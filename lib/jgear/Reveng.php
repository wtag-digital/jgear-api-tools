<?php

	/**
	 * Reverse engineering.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     https://wtag.com.br/jgear
	 * @since   1.0.0
	 * @version 1.0.0, 23 Apr 2020
	 */
	 
	namespace lib\jgear;	
	
	/*
	 * SPL
	 */	 	
	spl_autoload_register(function ($class) {
		require_once("../../" . $class . ".php");
	});	 
	
	use lib\jgear;
	use src\logic;
	
	/*
	 * Start the configured connection
	 */
	$logicConnection = new logic\Connection(); 
	if ($logicConnection->isOracle()) {
		$settings = simplexml_load_file("../../_dev/bin/settings.xml");		 
		$server = $settings->server;
		$db_username = $settings->username;
		$db_password = $settings->password;
		$service_name = "";
		$sid = $settings->oracle->sid;
		$port = $settings->oracle->port;
		$dbtns = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = ". $server . ")(PORT = " . 
				$port . ")) (CONNECT_DATA = (SERVICE_NAME = " . $service_name . ") (SID = " . $sid . 
				")))";
		$connection = new \PDO("oci:dbname=" . $dbtns . ";charset=utf8", $db_username, $db_password, array(
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_EMULATE_PREPARES => false));	
		$settings = simplexml_load_file("../../_dev/bin/settings.xml");	 
		$reveng = new jgear\Reveng($connection);
		$reveng->oracleBuild($settings);
	} else {
		$dao = new jgear\Dao(new logic\Connection());
		$dao->beginTransaction();
		$settings = simplexml_load_file("../../_dev/bin/settings.xml"); 
		$reveng = new jgear\Reveng($dao->getConnection());
		$reveng->build($settings);
		$dao->close();
	}
	/**
	 * Reveng
	 */
	class Reveng {
	
		private $connection;
		
		/**
		 * @param {Object} connection
		 */
		public function __construct($connection) {
			$this->connection = $connection;
		}

		public function oracleBuild($settings) {	
			$builder = "@ECHO OFF";
			$fileCfgXml = "oracle." . $settings->database . ".cfg.xml";
			$cfgXml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<hibernate-configuration>
    <session-factory name=\"model_factory\">
    	<property name=\"hibernate.dialect\">org.hibernate.dialect.Oracle10gDialect</property>
		<property name=\"hibernate.jmodelc.use_streams_for_binary=\">true</property>   
		<property name=\"hibernate.connection.datasource\">java:/comp/env/jdbc/oracle/" . $settings->database . "</property>
		<property name=\"current_session_context_class\">thread</property>
		<property name=\"hibernate.show_sql\">true</property>
		<property name=\"hibernate.format_sql\">true</property>";	
			$tables = $this->connection->prepare("
					select 
						object_name
					from 
						user_objects
					where 
						(object_type = 'TABLE' OR
						object_type = 'VIEW')
					order 
						by object_name"); // AND object_name LIKE 'AGENDA%'
			$tables->execute();
			$tableRows = $tables->fetchAll();
			foreach ($tableRows as $tableRow) {	
				$cfgXml .= "
		<mapping class=\"br.com." . $settings->enterprise . "." . str_replace("-", "", $settings->project) . ".io.entity." . ucfirst($this->conversionToUppercaseAfterUnderline(strtolower($tableRow["OBJECT_NAME"]))) . "\" />";
				$builder .= "
SET BIN_TARGET=%~dp0/map/" . strtolower($tableRow["OBJECT_NAME"]) . ".php
php \"%BIN_TARGET%\" %*";
				$filename = "map/" . strtolower($tableRow["OBJECT_NAME"]) . ".php";
				if (!file_exists($filename)) {
					$script = '<?php 

	/**
	 * ' . ucfirst($this->conversionToUppercaseAfterUnderline(strtolower($tableRow["OBJECT_NAME"]))) . ' map.
	 *
	 * @author  JGear <https://wtag.com.br/jgear>
	 * @since   1.0.0
	 * @version 1.0.0, ' . date("d M Y") . '
	 */
	 
	/*
	 * SPL
	 */	 	
	spl_autoload_register(function ($class) {
		require_once("../../" . $class . ".php");
	});	 
			
	use lib\\jgear;';
				
					$script .= "
			
	\$enterprise = \"" . $settings->enterprise . "\";
	\$project = \"" . $settings->project . "\";
	\$tableReference = \"" . strtolower($tableRow["OBJECT_NAME"]) . "\";	
	\$table = \"" . $this->conversionToUppercaseAfterUnderline(strtolower($tableRow["OBJECT_NAME"])) . "\";";	
				
					$script .= 
	"
			
	/*
	 * \$fields = array('field' => 'type');
	 *
	 * Types: Date, Integer, String
	 */ ";
				
					$script .= $this->getOracleColumn($tableRow["OBJECT_NAME"]);
				
					$script .= "
				
	/*
	 *\$fk = array(\"table\" => \"field\");
	 */";
				 
					$script .= $this->getOracleFk($tableRow["OBJECT_NAME"]);
					
					$script .=  
	"

	/*
	 * Builder
	 */
	\$builder = new jgear\\Builder();	
	\$builder->entity(\$enterprise, \$project, \$tableReference, \$fieldsReference, \$fkReference);
	\$builder->input(\$enterprise, \$project, \$table, \$fieldsReference, \$fkReference);	
	\$builder->output(\$enterprise, \$project, \$table, \$fieldsReference, \$fkReference);
	\$builder->controller(\$enterprise, \$project, \$table, \$fields, \$fk);	
	\$builder->dao(\$enterprise, \$project, \$table, \$fields, \$fk);	
	\$builder->rest(\$enterprise, \$project, \$tableReference, \$fieldsReference, \$fkReference);";
								
					$script .= '
				
?>';
					$fo = fopen($filename, "w");
					$fw = fwrite($fo, $script);	
					fclose($fo);
				}
			}
			$cfgXml .= "
	</session-factory>
</hibernate-configuration>";
			$fo = fopen("../../src/" . $settings->project . "-api/src/main/resources/" . $fileCfgXml, "w");
			$fw = fwrite($fo, $cfgXml);	
			fclose($fo);
			$filename = "builder.bat";
			if (!file_exists($filename)) {
				$fo = fopen($filename, "w");
				$fw = fwrite($fo, $builder . "
SET BIN_TARGET=%~dp0/builder.bat
del \"%BIN_TARGET%\" %*");	
				fclose($fo);
			}	
		}
		
		/**
		 * @param table String
		 * @return Array
		 */
		public function getOracleColumn($table) {
			$arrayColl = '
	$fieldsReference = array(';		
			$cont = 0;			
			$columns = $this->connection->prepare("
					select 
						column_name,
						data_type
					from 
						user_tab_cols
					where 
						table_name = '" . $table . "'");
			$columns->execute();
			$columnRows = $columns->fetchAll();
			foreach ($columnRows as $columnRow) {
				$control = false;
				$fkReferences = $this->connection->prepare("
						SELECT 
							a.column_name column_name, 
							b.table_name parent_table, 
							b.column_name parent_column
						FROM 
							user_cons_columns a
						JOIN 
							user_constraints c ON a.owner = c.owner AND 
							a.constraint_name = c.constraint_name
						JOIN 
							user_cons_columns b ON c.owner = b.owner AND 
							c.r_constraint_name = b.constraint_name
						WHERE 
							c.constraint_type = 'R' AND 
							a.table_name = '" . $table . "'");					
				$fkReferences->execute();
				$fkReferenceRows = $fkReferences->fetchAll();
				foreach ($fkReferenceRows as $fkReferenceRow) {
					if ($fkReferenceRow["COLUMN_NAME"] == $columnRow["COLUMN_NAME"]) {
						$control = true;	
					}
				}
				if ($control == false) {			
					if ($cont == 0) {
						$arrayColl .= "'" . strtolower($columnRow["COLUMN_NAME"]) . "' => '" . $this->getTypes($columnRow["COLUMN_NAME"], $columnRow["DATA_TYPE"]) . "'";
					} else {
						$arrayColl .= ",
			'" . strtolower($columnRow["COLUMN_NAME"]) . "' => '" . $this->getTypes($columnRow["COLUMN_NAME"], $columnRow["DATA_TYPE"]) . "'";
					}
					$cont++;
				}
			}
			$arrayColl .="
	);";	
			$arrayColl .= '
			
	$fields = array(';
			$cont = 0;
			foreach ($columnRows as $columnRow) {
				$control = false;
				$fkReferences = $this->connection->prepare("
						SELECT 
							a.column_name column_name, 
							b.table_name parent_table, 
							b.column_name parent_column
						FROM 
							user_cons_columns a
						JOIN 
							user_constraints c ON a.owner = c.owner AND 
							a.constraint_name = c.constraint_name
						JOIN 
							user_cons_columns b ON c.owner = b.owner AND 
							c.r_constraint_name = b.constraint_name
						WHERE 
							c.constraint_type = 'R' AND 
							a.table_name = '" . $table . "'");					
				$fkReferences->execute();
				$fkReferenceRows = $fkReferences->fetchAll();
				foreach ($fkReferenceRows as $fkReferenceRow) {
					if ($fkReferenceRow["COLUMN_NAME"] == $columnRow["COLUMN_NAME"]) {
						$control = true;	
					}
				}
				if ($control == false) {			
					if ($cont == 0) {
						$arrayColl .= "'" . $this->conversionToUppercaseAfterUnderline(strtolower($columnRow["COLUMN_NAME"])) . "' => '" . $this->getTypes($columnRow["COLUMN_NAME"], $columnRow["DATA_TYPE"]) . "'";
					} else {
						$arrayColl .= ",
			'" . $this->conversionToUppercaseAfterUnderline(strtolower($columnRow["COLUMN_NAME"])) . "' => '" . $this->getTypes($columnRow["COLUMN_NAME"], $columnRow["DATA_TYPE"]) . "'";
					}
					$cont++;
				}
			}	
			$arrayColl .="
	);";		
			return $arrayColl;
		}
		
		/**
		 * @param {String} table
		 * return {String}
		 */
		public function getOracleFk($table) {
			$fks = '
	$fkReference = array(';
			$cont = 0;	
			$fkReferences = $this->connection->prepare("
					SELECT 
						a.column_name column_name, 
						b.table_name parent_table, 
						b.column_name parent_column
					FROM 
						user_cons_columns a
					JOIN 
						user_constraints c ON a.owner = c.owner AND 
						a.constraint_name = c.constraint_name
					JOIN 
						user_cons_columns b ON c.owner = b.owner AND 
						c.r_constraint_name = b.constraint_name
					WHERE 
						c.constraint_type = 'R' AND 
						a.table_name = '" . $table . "'");					
			$fkReferences->execute();
			$fkReferenceRows = $fkReferences->fetchAll();
			foreach ($fkReferenceRows as $fkReferenceRow) {
				if ($cont == 0)
					$fks .= "'" . strtolower($fkReferenceRow['PARENT_TABLE']) . "' => '" . strtolower($fkReferenceRow['COLUMN_NAME']) . "'";
				else {
					$fks .= ",
			'" . strtolower($fkReferenceRow['PARENT_TABLE']) . "' => '" . strtolower($fkReferenceRow['COLUMN_NAME']) . "'";
				}
				$cont++;
			}
			$fks .= "
	);";
			$fks .= '
			
	$fk = array(';	
			$cont = 0;
			$fkReferences = $this->connection->prepare("
					SELECT 
						a.column_name column_name, 
						b.table_name parent_table, 
						b.column_name parent_column
					FROM 
						user_cons_columns a
					JOIN 
						user_constraints c ON a.owner = c.owner AND 
						a.constraint_name = c.constraint_name
					JOIN 
						user_cons_columns b ON c.owner = b.owner AND 
						c.r_constraint_name = b.constraint_name
					WHERE 
						c.constraint_type = 'R' AND 
						a.table_name = '" . $table . "'");					
			$fkReferences->execute();
			$fkReferenceRows = $fkReferences->fetchAll();
			foreach ($fkReferenceRows as $fkReferenceRow) {
				if ($cont == 0)
					$fks .= "'" . $this->conversionToUppercaseAfterUnderline(strtolower($fkReferenceRow["PARENT_TABLE"])) . "' => '" . $this->conversionToUppercaseAfterUnderline(strtolower($fkReferenceRow["COLUMN_NAME"])) . "'";
				else {
					$fks .= ",
			'" . $this->conversionToUppercaseAfterUnderline(strtolower($fkReferenceRow["PARENT_TABLE"])) . "' => '" . $this->conversionToUppercaseAfterUnderline(strtolower($fkReferenceRow["COLUMN_NAME"])) . "'";
				}
				$cont++;
			}
			$fks .= "
	);";			
			return $fks;					  
		}

		public function build($settings) {
			$sql = "
				SELECT 
					table_name AS 'table' 
				FROM 
					information_schema.tables
				WHERE 
					table_schema = DATABASE()";
					
			$result = $this->connection->execute($sql);
			
			$builder = "@ECHO OFF";
			
			$fileCfgXml = "mysql." . $settings->database . ".cfg.xml";
			$cfgXml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<hibernate-configuration>
    <session-factory name=\"db_factory\">
    	<property name=\"hibernate.dialect\">org.hibernate.dialect.MySQLInnoDBDialect</property>
		<property name=\"hibernate.jmodelc.use_streams_for_binary=\">true</property>   
		<property name=\"hibernate.connection.datasource\">java:/comp/env/jdbc/mysql/" . $settings->database . "</property>
		<property name=\"current_session_context_class\">thread</property>
		<property name=\"hibernate.show_sql\">true</property>
		<property name=\"hibernate.format_sql\">true</property>";
			
			while ($row = $result->fetch_assoc()) {		
				$cfgXml .= "
		<mapping class=\"br.com." . $settings->enterprise . "." . str_replace("-", "", $settings->project) . ".io.entity." . ucfirst($this->conversionToUppercaseAfterUnderline($row["table"])) . "\" />";
				
				$builder .= "
SET BIN_TARGET=%~dp0/map/" . $row["table"] . ".php
php \"%BIN_TARGET%\" %*";
				
				$filename = "map/" . $row["table"] . ".php";
				
				if (!file_exists($filename)) {
					$script = '<?php 

	/**
	 * Generated by JGear Framework 
	 *
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/jgear
	 */
	 
	/*
	 * SPL
	 */	 	
	spl_autoload_register(function ($class) {
		require_once("../../" . $class . ".php");
	});	 
			
	use lib\\jgear;';
				
					$script .= "
			
	\$enterprise = \"" . $settings->enterprise . "\";
	\$project = \"" . $settings->project . "\";
	\$tableReference = \"" . $row["table"] . "\";	
	\$table = \"" . $this->conversionToUppercaseAfterUnderline($row["table"]) . "\";";	
				
					$script .= 
	"
			
	/*
	 * \$fields = array('field' => 'type');
	 *
	 * Types: Date, Integer, String
	 */ ";
				
					$script .= $this->getColumn($row["table"]);
				
					$script .= "
				
	/*
	 *\$fk = array(\"table\" => \"field\");
	 */";
				 
					$script .= $this->getFk($row["table"]);
					
					$script .=  
	"

	/*
	 * Builder
	 */
	\$builder = new jgear\\Builder();	
	\$builder->entity(\$enterprise, \$project, \$tableReference, \$fieldsReference, \$fkReference);
	\$builder->input(\$enterprise, \$project, \$table, \$fieldsReference, \$fkReference);	
	\$builder->output(\$enterprise, \$project, \$table, \$fieldsReference, \$fkReference);
	\$builder->controller(\$enterprise, \$project, \$table, \$fields, \$fk);	
	\$builder->dao(\$enterprise, \$project, \$table, \$fields, \$fk);	
	\$builder->rest(\$enterprise, \$project, \$tableReference, \$fieldsReference, \$fkReference);";
								
					$script .= '
				
?>';

				}

				if (!file_exists($filename)) {
					$fo = fopen($filename, "w");
					$fw = fwrite($fo, $script);	
					fclose($fo);
				}
			}
			
			$cfgXml .= "
	</session-factory>
</hibernate-configuration>";
			
			$fo = fopen("../../src/" . $settings->project . "-api/src/main/resources/" . $fileCfgXml, "w");
			$fw = fwrite($fo, $cfgXml);	
			fclose($fo);
			
			$filename = "builder.bat";
			
			if (!file_exists($filename)) {
				$fo = fopen($filename, "w");
				$fw = fwrite($fo, $builder . "
SET BIN_TARGET=%~dp0/builder.bat
del \"%BIN_TARGET%\" %*");	

				fclose($fo);
			}

			$this->connection->free($result);
		}
		
		/**
		 * @param {String} table
		 * return {String}
		 */
		public function getFk($table) {
			$fks = '
	$fkReference = array(';
			
			$cont = 0;
			
			$sql = 
					"SELECT 
						TABLE_NAME AS 'table_name',
						COLUMN_NAME AS 'column_name',
						REFERENCED_COLUMN_NAME AS 'referenced_column_name',
						REFERENCED_TABLE_NAME AS 'referenced_table_name'
					FROM 
						information_schema.KEY_COLUMN_USAGE
					WHERE 
						TABLE_SCHEMA = DATABASE() AND
						REFERENCED_TABLE_SCHEMA = DATABASE() AND
						TABLE_NAME = '" . $table . "'";
								   
			$result = $this->connection->execute($sql);

			while ($row = $result->fetch_assoc()) {
				if ($cont == 0)
					$fks .= "'" . $row['referenced_table_name'] . "' => '" . $row['column_name'] . "'";
				else {
					$fks .= ",
			'" . $row['referenced_table_name'] . "' => '" . $row['column_name'] . "'";
				}
				
				$cont++;
			}
			
			$fks .= "
	);";
			
			$fks .= '
			
	$fk = array(';
			
			$cont = 0;
			
			$sql = 
					"SELECT 
						TABLE_NAME AS 'table_name',
						COLUMN_NAME AS 'column_name',
						REFERENCED_COLUMN_NAME AS 'referenced_column_name',
						REFERENCED_TABLE_NAME AS 'referenced_table_name'
					FROM 
						information_schema.KEY_COLUMN_USAGE
					WHERE 
						TABLE_SCHEMA = DATABASE() AND
						REFERENCED_TABLE_SCHEMA = DATABASE() AND
						TABLE_NAME = '" . $table . "'";
								   
			$result = $this->connection->execute($sql);

			while ($row = $result->fetch_assoc()) {
				if ($cont == 0)
					$fks .= "'" . $this->conversionToUppercaseAfterUnderline($row['referenced_table_name']) . "' => '" . $this->conversionToUppercaseAfterUnderline($row['column_name']) . "'";
				else {
					$fks .= ",
			'" . $this->conversionToUppercaseAfterUnderline($row['referenced_table_name']) . "' => '" . $this->conversionToUppercaseAfterUnderline($row['column_name']) . "'";
				}
				
				$cont++;
			}
			
			$fks .= "
	);";			
			return $fks;					  
		}
		
		/**
		 * @param table String
		 * @return Array
		 */
		public function getColumn($table) {
			$sql = "SHOW COLUMNS FROM " . $table;	   
			$result = $this->connection->execute($sql);

			$arrayColl = '
	$fieldsReference = array(';		
			
			$cont = 0;

			while ($row = $result->fetch_assoc()) {
				$control = false;
				
				$sqlFk = 
						"SELECT 
							TABLE_NAME AS 'table_name',
							COLUMN_NAME AS 'column_name',
							REFERENCED_COLUMN_NAME AS 'referenced_column_name',
							REFERENCED_TABLE_NAME AS 'referenced_table_name'
						FROM 
							information_schema.KEY_COLUMN_USAGE
						WHERE 
							TABLE_SCHEMA = DATABASE() AND
							REFERENCED_TABLE_SCHEMA = DATABASE() AND
							TABLE_NAME = '" . $table . "'";
							
				$resultFk = $this->connection->execute($sqlFk);
				
				while ($rowFk = $resultFk->fetch_assoc()) {
					if ($rowFk['column_name'] == $row['Field'])
						$control = true;	
				}
				
				if ($control == false) {			
					if ($cont == 0)
						$arrayColl .= "'" . $row['Field'] . "' => '" . $this->getTypes($row['Field'],$row['Type']) . "'";
					else
						$arrayColl .= ",
			'" . $row['Field'] . "' => '" . $this->getTypes($row['Field'], $row['Type']) . "'";
					
					$cont++;
				}
			}
			
			$arrayColl .="
	);";	
	
			$result = $this->connection->execute($sql);
	
			$arrayColl .= '
			
	$fields = array(';		
			
			$cont = 0;

			while ($row = $result->fetch_assoc()) {
				$control = false;
				
				$sqlFk = 
						"SELECT 
							TABLE_NAME AS 'table_name',
							COLUMN_NAME AS 'column_name',
							REFERENCED_COLUMN_NAME AS 'referenced_column_name',
							REFERENCED_TABLE_NAME AS 'referenced_table_name'
						FROM 
							information_schema.KEY_COLUMN_USAGE
						WHERE 
							TABLE_SCHEMA = DATABASE() AND
							REFERENCED_TABLE_SCHEMA = DATABASE() AND
							TABLE_NAME = '" . $table . "'";
							
				$resultFk = $this->connection->execute($sqlFk);
				
				while ($rowFk = $resultFk->fetch_assoc()) {
					if ($rowFk['column_name'] == $row['Field'])
						$control = true;	
				}
				
				if ($control == false) {			
					if ($cont == 0)
						$arrayColl .= "'" . $this->conversionToUppercaseAfterUnderline($row['Field']) . "' => '" . $this->getTypes($row['Field'],$row['Type']) . "'";
					else
						$arrayColl .= ",
			'" . $this->conversionToUppercaseAfterUnderline($row['Field']) . "' => '" . $this->getTypes($row['Field'], $row['Type']) . "'";
					
					$cont++;
				}
			}
			
			$arrayColl .="
	);";		

			return $arrayColl;
		}
		
		/**
		 * @param column String
		 * @param type String
		 * @return String
		 */
		public function getTypes($column, $type) {
			if (strtoupper($column) == "ID")
				return "Integer";
			else if (strpos(strtoupper($type), "BIGINT") !== false)
				return "Long";			
			else if (strpos(strtoupper($type), "INT") !== false)
				return "Integer";	
			else if (strpos(strtoupper($type), "DOUBLE") !== false)
				return "BigDecimal";
			else if (strpos(strtoupper($type), "DECIMAL") !== false)
				return "BigDecimal";			
			else if (strpos(strtoupper($type), "DATETIME") !== false)
				return "Date";
			else if (strpos(strtoupper($type), "DATE") !== false)
				return "Date";
			else if (strpos(strtoupper($type), "TIME") !== false)
				return "Date";
			else if (strpos(strtoupper($type), "VARCHAR") !== false)
				return "String";	
			else if (strpos(strtoupper($type), "CHAR") !== false)
				return "String";
			else if (strpos(strtoupper($type), "TEXT") !== false)
				return "String";
			else if (strpos(strtoupper($type), "BOOLEAN") !== false)
				return "Boolean";			
			else if (strpos(strtoupper($type), "NUMBER") !== false)
				return "Long";	
			else if (strpos(strtoupper($type), "CLOB") !== false)
				return "String";		
			else if (strpos(strtoupper($type), "TIMESTAMP") !== false)
				return "Date";				
			else 
				return "?";
		}	
		
		/**
		 * Conversion to uppercase after underline.
		 *
		 * @param  value String
		 * @return String
		 */
		public function conversionToUppercaseAfterUnderline($value) {
			$conversion = "";
			$conversionToUppercase = false;
			for ($x = 0; $x < strlen($value); $x++) {
				if ($conversionToUppercase) {
					$conversionToUppercase = false;
					$conversion .= strtoupper($value[$x]);
				} else {
					if ($value[$x] == "_") {
						$conversionToUppercase = true;
					} else {
						$conversion .= $value[$x];
					}
				}
			}
			return $conversion;
		}

	}

?>