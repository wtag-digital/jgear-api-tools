<?php

	/**
	 * Builder.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     https://wtag.com.br/jgear
	 * @since   1.0.0
	 * @version 1.0.0, 23 Apr 2020
	 */

	namespace lib\jgear;

	class Builder {
	
		public function __construct() { }	

		public function entity($enterprise, $project, $table, $fields, $fk) {
			$serial = rand(1, 8);
			for ($x = 1; $x < 19; $x++) {
				$serial .= rand(0, 9);
			}
			$serial .= "L";
			$attributes = "";
			$gettersSetters = "";
			$seeFkPackages = "";
			if (count($fields) > 0) {
				$id = "";
				$persistenceId = "";
				$temporal = "";
				$javaPackage = "";
				foreach ($fields as $field => $type) {	
					$field = strtolower($field);
					if ($type == "Date") {
						$javaPackage = "util";
					} else if ($type == "BigDecimal") {
						$javaPackage = "math";
					} else {
						$javaPackage = "lang";
					}
					if ($field == "id") {
						$id = "@Id
	";
					} else {
						$id = "";
					}
					if ($id != "") {
						$persistenceId = "
     * @see    javax.persistence.Id";
					} else {
						$persistenceId = "";
					}
					if ($type == "Date") {
						$temporal = "@Temporal(TemporalType.TIMESTAMP)
	";
					} else {
						$temporal = "";
					}					
					$attributes .= "
	private " . $type . " " . $this->conversionToUppercaseAfterUnderline($field) . ";";
					$gettersSetters .= "
					
	/**
     * Get " . $this->conversionToUppercaseAfterUnderline($field) . ".
	 *
	 * @return " . $type . $persistenceId . "
     * @see    javax.persistence.Column 
	 * @see    java." . $javaPackage . "." . $type . "
     */	 
	" . $id . $temporal . "@Column(name = \"" . strtoupper($field) . "\")
	public " . $type . " get" . ucfirst($this->conversionToUppercaseAfterUnderline($field)) . "() {
		return " . $this->conversionToUppercaseAfterUnderline($field) . ";
	}";
					$gettersSetters .= "
	
	/**
     * Set " . $this->conversionToUppercaseAfterUnderline($field) . ".
	 *
	 * @param " . $this->conversionToUppercaseAfterUnderline($field) . " " . $type . "
	 * @see   java." . $javaPackage . "." . $type . "
     */
	public void set" . ucfirst($this->conversionToUppercaseAfterUnderline($field)) . "(final " . $type . " " . $this->conversionToUppercaseAfterUnderline($field) . ") {
		this." . $this->conversionToUppercaseAfterUnderline($field) . " = " . $this->conversionToUppercaseAfterUnderline($field) . ";
	}";	
				}
			}
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {	
					$foreignKey = strtolower($foreignKey);
					$seeFkPackages .= "
 * @see     br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($this->conversionToUppercaseAfterUnderline($foreignTable));
					$attributes .= "
	private " . ucfirst($this->conversionToUppercaseAfterUnderline($foreignTable)) . " " . $this->conversionToUppercaseAfterUnderline($foreignTable) . ";";
					$gettersSetters .= "
					
	/**
     * Get " . $this->conversionToUppercaseAfterUnderline($foreignTable) . ".
	 *
	 * @return " . ucfirst($this->conversionToUppercaseAfterUnderline($foreignTable)) . "
	 * @see    javax.persistence.ManyToOne
	 * @see    javax.persistence.JoinColumn
	 * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($this->conversionToUppercaseAfterUnderline($foreignTable)) . "
     */	 
	@ManyToOne(fetch = FetchType.LAZY)
	@JoinColumn(name = \"" . strtoupper($foreignKey) . "\")
	public " . ucfirst($this->conversionToUppercaseAfterUnderline($foreignTable)) . " get" . ucfirst($this->conversionToUppercaseAfterUnderline($foreignTable)) . "() {
		return " . $this->conversionToUppercaseAfterUnderline($foreignTable) . ";
	}";
					$gettersSetters .= "
					
	/**
     * Set " . $this->conversionToUppercaseAfterUnderline($foreignTable) . ".
	 *
	 * @param " . $this->conversionToUppercaseAfterUnderline($foreignTable) . " " . ucfirst($this->conversionToUppercaseAfterUnderline($foreignTable)) . "
	 * @see   br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($this->conversionToUppercaseAfterUnderline($foreignTable)) . "
     */
	public void set" . ucfirst($this->conversionToUppercaseAfterUnderline($foreignTable)) . "(final " . ucfirst($this->conversionToUppercaseAfterUnderline($foreignTable)) . " " . $this->conversionToUppercaseAfterUnderline($foreignTable) . ") {
		this." . $this->conversionToUppercaseAfterUnderline($foreignTable) . " = " . $this->conversionToUppercaseAfterUnderline($foreignTable) . ";
	}";	
				}
			}			
			$jgear = "package br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity;
			
import java.io.Serializable;
import java.util.Date;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

import org.apache.commons.lang3.math.NumberUtils;

/**
 * " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . " entity.
 * 
 * @author  JGear <https://wtag.com.br/jgear>
 * @see     javax.persistence.Entity
 * @see     javax.persistence.Table
 * @see     java.io.Serializable
 * @see     java.lang.Integer
 * @see     java.lang.String
 * @see     java.util.Date" . $seeFkPackages . "
 * @since   1.0.0
 * @version 1.0.0, " . date("d M Y") . "
 */
@Entity
@Table(name=\"" . strtoupper($table) . "\")
public class " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . " implements Serializable {
	
	private static final long serialVersionUID = " . $serial . ";
	private static final String " . strtoupper($this->conversionToUppercaseAfterUnderline($table)) . "_START_BRACKETS_ID = \"" . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . " [id=\";
	private static final String END_BRACKETS = \"]\";" . $attributes . "
	
	/**
     * Construct method.
     */
    public " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "() { }" . $gettersSetters . "
	
	/**
     * Hash code.
     * 
     * @return int
     * @see    org.apache.commons.lang3.math.NumberUtils
     */
	@Override
	public int hashCode() {
		int result = NumberUtils.INTEGER_ZERO;
		if (id != null) {
			result = id.hashCode();
		}
		return result;
	}

    /**
     * Equals.
     * 
     * @return boolean
     * @see    java.lang.Object
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "
     */
	@Override
	public boolean equals(Object object) {
		if (this == object) {
			return true;
		} if (object == null) {
			return false;
		} if (getClass() != object.getClass()) {
			return false;
		}
		" . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . " other = (" . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . ") object;
		if (id == null) {
			if (other.id != null) {
				return false;
			}
		} else if (!id.equals(other.id)) {
			return false;
		}
		return true;
	}

    /**
     * To string.
     * 
     * @return String
     * @see    java.lang.String
     * @see    java.lang.StringBuilder
     */
	@Override
	public String toString() {
		StringBuilder stringBuilder = new StringBuilder();
		stringBuilder.append(" . strtoupper($this->conversionToUppercaseAfterUnderline($table)) . "_START_BRACKETS_ID);
		stringBuilder.append(id);
		stringBuilder.append(END_BRACKETS);
		return stringBuilder.toString();
	}

}";
			if (!file_exists("../../src/" . $project . "-io/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/io/entity/" . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . ".java")) {
				$fopen = fopen("../../src/" . $project . "-io/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/io/entity/" . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . ".java", "w");
				$fwrite = fwrite($fopen, $jgear);
				fclose($fopen);
			}
		}
		
		public function input($enterprise, $project, $table, $fields, $fk) {
			$attributes = "";
			$gettersSetters = "";
			$importFkPackages = "";
			$seeFkPackages = "";
			$seeFkConversionPackages = "";
			$conversion = "";
			$validation = "";
			$fieldsConversion = "";
			$entityConversion = "";
			$inputEntity = "";
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {
					$constantField = $field;
					$field = $this->conversionToUppercaseAfterUnderline(strtolower($field));
					if ($field == $table) {
						$inputEntity .= "
				" . $type . " " . $field . "Attribute = entity.get" . ucfirst($field) . "();
				if (" . $field . "Attribute != null) {
					" . $table . ".set" . ucfirst($field) . "(" . $field . "Attribute);
				}";		
					} else {
						$inputEntity .= "
				" . $type . " " . $field . " = entity.get" . ucfirst($field) . "();
				if (" . $field . " != null) {
					" . $table . ".set" . ucfirst($field) . "(" . $field . ");
				}";		
					}
					if ($type == "Date") {
						$entityConversion .= "
		" . $table . ".set" . ucfirst($field) . "(stringConversionToDate(" . $field . "));";			
					} else {
						if ($field == $table) {
							$entityConversion .= "
		" . $table . ".set" . ucfirst($field) . "(this." . $field . ");";	
						} else {
							$entityConversion .= "
		" . $table . ".set" . ucfirst($field) . "(" . $field . ");";								
						}
					}		
					$fieldsConversion .= "
			" . $table . "Input.set" . ucfirst($field) . "(" . $table . "Output.get" . ucfirst($field) . "());";
					if ($field != "id") {
						if ($type == "Integer") {
							$validation .= "		
			if (integerIsNotValid(" . $field . ")) {
				return getIsRequiredMessage(" . strtoupper($constantField) . ");
			}";
						} else if ($type == "Long") {
							$validation .= "		
			if (longIsNotValid(" . $field . ")) {
				return getIsRequiredMessage(" . strtoupper($constantField) . ");
			}";
						} else if ($type == "BigDecimal") {
							$validation .= "		
			if (bigDecimalIsNotValid(" . $field . ")) {
				return getIsRequiredMessage(" . strtoupper($constantField) . ");
			}";			
						} else if ($type == "Boolean") {
							$validation .= "		
			if (booleanIsNotValid(" . $field . ")) {
				return getIsRequiredMessage(" . strtoupper($constantField) . ");
			}";						
						} else {
							$validation .= "		
			if (stringIsNotValid(" . $field . ")) {
				return getIsRequiredMessage(" . strtoupper($constantField) . ");
			}";
						}
					}
					$attributes .= "
	protected static final String " . strtoupper($constantField) . " = \"" . $field . "\";";
				}		
				foreach ($fk as $foreignTable => $foreignKey) {	
					$constantForeignTable = $foreignTable;
					$foreignTable = $this->conversionToUppercaseAfterUnderline($foreignTable);
					$foreignKey = $this->conversionToUppercaseAfterUnderline(strtolower($foreignKey));
					$inputEntity .= "
			    " . ucfirst($foreignTable) . " " . $foreignTable . " = entity.get" . ucfirst($foreignTable) . "();
				if (" . $foreignTable . " != null) {
					" . $table . ".set" . ucfirst($foreignTable) . "(" . $foreignTable . ");
				}";							
					$entityConversion .= "
		" . $table . ".set" . ucfirst($foreignTable) . "(" . $foreignTable . "Input.inputConversionToEntity());";								
					$fieldsConversion .= "				
			" . ucfirst($foreignTable) . "Input " . $foreignTable . "Input = new " . ucfirst($foreignTable) . "Input();
			" . $table . "Input.set" . ucfirst($foreignTable) . "Input(" . $foreignTable . "Input.outputConversionToInput(" . $table . "Output.
					get" . ucfirst($foreignTable) . "Output()));";
					$validation .= " 
			if (" . $foreignTable . "Input == null || integerIsNotValid(" . $foreignTable . "Input.getId())) {
				return getIsRequiredMessage(" . strtoupper($constantForeignTable) . "_INPUT);
			}";
					$attributes .= "
	protected static final String " . strtoupper($constantForeignTable) . "_INPUT = \"" . $foreignTable . "Input\";";
				}					
				foreach ($fields as $field => $type) {
					$field = $this->conversionToUppercaseAfterUnderline(strtolower($field));
					if ($type == "Date") {
						$type = "String";
						$conversion .= "
			" . $table . "Input.set" . ucfirst($field) . "(dateConversionToString(" . $table . ".get" . ucfirst($field) . "()));";
					} else {
						$conversion .= "
			" . $table . "Input.set" . ucfirst($field) . "(" . $table . ".get" . ucfirst($field) . "());";	
					}
					$attributes .= "
	private " . $type . " " . $field . ";";
					$gettersSetters .= "
					
	/**
     * Get " . $field . ".
	 *
	 * @return " . $type . "
	 * @see    java.lang." . $type . "
     */	 
	public " . $type . " get" . ucfirst($field) . "() {
		return " . $field . ";
	}";
					$gettersSetters .= "
	
	/**
     * Set " . $field . ".
	 *
	 * @param " . $field . " " . $type . "
	 * @see   java.lang." . $type . "
     */
	public void set" . ucfirst($field) . "(final " . $type . " " . $field . ") {
		this." . $field . " = " . $field . ";
	}";	
				}
			}
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {	
					$foreignTable = $this->conversionToUppercaseAfterUnderline($foreignTable);
					$foreignKey = $this->conversionToUppercaseAfterUnderline(strtolower($foreignKey));
					$conversion .= "
			" . ucfirst($foreignTable) . "Input " . $foreignTable . "Input = new " . ucfirst($foreignTable) . "Input();
            if (" . $table . ".get" . ucfirst($foreignTable) . "() != null) {
            	" . $table . "Input.set" . ucfirst($foreignTable) . "Input(" . $foreignTable . "Input.entityConversionToInput(
            			" . $table . ".get" . ucfirst($foreignTable) . "()));
            }";
					$importFkPackages .= "
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($foreignTable) . ";";				
					$seeFkPackages .= "
 * @see     br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($foreignTable) . "Input";
					$seeFkConversionPackages .= "
	 * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($foreignTable) . "Input"; 
					$attributes .= "
	private " . ucfirst($foreignTable) . "Input " . $foreignTable . "Input;";
					$gettersSetters .= "
					
	/**
     * Get " . $foreignTable . "Input.
	 *
	 * @return " . ucfirst($foreignTable) . "
	 * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($foreignTable) . "Input
     */
	public " . ucfirst($foreignTable) . "Input get" . ucfirst($foreignTable) . "Input() {
		return " . $foreignTable . "Input;
	}";
					$gettersSetters .= "
					
	/**
     * Set " . $foreignTable . "Input.
	 *
	 * @param " . $foreignTable . "Input " . ucfirst($foreignTable) . "Input
	 * @see   br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($foreignTable) . "Input
     */
	public void set" . ucfirst($foreignTable) . "Input(final " . ucfirst($foreignTable) . "Input " . $foreignTable . "Input) {
		this." . $foreignTable . "Input = " . $foreignTable . "Input;
	}";	
				}
			}	
			$attributes .= "
	private List<" . ucfirst($table) . "Input> " . $table . "InputList;";
			$jgear = "package br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input;
			
import java.util.Date;
import java.util.List;

import org.apache.commons.lang3.math.NumberUtils;

import br.com." . $enterprise . ".common.api.Input;
import br.com." . $enterprise . ".common.api.InputUtil;
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . ";" . $importFkPackages . "
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output;

/**
 * " . ucfirst($table) . " input.
 * 
 * @author  JGear <https://wtag.com.br/jgear>
 * @param   " . ucfirst($table) . "Input " . ucfirst($table) . "Input
 * @param   " . ucfirst($table) . " " . ucfirst($table) . "
 * @see     br.com." . $enterprise . ".common.api.InputUtil
 * @see     br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Input
 * @see     br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . "
 * @see     java.lang.Integer
 * @see     java.lang.String" . $seeFkPackages . "
 * @see     java.util.List
 */
public class " . ucfirst($table) . "Input extends InputUtil<" . ucfirst($table) . "Input, " . ucfirst($table) . "> { 
	" . $attributes . "
	
	/**
     * Construct method.
     */
    public " . ucfirst($table) . "Input() { }" . $gettersSetters . "
	
	/**
	 * Get " . $table . " input list.
	 * 
	 * @return List<" . ucfirst($table) . "Input>
	 * @see    java.util.List
	 * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Input
	 */
	public List<" . ucfirst($table) . "Input> get" . ucfirst($table) . "InputList() {
		return " . $table . "InputList;
	}

	/**
	 * Set " . $table . " input list.
	 * 
	 * @param " . $table . "InputList List<" . ucfirst($table) . "Input>
	 * @see   java.util.List
	 * @see   br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Input 
	 */
	public void set" . ucfirst($table) . "InputList(final List<" . ucfirst($table) . "Input> " . $table . "InputList) {
		this." . $table . "InputList = " . $table . "InputList;
	}
	
	/**
	 * Check is valid.
	 * 
	 * @param  method String
	 * @return String
	 * @see    java.lang.String
	 */
	public String isValid(final String method) {
		if (method.equals(POST)) {" . $validation . "
		} else if (method.equals(PUT)) {
			if (integerIsNotValid(id)) {
				return getIsRequiredMessage(ID);
			}
		}
		return null;
	}

	/**
	 * Output conversion to input.
	 *
	 * @param  " . $table . " Output " . ucfirst($table) . "Output
	 * @return " . ucfirst($table) . "Input
	 * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Input
	 * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output" . $seeFkConversionPackages . "
	 */
	public " . ucfirst($table) . "Input outputConversionToInput(" . ucfirst($table) . "Output " . $table . "Output) {
		" . ucfirst($table) . "Input " . $table . "Input = new " . ucfirst($table) . "Input();
		if (" . $table . "Output != null) {" . $fieldsConversion . "
		}
		return " . $table . "Input;
	}	
	
	/**
	 * Input conversion to entity.
	 * 
	 * @return " . ucfirst($table) . "
	 * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . "
	 */
	public " . ucfirst($table) . " inputConversionToEntity() {
		" . ucfirst($table) . " " . $table . " = new " . ucfirst($table) . "();" . $entityConversion . "
		return " . $table . ";
	}	
	
	/**
	 * Input conversion to input entity.
	 * 
	 * @param  input Input<" . ucfirst($table) . "Input>
	 * @param  " . $table . " " . ucfirst($table) . "
	 * @return Input<" . ucfirst($table) . ">
	 * @see    br.com." . $enterprise . ".common.api.Input
	 * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . "
	 * @see    java.lang.Integer
	 * @see    org.apache.commons.lang3.math.NumberUtils
	 * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Input
	 * @see    java.lang.String
	 * @see    java.util.Date
	 */
	public Input<" . ucfirst($table) . "> inputConversionToInputEntity(final Input<" . ucfirst($table) . "Input> input,
			final " . ucfirst($table) . " " . $table . ") {
		Input<" . ucfirst($table) . "> inputEntity = new Input<" . ucfirst($table) . ">();
		inputEntity.setFilterColumn(input.getFilterColumn());
		inputEntity.setFilterCondition(input.getFilterCondition());
		inputEntity.setFilterValue(input.getFilterValue());
		inputEntity.setOrderColumn(input.getOrderColumn());
		inputEntity.setOrderValue(input.getOrderValue());
		Integer page = input.getPage();
		if (page != null) {
			page = page + NumberUtils.INTEGER_ONE;
		}
		inputEntity.setPage(page);
		inputEntity.setPageSize(input.getPageSize());
		" . ucfirst($table) . "Input " . $table . "Input = input.getT();
		if (" . $table . "Input != null) {
			" . ucfirst($table) . " entity = " . $table . "Input.inputConversionToEntity();
			if (" . $table . " != null) {" . $inputEntity . "
				inputEntity.setT(" . $table . ");
			} else {
				inputEntity.setT(entity);
			}
		}
		return inputEntity;
	}

}";
			if (!file_exists("../../src/" . $project . "-io/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/io/input/" . ucfirst($table) . "Input.java")) {
				$fopen = fopen("../../src/" . $project . "-io/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/io/input/" . ucfirst($table) . "Input.java", "w");
				$fwrite = fwrite($fopen, $jgear);
				fclose($fopen);
			}
		}	

		public function output($enterprise, $project, $table, $fields, $fk) {
			$serial = rand(1, 8);
			for ($x = 1; $x < 19; $x++) {
				$serial .= rand(0, 9);
			}
			$serial .= "L";
			$attributes = "";
			$gettersSetters = "";
			$seeFkPackages = "";
			$conversion = "";
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {
					$field = $this->conversionToUppercaseAfterUnderline(strtolower($field));
					if ($type == "Date") {
						$type = "String";
						$conversion .= "
			" . $table . "Output.set" . ucfirst($field) . "(dateConversionToString(" . $table . ".get" . ucfirst($field) . "()));";
					} else {
						$conversion .= "
			" . $table . "Output.set" . ucfirst($field) . "(" . $table . ".get" . ucfirst($field) . "());";	
					}
					$attributes .= "
	private " . $type . " " . $field . ";";
					$gettersSetters .= "
					
	/**
     * Get " . $field . ".
	 *
	 * @return " . $type . "
	 * @see    java.lang." . $type . "
     */	 
	public " . $type . " get" . ucfirst($field) . "() {
		return " . $field . ";
	}";
					$gettersSetters .= "
	
	/**
     * Set " . $field . ".
	 *
	 * @param " . $field . " " . $type . "
	 * @see   java.lang." . $type . "
     */
	public void set" . ucfirst($field) . "(final " . $type . " " . $field . ") {
		this." . $field . " = " . $field . ";
	}";	
				}
			}
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {
					$foreignTable = $this->conversionToUppercaseAfterUnderline($foreignTable);
					$foreignKey = $this->conversionToUppercaseAfterUnderline(strtolower($foreignKey));
					$conversion .= "
			" . ucfirst($foreignTable) . "Output " . $foreignTable . "Output = new " . ucfirst($foreignTable) . "Output();
            if (" . $table . ".get" . ucfirst($foreignTable) . "() != null) {
            	" . $table . "Output.set" . ucfirst($foreignTable) . "Output(" . $foreignTable . "Output.entityConversionToOutput(
            			" . $table . ".get" . ucfirst($foreignTable) . "()));
            }";
					$seeFkPackages .= "
 * @see     br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($foreignTable) . "Output";
					$attributes .= "
	private " . ucfirst($foreignTable) . "Output " . $foreignTable . "Output;";
					$gettersSetters .= "
					
	/**
     * Get " . $foreignTable . "Output.
	 *
	 * @return " . ucfirst($foreignTable) . "
	 * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($foreignTable) . "Output
     */
	public " . ucfirst($foreignTable) . "Output get" . ucfirst($foreignTable) . "Output() {
		return " . $foreignTable . "Output;
	}";
					$gettersSetters .= "
					
	/**
     * Set " . $foreignTable . "Output.
	 *
	 * @param " . $foreignTable . "Output " . ucfirst($foreignTable) . "Output
	 * @see   br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($foreignTable) . "Output
     */
	public void set" . ucfirst($foreignTable) . "Output(final " . ucfirst($foreignTable) . "Output " . $foreignTable . "Output) {
		this." . $foreignTable . "Output = " . $foreignTable . "Output;
	}";	
				}
			}	
			$attributes .= "
	private List<" . ucfirst($table) . "Output> value;";		
			$gettersSetters .= "
	
	/**
	 * Get value.
	 * 
	 * @return List<" . ucfirst($table) . "Output>
	 * @see    java.util.List
	 * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output
	 */
	public List<" . ucfirst($table) . "Output> getValue() {
		return value;
	}

	/**
	 * Set value.
	 * 
	 * @param value List<" . ucfirst($table) . "Output>
	 * @see   java.util.List
	 * @see   br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output
	 */
	public void setValue(final List<" . ucfirst($table) . "Output> value) {
		this.value = value;
	}";
			$jgear = "package br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output;
			
import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

import org.apache.commons.lang3.math.NumberUtils;

import br.com." . $enterprise . ".common.api.OutputUtil;
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . ";

/**
 * " . ucfirst($table) . " output.
 * 
 * @author  JGear <https://wtag.com.br/jgear>
 * @param   " . ucfirst($table) . "Output " . ucfirst($table) . "Output
 * @param   " . ucfirst($table) . " " . ucfirst($table) . "
 * @see     br.com." . $enterprise . ".common.api.OutputUtil
 * @see     br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output
 * @see     br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . "
 * @see     java.io.Serializable
 * @see     java.lang.Integer
 * @see     java.lang.String" . $seeFkPackages . "
 * @since   1.0.0
 * @version 1.0.0, " . date("d M Y") . "
 */
public class " . ucfirst($table) . "Output extends OutputUtil<" . ucfirst($table) . "Output, " . ucfirst($table) . "> implements Serializable {
	
	private static final long serialVersionUID = " . $serial . ";
	private static final String " . strtoupper($table) . "_OUTPUT_START_BRACKETS_ID = \"" . ucfirst($table) . "Output [id=\";
	private static final String END_BRACKETS = \"]\";" . $attributes . "
	
	/**
     * Construct method.
     */
    public " . ucfirst($table) . "Output() { }" . $gettersSetters . "
	
	/**
	 * Entity list convertion to output list.
	 *  
     * @param  " . $this->conversionToUppercaseAfterUnderline($table) . "List List<" . ucfirst($table) . ">
     * @return List<" . ucfirst($table) . "Output>
     * @see    java.util.List
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output
     * @see    java.util.ArrayList
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . "
     */
    public List<" . ucfirst($table) . "Output> entityListConversionToOutputList(
    		final List<" . ucfirst($table) . "> " . $table . "List) {
        List<" . ucfirst($table) . "Output> " . $table . "OutList = new ArrayList<" . ucfirst($table) . "Output>();
        for (" . ucfirst($table) . " " . $table . " : " . $table . "List) {
            " . ucfirst($table) . "Output " . $table . "Output = entityConversionToOutput(" . $table . ");
            if (" . $table . "Output != null) {
                " . $table . "OutList.add(" . $table . "Output);
            }
        }
        return " . $table . "OutList;
    }	
	
    /**
     * Entity convertion to output.
     * 
     * @param  " . $table . " " . ucfirst($table) . "
     * @return " . ucfirst($table) . "Output
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . "
     */
    public " . ucfirst($table) . "Output entityConversionToOutput(final " . ucfirst($table) . " " . $table . ") {
        if (" . $table . " != null) {
            " . ucfirst($table) . "Output " . $table . "Output = new " . ucfirst($table) . "Output();" . $conversion . "
            return " . $table . "Output;
        } else {
            return null;
        }
    }	
	
	/**
     * Hash code.
     * 
     * @return int
     * @see    org.apache.commons.lang3.math.NumberUtils
     */
	@Override
	public int hashCode() {
		int result = NumberUtils.INTEGER_ZERO;
		if (id != null) {
			result = id.hashCode();
		}
		return result;
	}

    /**
     * Equals.
     * 
     * @return boolean
     * @see    java.lang.Object
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output
     */
	@Override
	public boolean equals(Object object) {
		if (this == object) {
			return true;
		} if (object == null) {
			return false;
		} if (getClass() != object.getClass()) {
			return false;
		}
		" . ucfirst($table) . "Output other = (" . ucfirst($table) . "Output) object;
		if (id == null) {
			if (other.id != null) {
				return false;
			}
		} else if (!id.equals(other.id)) {
			return false;
		}
		return true;
	}

    /**
     * To string.
     * 
     * @return String
     * @see    java.lang.String
     * @see    java.lang.StringBuilder
     */
	@Override
	public String toString() {
		StringBuilder stringBuilder = new StringBuilder();
		stringBuilder.append(" . strtoupper($table) . "_OUTPUT_START_BRACKETS_ID);
		stringBuilder.append(id);
		stringBuilder.append(END_BRACKETS);
		return stringBuilder.toString();
	}

}";
			if (!file_exists("../../src/" . $project . "-io/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/io/output/" . ucfirst($table) . "Output.java")) {
				$fopen = fopen("../../src/" . $project . "-io/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/io/output/" . ucfirst($table) . "Output.java", "w");
				$fwrite = fwrite($fopen, $jgear);
				fclose($fopen);
			}
		}
		
		public function controller($enterprise, $project, $table, $fields, $fk) {
			$jgear = "package br.com." . $enterprise . "." . str_replace("-", "", $project) . ".api.controller;

import java.util.List;

import org.apache.commons.lang3.math.NumberUtils;

import br.com." . $enterprise . ".common.api.Input;
import br.com." . $enterprise . ".common.api.Output;
import br.com." . $enterprise . ".common.api.Service;
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . ";
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Input;
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output;
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".api.model.dao." . ucfirst($table) . "Dao;

/**
 * " . ucfirst($table) . " controller.
 *
 * @author  JGear <https://wtag.com.br/jgear>
 * @param   " . ucfirst($table) . "Input " . ucfirst($table) . "Input
 * @param   " . ucfirst($table) . "Output " . ucfirst($table) . "Output
 * @param   " . ucfirst($table) . " " . ucfirst($table) . "
 * @see     br.com." . $enterprise . ".common.api.Service
 * @see     br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Input
 * @see     br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Output
 * @see     br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . "
 * @since   1.0.0
 * @version 1.0.0, " . date("d M Y") . "
 */
public class " . ucfirst($table) . "Controller extends Service<" . ucfirst($table) . "Input, " . ucfirst($table) . "Output, " . ucfirst($table) . "> {
    
    /**
     * Construct method.
     */
    public " . ucfirst($table) . "Controller() { }

    /**
     * Create.
     *
     * @param  input Input<" . ucfirst($table) . "Input>
     * @return Output<" . ucfirst($table) . "Output>
     * @see    java.lang.Override
     * @see    br.com." . $enterprise . ".common.api.Output
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output
     * @see    br.com." . $enterprise . ".common.api.Input
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Input
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . "
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".api.model.dao." . ucfirst($table) . "Dao
     * @see    java.lang.Exception
     */
    @Override
    public Output<" . ucfirst($table) . "Output> create(final Input<" . ucfirst($table) . "Input> input) {
        try {
            if (isValid(input, POST)) {
                Input<" . ucfirst($table) . "> inputEntity = inputConversionToInputEntity(input, null);
                " . ucfirst($table) . " " . $table . " = inputEntity.getT();
                " . ucfirst($table) . "Dao " . $table . "DAO = new " . ucfirst($table) . "Dao(getHibernate());
                " . $table . " = " . $table . "DAO.create(" . $table . ");
                setEntityInOutput(" . $table . ");
            }
        } catch (Exception exception) {
            output.setStatus(INTERNAL_ERROR, TRUE);
            output.setMessage(exception.getMessage());
        }
        return output;
    }

    /**
     * Read.
     *
     * @param  input Input<" . ucfirst($table) . "Input>
     * @return List<" . ucfirst($table) . "Output>
     * @see    java.lang.Override
     * @see    br.com." . $enterprise . ".common.api.Output
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output
     * @see    br.com." . $enterprise . ".common.api.Input
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Input
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . "
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".api.model.dao." . ucfirst($table) . "Dao
     * @see    org.apache.commons.lang3.math.NumberUtils
     * @see    java.lang.Exception
     */
    @Override
    public Output<" . ucfirst($table) . "Output> read(final Input<" . ucfirst($table) . "Input> input) {
        try {
            if (isValid(input, GET)) {
                Input<" . ucfirst($table) . "> inputEntity = inputConversionToInputEntity(input, null);
                " . ucfirst($table) . "Dao " . $table . "DAO = new " . ucfirst($table) . "Dao(getHibernate());
                List<" . ucfirst($table) . "> " . $table . "List = " . $table . "DAO.read(inputEntity);
                Integer size = " . $table . "DAO.getSize();
                if (size == null) {
                    if (" . $table . "List != null) {
                        size = " . $table . "List.size();
                    } else {
                        size = NumberUtils.INTEGER_ZERO;
                    }
                }                 
                setListInOutput(" . $table . "List, size);
            }
        } catch (Exception exception) {
            output.setStatus(INTERNAL_ERROR, FALSE);
            output.setMessage(exception.getMessage());
        }
        return output;
    }

    /**
     * Update.
     *
     * @param  input Input<" . ucfirst($table) . "Input>
     * @return Output<" . ucfirst($table) . "Output>
     * @see    java.lang.Override
     * @see    br.com." . $enterprise . ".common.api.Output
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output
     * @see    br.com." . $enterprise . ".common.api.Input
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Input
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".api.model.dao." . ucfirst($table) . "Dao
     * @see    java.lang.String
     * @see    java.lang.Integer
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . "
     * @see    java.lang.Boolean
     * @see    java.lang.Exception
     */
    @Override
    public Output<" . ucfirst($table) . "Output> update(final Input<" . ucfirst($table) . "Input> input) {
        try {
            if (isValid(input, PUT)) {
                " . ucfirst($table) . "Dao " . $table . "DAO = new " . ucfirst($table) . "Dao(getHibernate());
                String resource = input.getResource();
                Integer id = Integer.valueOf(resource);
                " . ucfirst($table) . "Input " . $table . "Input = input.getT();
                if (id.equals(" . $table . "Input.getId())) {
                    " . ucfirst($table) . " " . $table . " = null;
                    Boolean updateAllColumns = " . $table . "Input.getUpdateAllColumns();
                    if (updateAllColumns == FALSE) {
                        " . $table . " = " . $table . "DAO.readById(" . $table . "Input.getId());
                    }
                    Input<" . ucfirst($table) . "> inputEntity = inputConversionToInputEntity(input, " . $table . ");
                    " . $table . " = inputEntity.getT();
                    " . $table . " = " . $table . "DAO.update(" . $table . ");
                    setEntityInOutput(" . $table . ");
                } else {
                    output.setStatus(BAD_REQUEST, TRUE);
                    output.setMessage(DATA_DOES_NOT_MATCH);
                }
            }
        } catch (Exception exception) {
            output.setStatus(INTERNAL_ERROR, TRUE);
            output.setMessage(exception.getMessage());
        }
        return output;
    }

    /**
     * Delete.
     *
     * @param  input Input<" . ucfirst($table) . "Input>
     * @return Output<" . ucfirst($table) . "Output>
     * @see    java.lang.Override
     * @see    br.com." . $enterprise . ".common.api.Output
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($table) . "Output
     * @see    br.com." . $enterprise . ".common.api.Input
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.input." . ucfirst($table) . "Input
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . "
     * @see    java.lang.String
     * @see    java.lang.Integer
     * @see    br.com." . $enterprise . "." . str_replace("-", "", $project) . ".api.model.dao." . ucfirst($table) . "Dao
     * @see    java.lang.Exception
     */ 
    @Override
    public Output<" . ucfirst($table) . "Output> delete(final Input<" . ucfirst($table) . "Input> input) {
        try {
            if (isValid(input, DELETE)) {
                " . ucfirst($table) . " " . $table . " = new " . ucfirst($table) . "();
                String resource = input.getResource();
                Integer id = Integer.valueOf(resource);
                " . $table . ".setId(id);
                " . ucfirst($table) . "Dao " . $table . "DAO = new " . ucfirst($table) . "Dao(getHibernate());
                " . $table . " = " . $table . "DAO.delete(" . $table . ");
                setEntityInOutput(" . $table . ");      
            }
        } catch (Exception exception) {
            output.setStatus(INTERNAL_ERROR, TRUE);
            output.setMessage(exception.getMessage());
        }
        return output;
    }
    
}";
			if (!file_exists("../../src/" . $project . "-api/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/api/controller/" . ucfirst($table) . "Controller.java")) {
				$fopen = fopen("../../src/" . $project . "-api/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project). "/api/controller/" . ucfirst($table) . "Controller.java", "w");
				$fwrite = fwrite($fopen, $jgear);
				fclose($fopen);
			}	
		}
		
		public function rest($enterprise, $project, $table, $fields, $fk) {
			$serial = rand(1, 8);
			for ($x = 1; $x < 19; $x++) {
				$serial .= rand(0, 9);
			}
			$serial .= "L";
			$jgear = "package br.com." . $enterprise . "." . str_replace("-", "", $project) . ".api.rest;

import java.io.IOException;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import br.com." . $enterprise . ".common.api.Api;
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . ";
import br.com." . $enterprise . "." . str_replace("-", "", $project). ".io.input." . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Input;
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.output." . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Output;
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".api.controller." . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Controller;

/**
 * " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . " rest. 
 *
 * @author  JGear <https://wtag.com.br/jgear>
 * @param   " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Input " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Input
 * @param   " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Output " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Output
 * @param   " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . " " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "
 * @param   " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Controller " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Service
 * @see     javax.servlet.annotation.WebServlet
 * @see   	br.com." . $enterprise . ".common.api.Api
 * @since   1.0.0
 * @version 1.0.0, " . date("d M Y") . "
 */
@WebServlet(name = \"/" . $this->conversionToHifenAfterUnderline($table) . "/*\", urlPatterns = {\"/" . $this->conversionToHifenAfterUnderline($table) . "/*\"})
public class " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Rest extends Api<" . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Input, " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Output, " . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . ", 
		" . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Controller> {
  
	private static final long serialVersionUID = " . $serial . ";

	/**
     * Post.
     * 
     * @param  request HttpServletRequest
     * @param  response HttpServletResponse
     * @see    java.lang.Override
     * @see    javax.servlet.http.HttpServletRequest
     * @see    javax.servlet.http.HttpServletResponse
     * @see    java.io.IOException
     * @see    javax.servlet.ServletException
     * @throws IOException
     * @throws ServletException
     */
    @Override
    public void doPost(final HttpServletRequest request, final HttpServletResponse response) 
            throws IOException, ServletException {
        action(POST, request, response);
    }

    /**
     * Get.
     * 
     * @param  request HttpServletRequest
     * @param  response HttpServletResponse
     * @see    java.lang.Override
     * @see    javax.servlet.http.HttpServletRequest
     * @see    javax.servlet.http.HttpServletResponse
     * @see    java.io.IOException
     * @see    javax.servlet.ServletException
     * @throws IOException
     * @throws ServletException
     */
    @Override
    public void doGet(final HttpServletRequest request, final HttpServletResponse response) 
            throws IOException, ServletException { 
        action(GET, request, response);
    }

    /**
     * Put.
     * 
     * @param  request HttpServletRequest
     * @param  response HttpServletResponse
     * @see    java.lang.Override
     * @see    javax.servlet.http.HttpServletRequest
     * @see    javax.servlet.http.HttpServletResponse
     * @see    java.io.IOException
     * @see    javax.servlet.ServletException
     * @throws IOException
     * @throws ServletException
     */
    @Override
    public void doPut(final HttpServletRequest request, final HttpServletResponse response) 
            throws IOException, ServletException {
        action(PUT, request, response);
    }

    /**
     * Delete.
     * 
     * @param  request HttpServletRequest
     * @param  response HttpServletResponse
     * @see    java.lang.Override
     * @see    javax.servlet.http.HttpServletRequest
     * @see    javax.servlet.http.HttpServletResponse
     * @see    java.io.IOException
     * @see    javax.servlet.ServletException
     * @throws IOException
     * @throws ServletException
     */
    @Override
    public void doDelete(final HttpServletRequest request, final HttpServletResponse response) 
            throws IOException, ServletException {
        action(DELETE, request, response);
    }

}";
			if (!file_exists("../../src/" . $project . "-api/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/api/rest/" . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Rest.java")) {
				$fopen = fopen("../../src/" . $project . "-api/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/api/rest/" . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Rest.java", "w");
				$fwrite = fwrite($fopen, $jgear);
				fclose($fopen);
			}	
			$stack = "";
			if (!file_exists("../../src/" . $project . "-api/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/api/stack/" . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Stack.txt")) {
				$fopen = fopen("../../src/" . $project . "-api/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/api/stack/" . ucfirst($this->conversionToUppercaseAfterUnderline($table)) . "Stack.txt", "w");
				$fwrite = fwrite($fopen, $stack);
				fclose($fopen);
			}			
		}
				
		public function dao($enterprise, $project, $table, $fields, $fk) {
			$jgear = "package br.com." . $enterprise . "." . str_replace("-", "", $project) . ".api.model.dao;

import java.util.List;

import org.apache.commons.lang3.StringUtils;
import org.apache.commons.lang3.math.NumberUtils;

import com.pugsource.dao.Dao;

import br.com." . $enterprise . ".common.api.DataAccessObject;
import br.com." . $enterprise . ".common.api.Hibernate;
import br.com." . $enterprise . ".common.api.Input;
import br.com." . $enterprise . "." . str_replace("-", "", $project) . ".io.entity." . ucfirst($table) . ";

/**
 * " . ucfirst($table) . " dao.
 * 
 * @author  JGear <https://wtag.com.br/jgear>
 * @see     br.com." . $enterprise . ".common.api.DataAccessObject
 * @see     java.lang.String
 * @see     br.com." . $enterprise . ".jint.io.entity." . ucfirst($table) . "
 * @since   1.0.0
 * @version 1.0.0, " . date("d M Y") . "
 */
public class " . ucfirst($table) . "Dao extends DataAccessObject {

    private static final String TABLE = \"" . ucfirst($table) . "\";
    private Dao<" . ucfirst($table) . "> " . $table . "Dao;
    
    /**
     * Construct method.
     * 
     * @param hibernate Hibernate
     * @see   br.com." . $enterprise . ".common.api.Hibernate
     * @see   br.com." . $enterprise . ".common.api.DataAccessObject
     * @see   br.com." . $enterprise . ".jint.io.entity." . ucfirst($table) . "
     */
    public " . ucfirst($table) . "Dao(final Hibernate hibernate) {
    	" . $table . "Dao = new Dao<" . ucfirst($table) . ">(hibernate.getSession(), " . ucfirst($table) . ".class);
    }

    /**
     * Create.
     *
     * @param  " . $table . " " . ucfirst($table) . "
     * @return " . ucfirst($table) . "
     * @see    br.com." . $enterprise . ".jint.io.entity." . ucfirst($table) . "
     * @see    java.lang.Integer
     */
    public " . ucfirst($table) . " create(final " . ucfirst($table) . " " . $table . ") {
        Integer id = getNextId();
        " . $table . ".setId(id);
        " . $table . "Dao.persist(" . $table . ");
        return " . $table . ";
    }

    /**
     * Read.
     *
     * @param  input Input<" . ucfirst($table) . ">
     * @return List<" . ucfirst($table) . ">
     * @see    java.util.List
     * @see    br.com." . $enterprise . ".jint.io.entity." . ucfirst($table) . "
     * @see    br.com." . $enterprise . ".common.api.Input
     * @see    java.lang.String
     * @see    java.lang.StringBuilder
     * @see    java.lang.Integer
     * @see    org.apache.commons.lang3.math.NumberUtils
     * @see    org.apache.commons.lang3.StringUtils
     */
    public List<" . ucfirst($table) . "> read(final Input<" . ucfirst($table) . "> input) {
        String filterColumn = input.getFilterColumn();
        String filterCondition = input.getFilterCondition();
        String filterValue = input.getFilterValue();
        StringBuilder filter = new StringBuilder();
        if (filterColumn != null && !filterColumn.isEmpty() && filterCondition != null && 
        		!filterCondition.isEmpty() && filterValue != null && !filterValue.isEmpty()) {
            String[] filterColumnList = filterColumn.split(COMMA);
            String[] filterConditionList = filterCondition.split(COMMA);
            String[] filterValueList = filterValue.split(COMMA);
            String[] specialList = null;
            Integer item = NumberUtils.INTEGER_ZERO; 
            Integer leftParentheses = NumberUtils.INTEGER_ZERO;
            Integer rightParentheses = NumberUtils.INTEGER_ZERO;
            for (String filterColumnItem : filterColumnList) {
            	leftParentheses = StringUtils.countMatches(filterColumnItem, LEFT_PARENTHESES);
            	rightParentheses = StringUtils.countMatches(filterColumnItem, RIGHT_PARENTHESES);
            	filterColumnItem = filterColumnItem.replace(LEFT_PARENTHESES, 
        				StringUtils.EMPTY).replace(RIGHT_PARENTHESES, StringUtils.EMPTY);
                if (item > NumberUtils.INTEGER_ZERO) {
                	if (StringUtils.countMatches(filterColumnItem, SEMICOLON) > 
                			NumberUtils.INTEGER_ZERO) {
                		filterColumnItem = filterColumnItem.replace(SEMICOLON, StringUtils.EMPTY);
	                    filter.append(SPACE);
	                    filter.append(OR);
	                    filter.append(SPACE);	
                	} else {
	                    filter.append(SPACE);
	                    filter.append(AND);
	                    filter.append(SPACE);
                	}
                }
                for (Integer x = NumberUtils.INTEGER_ZERO; x < leftParentheses; x++) {
                	filter.append(LEFT_PARENTHESES);
                }
				if (filterConditionList[item].equalsIgnoreCase(LIKE)) {
                	filter.append(LOWER);
                	filter.append(LEFT_PARENTHESES);
                    filter.append(filterColumnItem);
                    filter.append(RIGHT_PARENTHESES);
                } else {
					if (!filterColumnList[item].equalsIgnoreCase(STRING_QUERY)) {
                		filter.append(filterColumnItem);
                	}
                }
                filter.append(SPACE);
                if (filterConditionList[item].equalsIgnoreCase(STRING_EQUALS)) {
					filter.append(EQUALS);
					filter.append(SPACE);
					filter.append(QUOTE_MARK);
					filter.append(filterValueList[item]);
					filter.append(QUOTE_MARK);
                } else if (filterConditionList[item].equalsIgnoreCase(STRING_NOT_EQUALS)) {
					filter.append(NOT_EQUALS);
					filter.append(SPACE);
					filter.append(QUOTE_MARK);
					filter.append(filterValueList[item]);
					filter.append(QUOTE_MARK);  
                } else if (filterConditionList[item].equalsIgnoreCase(STRING_MORE_EQUALS)) {
					filter.append(MORE_EQUALS);
					filter.append(SPACE);
					filter.append(QUOTE_MARK);
					filter.append(filterValueList[item]);
					filter.append(QUOTE_MARK);  
                } else if (filterConditionList[item].equalsIgnoreCase(STRING_MORE_THAN)) {
					filter.append(MORE_THAN);
					filter.append(SPACE);
					filter.append(QUOTE_MARK);
					filter.append(filterValueList[item]);
					filter.append(QUOTE_MARK);  
                } else if (filterConditionList[item].equalsIgnoreCase(STRING_LESS_EQUALS)) {
					filter.append(LESS_EQUALS);
					filter.append(SPACE);
					filter.append(QUOTE_MARK);
					filter.append(filterValueList[item]);
					filter.append(QUOTE_MARK);  
                } else if (filterConditionList[item].equalsIgnoreCase(STRING_LESS_THAN)) {
					filter.append(LESS_THAN);
					filter.append(SPACE);
					filter.append(QUOTE_MARK);
					filter.append(filterValueList[item]);
					filter.append(QUOTE_MARK);  
                } else if (filterConditionList[item].equalsIgnoreCase(LIKE)) {
                	filter.append(LIKE);
                    filter.append(SPACE);
					filter.append(LOWER);
                	filter.append(LEFT_PARENTHESES);
                    filter.append(QUOTE_MARK);
                    filter.append(PERCENT);
                    filter.append(filterValueList[item]);
                    filter.append(PERCENT);
                    filter.append(QUOTE_MARK);
                	filter.append(RIGHT_PARENTHESES);
                } else if (filterConditionList[item].equalsIgnoreCase(BETWEEN)) {
                	specialList = filterValueList[item].split(SEMICOLON);
                	filter.append(BETWEEN);
                    filter.append(SPACE);
                    filter.append(QUOTE_MARK);
                    filter.append(specialList[NumberUtils.INTEGER_ZERO]);
                    filter.append(QUOTE_MARK);
                    filter.append(SPACE);
                    filter.append(AND);
                    filter.append(SPACE);
                    filter.append(QUOTE_MARK);
                    filter.append(specialList[NumberUtils.INTEGER_ONE]);
                    filter.append(QUOTE_MARK);
                } else if (filterConditionList[item].equalsIgnoreCase(IN)) {
                	specialList = filterValueList[item].split(SEMICOLON);
                	filter.append(IN);
                    filter.append(SPACE);
                    filter.append(LEFT_PARENTHESES);
                    for (Integer x = NumberUtils.INTEGER_ZERO; x < specialList.length; x++) {
                    	if (x == NumberUtils.INTEGER_ZERO) {
	                    	filter.append(QUOTE_MARK);
	                        filter.append(specialList[x]);
	                        filter.append(QUOTE_MARK);
                        } else {
                        	filter.append(COMMA);
                        	filter.append(SPACE);
                        	filter.append(QUOTE_MARK);
	                        filter.append(specialList[x]);
	                        filter.append(QUOTE_MARK);
                        }
                    }
                    filter.append(RIGHT_PARENTHESES);
                } else if (filterConditionList[item].equalsIgnoreCase(STRING_NOT_IN)) {
                	specialList = filterValueList[item].split(SEMICOLON);
                	filter.append(NOT_IN);
                    filter.append(SPACE);
                    filter.append(LEFT_PARENTHESES);
                    for (Integer x = NumberUtils.INTEGER_ZERO; x < specialList.length; x++) {
                    	if (x == NumberUtils.INTEGER_ZERO) {
	                    	filter.append(QUOTE_MARK);
	                        filter.append(specialList[x]);
	                        filter.append(QUOTE_MARK);
                        } else {
                        	filter.append(COMMA);
                        	filter.append(SPACE);
                        	filter.append(QUOTE_MARK);
	                        filter.append(specialList[x]);
	                        filter.append(QUOTE_MARK);
                        }
                    }
                    filter.append(RIGHT_PARENTHESES);
				} else if (filterConditionList[item].equalsIgnoreCase(STRING_QUERY)) {
                	if (!filterColumnList[item].equalsIgnoreCase(STRING_QUERY)) {
                		filter.append(EQUALS);
                	}
					filter.append(SPACE);
					filter.append(LEFT_PARENTHESES);
					filter.append(filterValueList[item]);
					filter.append(RIGHT_PARENTHESES);
                }				
                for (Integer x = NumberUtils.INTEGER_ZERO; x < rightParentheses; x++) {
                	filter.append(RIGHT_PARENTHESES);
                }
                item++;
            }
        }
        String orderColumn = input.getOrderColumn();
        if (orderColumn == null) {
            orderColumn = ID;
        }
        String orderValue = input.getOrderValue();
        if (orderValue == null) {
            orderValue = DESC;
        }
        StringBuilder order = new StringBuilder();
        if (orderColumn != null && orderValue != null) {
        	Integer item = NumberUtils.INTEGER_ZERO; 
        	String[] orderColumnList = orderColumn.split(COMMA);
        	String[] orderValueList = orderValue.split(COMMA);
        	for (String orderColumnItem : orderColumnList) {
        		if (item > NumberUtils.INTEGER_ZERO) {
        			order.append(COMMA);
        			order.append(SPACE);
                }
	            order.append(orderColumnItem);
	            order.append(SPACE);
	            order.append(orderValueList[item]); 
	            item++;
            }
        }
        Integer page = input.getPage();
        Integer pageSize = input.getPageSize();
        List<" . ucfirst($table) . "> " . $table . "List = " . $table . "Dao.findAll(toReturn, TABLE, filter.toString(), 
        		order.toString(), StringUtils.EMPTY, page, pageSize, null);
        if (" . $table . "List != null) {
            if (" . $table . "List.size() > NumberUtils.INTEGER_ZERO) {
                return " . $table . "List;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }	

    /**
     * Update.
     *
     * @param  " . $table . " " . ucfirst($table) . "
     * @return " . ucfirst($table) . "
     * @see    br.com." . $enterprise . ".jint.io.entity." . ucfirst($table) . "
     */
    public " . ucfirst($table) . " update(final " . ucfirst($table) . " " . $table . ") {
        " . $table . "Dao.merge(" . $table . ");
        return " . $table . ";
    }

    /**
     * Delete.
     *
     * @param  " . $table . " " . ucfirst($table) . "
     * @return " . ucfirst($table) . "
     * @see    br.com." . $enterprise . ".jint.io.entity." . ucfirst($table) . "
     * @see    java.lang.Integer
     */
    public " . ucfirst($table) . " delete(final " . ucfirst($table) . " " . $table . ") {
        Integer id = " . $table . ".getId();
        " . ucfirst($table) . " entity = readById(id);
        if (entity != null) {
            " . $table . "Dao.remove(entity);
            return entity;
        } else {
            return null;
        }
    }

    /**
     * Read by id.
     *
     * @param  id Integer
     * @return " . ucfirst($table) . "
     * @see    br.com." . $enterprise . ".jint.io.entity." . ucfirst($table) . "
     * @see    java.lang.Integer
     * @see    br.com." . $enterprise . ".common.api.Input
     * @see    java.util.List
     * @see    org.apache.commons.lang3.math.NumberUtils   
     */
    public " . ucfirst($table) . " readById(final Integer id) {
        Input<" . ucfirst($table) . "> input = new Input<" . ucfirst($table) . ">();
        input.setFilterColumn(ID);
        input.setFilterCondition(STRING_EQUALS);
        input.setFilterValue(id.toString());
        List<" . ucfirst($table) . "> " . $table . "List = read(input);
        if (" . $table . "List.size() == NumberUtils.INTEGER_ONE) {
            return " . $table . "List.get(NumberUtils.INTEGER_ZERO);
        } else {
            return null;
        }
    }   

    /**
     * Get next id.
     * 
     * @return Integer
     * @see    java.lang.Integer
     * @see    br.com." . $enterprise . ".common.api.Input
     * @see    br.com." . $enterprise . ".jint.io.entity." . ucfirst($table) . "
     * @see    java.util.List
     * @see    org.apache.commons.lang3.math.NumberUtils
     */
    public Integer getNextId() {
        Input<" . ucfirst($table) . "> input = new Input<" . ucfirst($table) . ">();
        input.setOrderColumn(ID);
        input.setOrderValue(DESC);
		input.setPage(NumberUtils.INTEGER_ONE);
        input.setPageSize(NumberUtils.INTEGER_ONE);
        List<" . ucfirst($table) . "> " . $table . "List = read(input);
        " . ucfirst($table) . " " . $table . ";
        Integer nextId = NumberUtils.INTEGER_ONE;
        if (" . $table . "List != null) {
            if (" . $table . "List.size() > NumberUtils.INTEGER_ZERO) {
            	" . $table . " = " . $table . "List.get(NumberUtils.INTEGER_ZERO);
                Integer lastId = " . $table . ".getId();
                if (lastId != null) {
                    nextId = lastId + NumberUtils.INTEGER_ONE;
                }
            }
        }
        return nextId;
    }

}";
			if (!file_exists("../../src/" . $project . "-api/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/api/model/dao/" . ucfirst($table) . "Dao.java")) {
				$fopen = fopen("../../src/" . $project . "-api/src/main/java/br/com/" . $enterprise . "/" . str_replace("-", "", $project) . "/api/model/dao/" . ucfirst($table) . "Dao.java", "w");
				$fwrite = fwrite($fopen, $jgear);
				fclose($fopen);
			}	
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
		
		/**
		 * Conversion to uppercase after line.
		 *
		 * @param  value String
		 * @return String
		 */
		public function conversionToUppercaseAfterLine($value) {
			$conversion = "";
			$conversionToUppercase = false;
			for ($x = 0; $x < strlen($value); $x++) {
				if ($conversionToUppercase) {
					$conversionToUppercase = false;
					$conversion .= strtoupper($value[$x]);
				} else {
					if ($value[$x] == "-") {
						$conversionToUppercase = true;
					} else {
						$conversion .= $value[$x];
					}
				}
			}
			return $conversion;
		}		
		
		/**
		 * Conversion to hifen after underline.
		 *
		 * @param  value String
		 * @return String
		 */
		public function conversionToHifenAfterUnderline($value) {
			return str_replace("_", "-", $value); 
		}		
		
	}
	
?>