<?php

	/**
	 * Data access object .
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     https://wtag.com.br/jgear
	 * @since   1.0.0
	 * @version 1.0.0, 23 Apr 2020
	 */
	 
	namespace lib\jgear;

	use lib\jgear;
	
	class Dao extends jgear\Sqli {

		private $sqli;
		
		private $server;
		private $username;
		private $password;
		private $database;

		private $itensPerPage;	
		private $position;	
		
		/**
		 * {Object} connection
		 */
		public function __construct($connection) { 
			$this->server = $connection->getServer();
			$this->username = $connection->getUsername();
			$this->password = $connection->getPassword();
			$this->database = $connection->getDatabase();
		}
		
		/**
		 * @param {String} parameter
		 * @return {String}
		 */
		public function prepare($parameter) {
			$statement = "";
			$statement = str_replace("\\", "", $parameter);
			$statement = str_replace("'", "\'", $statement);
			$statement = str_replace("\"", "\'", $statement);

			return $statement;
		}
		
		/**
		 * @param {Integer} itensPerPage
		 */
		public function setItensPerPage($itensPerPage) {
			$this->itensPerPage = $itensPerPage;
		}
		
		/**
		 * @param {Integer} position
		 */
		public function setPosition($position) {
			$this->position = $position;
		}
		
		/**
		 * @return {Object}
		 */
		public function getConnection() {
			return $this->sqli;
		}
		
		public function beginTransaction() {
			$this->sqli = new jgear\Sqli($this->server, $this->username, 
					$this->password, $this->database);

			$this->sqli->beginTransaction();
			$this->sqli->setItensPerPage($this->itensPerPage);
			$this->sqli->setPosition($this->position);
		}

		public function commit() {
			$this->sqli->commit();
		}

		public function rollBack() {
			$this->sqli->rollBack();
		}

		public function close() {
			$this->sqli->close();
		}

	}

?>