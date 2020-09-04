<?php

	/**
	 * Boot.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     https://wtag.com.br/jgear
	 * @since   1.0.0
	 * @version 1.0.0, 23 Apr 2020
	 */
	 
	$settings = simplexml_load_file("settings.xml");
	
	/*
	 * Connection.php
	 */
	if (!file_exists("../../src/logic/Connection.php")) {
		$fo = fopen("../../src/logic/Connection.php", "w");
		
		$fw = fwrite($fo, "<?php

	/**
	 * Connection.
	 * 
	* @author   JGear <https://wtag.com.br/jgear>
	 * @see     https://wtag.com.br/jgear
	 * @since   1.0.0
	 * @version 1.0.0, " . date("d M Y") . "
	 */
	 
	namespace src\\logic;

	class Connection {

		private \$server;
		private \$userName;
		private \$password;
		private \$database;
		private \$isOracle = " . $settings->oracle->active . ";
		private \$sid;
		private \$port;
	
		public function __construct() {
			\$this->setServer(\"" . $settings->server . "\");
			\$this->setUsername(\"" . $settings->username . "\");
			\$this->setPassword(\"" . $settings->password . "\");
			\$this->setDatabase(\"" . $settings->database . "\");
			if (\$this->isOracle) {
				\$this->setSid(\"" . $settings->oracle->sid . "\");
				\$this->setPort(\"" . $settings->oracle->port . "\");
			}
		}
		
		/**
		 * @param {String} server
		 */
		private function setServer(\$server) {
			\$this->server = \$server;
		}
		
		/**
		 * @return {String}
		 */
		public function getServer() {
			return \$this->server;
		}
		
		/**
		 * @param {String} username
		 */
		private function setUsername(\$username) {
			\$this->username = \$username;
		}
		
		/**
		 * @return {String}
		 */
		public function getUsername() {
			return \$this->username;
		}
		
		/**
		 * @param {String} password
		 */
		private function setPassword(\$password) {
			\$this->password = \$password;
		}
		
		/**
		 * @return {String}
		 */
		public function getPassword() {
			return \$this->password;
		}
		
		/**
		 * @param {String} database
		 */ 
		private function setDatabase(\$database) {
			\$this->database = \$database;
		}
		
		/**
		 * @return {String}
		 */
		public function getDatabase() {
			return \$this->database;
		}	
		
		/**
		 * @param {String} sid
		 */ 
		private function setSid(\$sid) {
			\$this->sid = \$sid;
		}
		
		/**
		 * @return {String}
		 */
		public function getSid() {
			return \$this->sid;
		}
		
		/**
		 * @param {String} port
		 */ 
		private function setPort(\$port) {
			\$this->port = \$port;
		}
		
		/**
		 * @return {String}
		 */
		public function getPort() {
			return \$this->port;
		}
		
		/**
		 * @return {Boolean}
		 */
		public function isOracle() {
			return \$this->isOracle;
		}			
		
	}

?>");	

		fclose($fo);
	}	
	
	/*
	 * Reveng.bat
	 */
	if (!file_exists("reveng.bat")) {
		$fo = fopen("reveng.bat", "w");
		
		$fw = fwrite($fo, "@ECHO OFF
SET BIN_TARGET=%~dp0/../../lib/jgear/reveng.php
php \"%BIN_TARGET%\" %*");	

		fclose($fo);
	}	
	
	/*
	 * Api Project package.
	 */
	mkdir("../../src/" . $settings->project . "-api", 0700);
	
	/*
	 * .classpath.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.classpath")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.classpath", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<classpath>
	<classpathentry kind=\"src\" output=\"target/classes\" path=\"src/main/java\">
		<attributes>
			<attribute name=\"optional\" value=\"true\"/>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
		</attributes>
	</classpathentry>
	<classpathentry excluding=\"**\" kind=\"src\" output=\"target/classes\" path=\"src/main/resources\">
		<attributes>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
		</attributes>
	</classpathentry>
	<classpathentry kind=\"src\" output=\"target/test-classes\" path=\"src/test/java\">
		<attributes>
			<attribute name=\"optional\" value=\"true\"/>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
			<attribute name=\"test\" value=\"true\"/>
		</attributes>
	</classpathentry>
	<classpathentry excluding=\"**\" kind=\"src\" output=\"target/test-classes\" path=\"src/test/resources\">
		<attributes>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
			<attribute name=\"test\" value=\"true\"/>
		</attributes>
	</classpathentry>
	<classpathentry kind=\"con\" path=\"org.eclipse.jdt.launching.JRE_CONTAINER/org.eclipse.jdt.internal.debug.ui.launcher.StandardVMType/JavaSE-1.8\">
		<attributes>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
		</attributes>
	</classpathentry>
	<classpathentry kind=\"con\" path=\"org.eclipse.m2e.MAVEN2_CLASSPATH_CONTAINER\">
		<attributes>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
			<attribute name=\"org.eclipse.jst.component.dependency\" value=\"/WEB-INF/lib\"/>
		</attributes>
	</classpathentry>
	<classpathentry kind=\"output\" path=\"target/classes\"/>
</classpath>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}		
	
	/*
	 * .project.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.project")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.project", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<projectDescription>
	<name>" . $settings->project . "</name>
	<comment></comment>
	<projects>
	</projects>
	<buildSpec>
		<buildCommand>
			<name>org.eclipse.jdt.core.javabuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.eclipse.wst.common.project.facet.core.builder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.jboss.tools.ws.jaxrs.metamodelBuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.jboss.tools.jst.web.kb.kbbuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.fusesource.ide.project.RiderProjectBuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.jboss.tools.cdi.core.cdibuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.eclipse.wst.validation.validationbuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.hibernate.eclipse.console.hibernateBuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.eclipse.m2e.core.maven2Builder</name>
			<arguments>
			</arguments>
		</buildCommand>
	</buildSpec>
	<natures>
		<nature>org.fusesource.ide.project.RiderProjectNature</nature>
		<nature>org.eclipse.jem.workbench.JavaEMFNature</nature>
		<nature>org.eclipse.wst.common.modulecore.ModuleCoreNature</nature>
		<nature>org.eclipse.jdt.core.javanature</nature>
		<nature>org.eclipse.m2e.core.maven2Nature</nature>
		<nature>org.eclipse.wst.common.project.facet.core.nature</nature>
		<nature>org.eclipse.wst.jsdt.core.jsNature</nature>
		<nature>org.jboss.tools.ws.jaxrs.nature</nature>
		<nature>org.jboss.tools.jst.web.kb.kbnature</nature>
		<nature>org.jboss.tools.jsf.jsfnature</nature>
		<nature>org.jboss.tools.cdi.core.cdinature</nature>
		<nature>org.hibernate.eclipse.console.hibernateNature</nature>
	</natures>
</projectDescription>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}	
	
	/*
	 * pom.xml.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/pom.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/pom.xml", "w");
		$jgear = "<project xmlns=\"http://maven.apache.org/POM/4.0.0\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
		xsi:schemaLocation=\"http://maven.apache.org/POM/4.0.0 http://maven.apache.org/xsd/maven-4.0.0.xsd\">
	<modelVersion>4.0.0</modelVersion>
	<groupId>br.com." . $settings->enterprise . "</groupId>
	<artifactId>" . $settings->project . "-api</artifactId>
	<version>1.0.0</version>
	<packaging>war</packaging>
	<properties>
		<maven.compiler.source>1.8</maven.compiler.source>
		<maven.compiler.target>1.8</maven.compiler.target>
		<project.build.sourceEncoding>UTF-8</project.build.sourceEncoding>
	</properties>
	<build>
		<finalName>" . $settings->project . "-api</finalName>
		<plugins>
			<plugin>
			    <artifactId>maven-compiler-plugin</artifactId>
			    <version>3.8.1</version>
			    <configuration>
					<source>1.8</source>
	    			<target>1.8</target>
			    </configuration>
			</plugin>
		</plugins>
	</build>
	<repositories>
		<repository>
			<id>" . $settings->enterprise . "</id>
			<url>http://docker:8081/artifactory/" . $settings->enterprise . "</url>
		</repository>
	</repositories>
	<dependencies>
		<dependency>
			<groupId>com.pugsource</groupId>
			<artifactId>pugsource-core</artifactId>
			<version>2.9</version>
			<scope>system</scope>
			<systemPath>\${project.basedir}/src/main/webapp/WEB-INF/lib/pug-library-jar-2.9.jar</systemPath>
		</dependency>
		<dependency>
			<groupId>br.com." . $settings->enterprise . "</groupId>
			<artifactId>" . $settings->project . "-io</artifactId>
			<version>1.0.0</version>
		</dependency>		
	</dependencies>
</project>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}	
	
	/*
	 * api/.settings.
	 */
	mkdir("../../src/" . $settings->project . "-api/.settings", 0700);	
	
	/*
	 * .jsdtscope.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.settings/.jsdtscope")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.settings/.jsdtscope", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<classpath>
	<classpathentry kind=\"src\" path=\"src/main/webapp\"/>
	<classpathentry excluding=\"**/bower_components/*|**/node_modules/*|**/*.min.js\" kind=\"src\" path=\"target/m2e-wtp/web-resources\"/>
	<classpathentry kind=\"con\" path=\"org.eclipse.wst.jsdt.launching.JRE_CONTAINER\"/>
	<classpathentry kind=\"con\" path=\"org.eclipse.wst.jsdt.launching.WebProject\">
		<attributes>
			<attribute name=\"hide\" value=\"true\"/>
		</attributes>
	</classpathentry>
	<classpathentry kind=\"con\" path=\"org.eclipse.wst.jsdt.launching.baseBrowserLibrary\"/>
	<classpathentry kind=\"output\" path=\"\"/>
</classpath>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}		
	
	/*
	 * org.eclipse.core.resources.prefs.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.settings/org.eclipse.core.resources.prefs")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.settings/org.eclipse.core.resources.prefs", "w");
		$jgear = "eclipse.preferences.version=1
encoding//src/main/java=UTF-8
encoding//src/main/resources=UTF-8
encoding//src/test/java=UTF-8
encoding//src/test/resources=UTF-8
encoding/<project>=UTF-8";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.jdt.core.prefs.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.settings/org.eclipse.jdt.core.prefs")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.settings/org.eclipse.jdt.core.prefs", "w");
		$jgear = "eclipse.preferences.version=1
org.eclipse.jdt.core.compiler.codegen.inlineJsrBytecode=enabled
org.eclipse.jdt.core.compiler.codegen.methodParameters=do not generate
org.eclipse.jdt.core.compiler.codegen.targetPlatform=1.8
org.eclipse.jdt.core.compiler.codegen.unusedLocal=preserve
org.eclipse.jdt.core.compiler.compliance=1.8
org.eclipse.jdt.core.compiler.debug.lineNumber=generate
org.eclipse.jdt.core.compiler.debug.localVariable=generate
org.eclipse.jdt.core.compiler.debug.sourceFile=generate
org.eclipse.jdt.core.compiler.problem.assertIdentifier=error
org.eclipse.jdt.core.compiler.problem.enablePreviewFeatures=disabled
org.eclipse.jdt.core.compiler.problem.enumIdentifier=error
org.eclipse.jdt.core.compiler.problem.forbiddenReference=warning
org.eclipse.jdt.core.compiler.problem.reportPreviewFeatures=ignore
org.eclipse.jdt.core.compiler.release=disabled
org.eclipse.jdt.core.compiler.source=1.8";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.m2e.core.prefs.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.settings/org.eclipse.m2e.core.prefs")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.settings/org.eclipse.m2e.core.prefs", "w");
		$jgear = "activeProfiles=
eclipse.preferences.version=1
resolveWorkspaceProjects=true
version=1";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.wst.common.component.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.common.component")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.common.component", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><project-modules id=\"moduleCoreId\" project-version=\"1.5.0\">
    <wb-module deploy-name=\"" . $settings->project . "\">
        <wb-resource deploy-path=\"/\" source-path=\"/target/m2e-wtp/web-resources\"/>
        <wb-resource deploy-path=\"/\" source-path=\"/src/main/webapp\" tag=\"defaultRootSource\"/>
        <wb-resource deploy-path=\"/WEB-INF/classes\" source-path=\"/src/main/java\"/>
        <wb-resource deploy-path=\"/WEB-INF/classes\" source-path=\"/src/main/resources\"/>
        <property name=\"context-root\" value=\"" . $settings->project . "\"/>
        <property name=\"java-output-path\" value=\"/" . $settings->project . "/target/classes\"/>
    </wb-module>
</project-modules>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.wst.common.project.facet.core.prefs.xml.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.common.project.facet.core.prefs.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.common.project.facet.core.prefs.xml", "w");
		$jgear = "<root>
  <facet id=\"jst.jaxrs\">
    <node name=\"libprov\">
      <attribute name=\"provider-id\" value=\"jaxrs-no-op-library-provider\"/>
    </node>
  </facet>
  <facet id=\"jst.jsf\">
    <node name=\"libprov\">
      <attribute name=\"provider-id\" value=\"jsf-no-op-library-provider\"/>
    </node>
  </facet>
</root>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.wst.common.project.facet.core.xml.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.common.project.facet.core.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.common.project.facet.core.xml", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<faceted-project>
  <fixed facet=\"wst.jsdt.web\"/>
  <installed facet=\"wst.jsdt.web\" version=\"1.0\"/>
  <installed facet=\"java\" version=\"1.8\"/>
  <installed facet=\"jst.jaxrs\" version=\"2.1\"/>
  <installed facet=\"jst.jsf\" version=\"2.3\"/>
  <installed facet=\"jst.web\" version=\"3.1\"/>
</faceted-project>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.wst.jsdt.ui.superType.container.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.jsdt.ui.superType.container")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.jsdt.ui.superType.container", "w");
		$jgear = "org.eclipse.wst.jsdt.launching.baseBrowserLibrary";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.wst.jsdt.ui.superType.name.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.jsdt.ui.superType.name")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.jsdt.ui.superType.name", "w");
		$jgear = "Window";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.wst.validation.prefs.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.validation.prefs")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.settings/org.eclipse.wst.validation.prefs", "w");
		$jgear = "disabled=06target
eclipse.preferences.version=1";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.hibernate.eclipse.console.prefs.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/.settings/org.hibernate.eclipse.console.prefs")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/.settings/org.hibernate.eclipse.console.prefs", "w");
		$jgear = "default.configuration=
eclipse.preferences.version=1
hibernate3.enabled=true";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}

	/*
	 * api/src.
	 */
	mkdir("../../src/" . $settings->project . "-api/src", 0700);	
	
	/*
	 * api/src/main.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main", 0700);	
	
	/*
	 * api/src/main/java.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java", 0700);	
	
	/*
	 * api/src/main/java/br.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java/br", 0700);	
	
	/*
	 * api/src/main/java/br/com.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java/br/com", 0700);	
	
	/*
	 * api/src/main/java/br/com/@_ENTERPRISE
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java/br/com/" . $settings->enterprise, 
			0700);	
	
	/*
	 * api/src/main/java/br/com/@_ENTERPRISE/@_PROJECT
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project), 0700);	
	
	/*
	 * api/src/main/java/br/com/@_ENTERPRISE/@_PROJECT/api
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project) . "/api", 0700);	

	/*
	 * api/src/main/java/br/com/@_ENTERPRISE/@_PROJECT/api/controller
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project) . "/api/controller", 0700);	

	/*
	 * api/src/main/java/br/com/@_ENTERPRISE/@_PROJECT/api/model
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project) . "/api/model", 0700);	

	/*
	 * api/src/main/java/br/com/@_ENTERPRISE/@_PROJECT/api/model/cfg
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project) . "/api/model/cfg", 0700);	
			
	/*
	 * HibernateListener.java.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/src/main/java/br/com/" . 
			$settings->enterprise . "/" . str_replace("-", "", $settings->project) . 
			"/api/model/cfg/HibernateListener.java")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/src/main/java/br/com/" . 
				$settings->enterprise . "/" . str_replace("-", "", $settings->project) . 
				"/api/model/cfg/HibernateListener.java", "w");
		if ($settings->oracle->active) {
			$databaseJGear = "ORACLE";
		} else {
			$databaseJGear = "MYSQL";
		}		
		$jgear = "package br.com." . $settings->enterprise . "." . str_replace("-", "", $settings->project) . ".api.model.cfg;

import javax.servlet.ServletContextEvent;
import javax.servlet.ServletContextListener;
import javax.servlet.annotation.WebListener;
import javax.servlet.http.HttpServlet;

import org.hibernate.SessionFactory;
import org.hibernate.boot.registry.StandardServiceRegistryBuilder;
import org.hibernate.cfg.Configuration;
import org.hibernate.service.ServiceRegistry;

/**
 * HibernateListener.
 * 
 * @author  JGear <https://wtag.com.br/jgear>
 * @see     javax.servlet.http.HttpServlet
 * @see     javax.servlet.ServletContextListener
 * @since   1.0.0
 * @version 1.0.0, " . date("d M Y") . "
 */
@WebListener
public class HibernateListener extends HttpServlet implements ServletContextListener {

	private static final long serialVersionUID = 266501023174726102L;
    public static final String " . $databaseJGear . "_" . conversionToUnderlineAfterHifen(strtoupper($settings->database)) . "_CFG_XML = \"" . strtolower($databaseJGear) . "." . $settings->database . ".cfg.xml\";
    public static final String " . $databaseJGear . "_" . conversionToUnderlineAfterHifen(strtoupper($settings->database)) . " = \"" . strtolower($databaseJGear) . "-" . $settings->database . "\";	

	/**
	 * Context initialized.
	 * 
	 * @param servletContextEvent ServletContextEvent
	 * @see   javax.servlet.ServletContextEvent
	 * @see   org.hibernate.cfg.Configuration
	 * @see   org.hibernate.service.ServiceRegistry
	 * @see   org.hibernate.boot.registry.ServiceRegistry
	 * @see   org.hibernate.SessionFactory
	 * @see   java.lang.Exception
	 */
	@Override
	public void contextInitialized(ServletContextEvent servletContextEvent) {
		try {
			Configuration configuration = new Configuration();	
			final ServiceRegistry serviceRegistry" . ucfirst(strtolower($databaseJGear)) . conversionToUppercaseAfterHifen(ucfirst($settings->database)) . " = 
			        new StandardServiceRegistryBuilder().configure(" . $databaseJGear . "_" . conversionToUnderlineAfterHifen(strtoupper($settings->database)) . "_CFG_XML).
					applySettings(configuration.getProperties()).build();
			SessionFactory sessionFactory" . ucfirst(strtolower($databaseJGear)) . conversionToUppercaseAfterHifen(ucfirst($settings->database)) . "= configuration.buildSessionFactory(
					serviceRegistry" . ucfirst(strtolower($databaseJGear)) . conversionToUppercaseAfterHifen(ucfirst($settings->database)) . ");		
			servletContextEvent.getServletContext().setAttribute(" . $databaseJGear . "_" . conversionToUnderlineAfterHifen(strtoupper($settings->database)) . ", 
					sessionFactory" . ucfirst(strtolower($databaseJGear)) . conversionToUppercaseAfterHifen(ucfirst($settings->database)) . ");					
		} catch (Exception exception) {
			exception.printStackTrace();
		}
	}
	
	/**
	 * Context destroyed.
	 * 
	 * @param servletContextEvent ServletContextEvent
	 * @see   javax.servlet.ServletContextEvent
	 * @see   org.hibernate.SessionFactory
	 * @see   java.lang.Exception
	 */
	@Override
	public void contextDestroyed(ServletContextEvent servletContextEvent) {
		try {
			SessionFactory sessionFactory" . ucfirst(strtolower($databaseJGear)) . conversionToUppercaseAfterHifen(ucfirst($settings->database)) . " = (SessionFactory) 
			        servletContextEvent.getServletContext().getAttribute(" . $databaseJGear . "_" . conversionToUnderlineAfterHifen(strtoupper($settings->database)) . ");
			if (!sessionFactory" . ucfirst(strtolower($databaseJGear)) . conversionToUppercaseAfterHifen(ucfirst($settings->database)) . ".isClosed()) {
				sessionFactory" . ucfirst(strtolower($databaseJGear)) . conversionToUppercaseAfterHifen(ucfirst($settings->database)) . ".close();
			}
		} catch (Exception exception) {
			exception.printStackTrace();
		}
	}
	
}";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * api/src/main/java/br/com/@_ENTERPRISE/@_PROJECT/api/model/dao
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project) . "/api/model/dao", 0700);	
			
	/*
	 * api/src/main/java/br/com/@_ENTERPRISE/@_PROJECT/api/rest
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project) . "/api/rest", 0700);	

	/*
	 * api/src/main/java/br/com/@_ENTERPRISE/@_PROJECT/api/stack
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project) . "/api/stack", 0700);	

	/*
	 * api/src/main/resources.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/resources", 0700);
	
	/*
	 * datasource.txt.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/src/main/resources/datasource.txt")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/src/main/resources/datasource.txt", "w");
		$jgear = "<datasource jndi-name=\"java:/comp/env/jdbc/mysql/" . $settings->database . "\" pool-name=\"mysql-" . $settings->database . "\">
	<connection-url>jdbc:mysql://" . $settings->server . ":3306/" . $settings->database . "?useUnicode=true&amp;useGmtMillisForDatetimes=true&amp;useJDBCCompliantTimezoneShift=true&amp;useLegacyDatetimeCode=false&amp;useTimezone=false&amp;serverTimezone=UTC</connection-url>
	<driver-class>com.mysql.cj.jdbc.Driver</driver-class>
	<driver>mysql</driver>
	<security>
		<user-name>" . $settings->username . "</user-name>
		<password>" . $settings->password . "</password>
	</security>
	<pool>
		<min-pool-size>5</min-pool-size>
		<max-pool-size>10</max-pool-size>
		<prefill>false</prefill>
		<use-strict-min>false</use-strict-min>
		<flush-strategy>IdleConnections</flush-strategy>
	</pool>  
	<validation>
		<valid-connection-checker class-name=\"org.jboss.jca.adapters.jdbc.extensions.mysql.MySQLValidConnectionChecker\"/>
		<background-validation>true</background-validation>
		<stale-connection-checker class-name=\"org.jboss.jca.adapters.jdbc.extensions.mysql.MySQLValidConnectionChecker\"/>
		<exception-sorter class-name=\"org.jboss.jca.adapters.jdbc.extensions.mysql.MySQLExceptionSorter\"/>
	</validation>
</datasource>
<datasource jndi-name=\"java:/comp/env/jdbc/oracle/" . $settings->database . "\" pool-name=\"oracle-" . $settings->database . "\">
    <connection-url>jdbc:oracle:thin:@" . $settings->server . ":" . $settings->oracle->port . ":" . $settings->oracle->sid . "</connection-url>
    <driver-class>oracle.jdbc.driver.OracleDriver</driver-class>
    <driver>oracle</driver>
    <security>
        <user-name>" . $settings->username . "</user-name>
        <password>" . $settings->password . "</password>
    </security>
	<pool>
		<min-pool-size>5</min-pool-size>
		<max-pool-size>10</max-pool-size>
		<prefill>false</prefill>
		<use-strict-min>false</use-strict-min>
		<flush-strategy>IdleConnections</flush-strategy>
	</pool> 	
    <validation>
        <valid-connection-checker class-name=\"org.jboss.jca.adapters.jdbc.extensions.oracle.OracleValidConnectionChecker\"/>
        <background-validation>true</background-validation>
        <stale-connection-checker class-name=\"org.jboss.jca.adapters.jdbc.extensions.oracle.OracleStaleConnectionChecker\"/>
        <exception-sorter class-name=\"org.jboss.jca.adapters.jdbc.extensions.oracle.OracleExceptionSorter\"/>
    </validation>
</datasource>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * hibernate.properties.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/src/main/resources/hibernate.properties")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/src/main/resources/hibernate.properties", "w");
		$jgear = "######################
### Query Language ###
######################

## define query language constants / function names

#hibernate.query.substitutions yes 'Y', no 'N'


## select the classic query parser

#hibernate.query.factory_class org.hibernate.hql.classic.ClassicQueryTranslatorFactory



#################
### Platforms ###
#################

## JNDI Datasource

#hibernate.connection.datasource java:comp/env/jdbc/usuarios
#hibernate.connection.username db2
#hibernate.connection.password db2


## HypersonicSQL

#hibernate.dialect org.hibernate.dialect.HSQLDialect
#hibernate.connection.driver_class org.hsqldb.jdbcDriver
#hibernate.connection.username sa
#hibernate.connection.password
#hibernate.connection.url jdbc:hsqldb:./build/db/hsqldb/hibernate
#hibernate.connection.url jdbc:hsqldb:hsql://localhost
#hibernate.connection.url jdbc:hsqldb:test

## H2 (www.h2database.com)
#hibernate.dialect org.hibernate.dialect.H2Dialect
#hibernate.connection.driver_class org.h2.Driver
#hibernate.connection.username sa
#hibernate.connection.password
#hibernate.connection.url jdbc:h2:mem:./build/db/h2/hibernate
#hibernate.connection.url jdbc:h2:testdb/h2test
#hibernate.connection.url jdbc:h2:mem:imdb1
#hibernate.connection.url jdbc:h2:tcp://dbserv:8084/sample; 	
#hibernate.connection.url jdbc:h2:ssl://secureserv:8085/sample; 	
#hibernate.connection.url jdbc:h2:ssl://secureserv/testdb;cipher=AES

## MySQL

#hibernate.dialect org.hibernate.dialect.MySQLDialect
#hibernate.dialect org.hibernate.dialect.MySQLInnoDBDialect
#hibernate.dialect org.hibernate.dialect.MySQLMyISAMDialect
#hibernate.dialect org.hibernate.dialect.MySQL5InnoDBDialect
#hibernate.connection.driver_class org.gjt.mm.mysql.Driver
#hibernate.connection.url jdbc:mysql://localhost:3306/banco
#hibernate.connection.username root
#hibernate.connection.password vertrigo


## Oracle

#hibernate.dialect org.hibernate.dialect.OracleDialect
#hibernate.dialect org.hibernate.dialect.Oracle9Dialect
#hibernate.connection.driver_class oracle.jdbc.driver.OracleDriver
#hibernate.connection.username ora
#hibernate.connection.password ora
#hibernate.connection.url jdbc:oracle:thin:@localhost:1521:orcl
#hibernate.connection.url jdbc:oracle:thin:@localhost:1522:XE


## PostgreSQL

#hibernate.dialect org.hibernate.dialect.PostgreSQLDialect
#hibernate.connection.driver_class org.postgresql.Driver
#hibernate.connection.url jdbc:postgresql:template1
#hibernate.connection.username pg
#hibernate.connection.password


## DB2

#hibernate.dialect org.hibernate.dialect.DB2Dialect
#hibernate.connection.driver_class com.ibm.db2.jcc.DB2Driver
#hibernate.connection.driver_class COM.ibm.db2.jdbc.app.DB2Driver
#hibernate.connection.url jdbc:db2://localhost:50000/somename
#hibernate.connection.url jdbc:db2:somename
#hibernate.connection.username db2
#hibernate.connection.password db2

## TimesTen

#hibernate.dialect org.hibernate.dialect.TimesTenDialect
#hibernate.connection.driver_class com.timesten.jdbc.TimesTenDriver
#hibernate.connection.url jdbc:timesten:direct:test
#hibernate.connection.username
#hibernate.connection.password 

## DB2/400

#hibernate.dialect org.hibernate.dialect.DB2400Dialect
#hibernate.connection.username user
#hibernate.connection.password password

## Native driver
#hibernate.connection.driver_class COM.ibm.db2.jdbc.app.DB2Driver
#hibernate.connection.url jdbc:db2://systemname

## Toolbox driver
#hibernate.connection.driver_class com.ibm.as400.access.AS400JDBCDriver
#hibernate.connection.url jdbc:as400://systemname


## Derby (not supported!)

#hibernate.dialect org.hibernate.dialect.DerbyDialect
#hibernate.connection.driver_class org.apache.derby.jdbc.EmbeddedDriver
#hibernate.connection.username
#hibernate.connection.password
#hibernate.connection.url jdbc:derby:build/db/derby/hibernate;create=true


## Sybase

#hibernate.dialect org.hibernate.dialect.SybaseDialect
#hibernate.connection.driver_class com.sybase.jdbc2.jdbc.SybDriver
#hibernate.connection.username sa
#hibernate.connection.password sasasa
#hibernate.connection.url jdbc:sybase:Tds:co3061835-a:5000/tempdb


## Mckoi SQL

#hibernate.dialect org.hibernate.dialect.MckoiDialect
#hibernate.connection.driver_class com.mckoi.JDBCDriver
#hibernate.connection.url jdbc:mckoi:///
#hibernate.connection.url jdbc:mckoi:local://C:/mckoi1.0.3/db.conf
#hibernate.connection.username admin
#hibernate.connection.password nimda


## SAP DB

#hibernate.dialect org.hibernate.dialect.SAPDBDialect
#hibernate.connection.driver_class com.sap.dbtech.jdbc.DriverSapDB
#hibernate.connection.url jdbc:sapdb://localhost/TST
#hibernate.connection.username TEST
#hibernate.connection.password TEST
#hibernate.query.substitutions yes 'Y', no 'N'


## MS SQL Server

#hibernate.dialect org.hibernate.dialect.SQLServerDialect
#hibernate.connection.username sa
#hibernate.connection.password sa

## JSQL Driver
#hibernate.connection.driver_class com.jnetdirect.jsql.JSQLDriver
#hibernate.connection.url jdbc:JSQLConnect://1E1/test

## JTURBO Driver
#hibernate.connection.driver_class com.newatlanta.jturbo.driver.Driver
#hibernate.connection.url jdbc:JTurbo://1E1:1433/test

## WebLogic Driver
#hibernate.connection.driver_class weblogic.jdbc.mssqlserver4.Driver
#hibernate.connection.url jdbc:weblogic:mssqlserver4:1E1:1433

## Microsoft Driver (not recommended!)
#hibernate.connection.driver_class com.microsoft.jdbc.sqlserver.SQLServerDriver
#hibernate.connection.url jdbc:microsoft:sqlserver://1E1;DatabaseName=test;SelectMethod=cursor

## The New Microsoft Driver 
#hibernate.connection.driver_class com.microsoft.sqlserver.jdbc.SQLServerDriver
#hibernate.connection.url jdbc:sqlserver://localhost

## jTDS (since version 0.9)
#hibernate.connection.driver_class net.sourceforge.jtds.jdbc.Driver
#hibernate.connection.url jdbc:jtds:sqlserver://1E1/test

## Interbase

#hibernate.dialect org.hibernate.dialect.InterbaseDialect
#hibernate.connection.username sysdba
#hibernate.connection.password masterkey

## DO NOT specify hibernate.connection.sqlDialect

## InterClient

#hibernate.connection.driver_class interbase.interclient.Driver
#hibernate.connection.url jdbc:interbase://localhost:3060/C:/firebird/test.gdb

## Pure Java

#hibernate.connection.driver_class org.firebirdsql.jdbc.FBDriver
#hibernate.connection.url jdbc:firebirdsql:localhost/3050:/firebird/test.gdb


## Pointbase

#hibernate.dialect org.hibernate.dialect.PointbaseDialect
#hibernate.connection.driver_class com.pointbase.jdbc.jdbcUniversalDriver
#hibernate.connection.url jdbc:pointbase:embedded:sample
#hibernate.connection.username PBPUBLIC
#hibernate.connection.password PBPUBLIC


## Ingres

## older versions (before Ingress 2006)

#hibernate.dialect org.hibernate.dialect.IngresDialect
#hibernate.connection.driver_class ca.edbc.jdbc.EdbcDriver
#hibernate.connection.url jdbc:edbc://localhost:II7/database
#hibernate.connection.username user
#hibernate.connection.password password

## Ingres 2006 or later

#hibernate.dialect org.hibernate.dialect.IngresDialect
#hibernate.connection.driver_class com.ingres.jdbc.IngresDriver
#hibernate.connection.url jdbc:ingres://localhost:II7/database;CURSOR=READONLY;auto=multi
#hibernate.connection.username user
#hibernate.connection.password password

## Mimer SQL

#hibernate.dialect org.hibernate.dialect.MimerSQLDialect
#hibernate.connection.driver_class com.mimer.jdbc.Driver
#hibernate.connection.url jdbc:mimer:multi1
#hibernate.connection.username hibernate
#hibernate.connection.password hibernate


## InterSystems Cache

#hibernate.dialect org.hibernate.dialect.Cache71Dialect
#hibernate.connection.driver_class com.intersys.jdbc.CacheDriver
#hibernate.connection.username _SYSTEM
#hibernate.connection.password SYS
#hibernate.connection.url jdbc:Cache://127.0.0.1:1972/HIBERNATE


#################################
### Hibernate Connection Pool ###
#################################

#hibernate.connection.pool_size 1



###########################
### C3P0 Connection Pool###
###########################

hibernate.c3p0.max_size 100
hibernate.c3p0.min_size 10
hibernate.c3p0.timeout 100
hibernate.c3p0.max_statements 0
hibernate.c3p0.idle_test_period 100
hibernate.c3p0.acquire_increment 1
hibernate.c3p0.validate false



##############################
### Proxool Connection Pool###
##############################

## Properties for external configuration of Proxool

#hibernate.proxool.pool_alias pool1

## Only need one of the following

#hibernate.proxool.existing_pool true
#hibernate.proxool.xml proxool.xml
#hibernate.proxool.properties proxool.properties



#################################
### Plugin ConnectionProvider ###
#################################

## use a custom ConnectionProvider (if not set, Hibernate will choose a built-in ConnectionProvider using hueristics)

#hibernate.connection.provider_class org.hibernate.connection.DriverManagerConnectionProvider
#hibernate.connection.provider_class org.hibernate.connection.DatasourceConnectionProvider
#hibernate.connection.provider_class org.hibernate.connection.C3P0ConnectionProvider
#hibernate.connection.provider_class org.hibernate.connection.ProxoolConnectionProvider



#######################
### Transaction API ###
#######################

## Enable automatic flush during the JTA beforeCompletion() callback
## (This setting is relevant with or without the Transaction API)

#hibernate.transaction.flush_before_completion


## Enable automatic session close at the end of transaction
## (This setting is relevant with or without the Transaction API)

#hibernate.transaction.auto_close_session


## the Transaction API abstracts application code from the underlying JTA or JDBC transactions

#hibernate.transaction.factory_class org.hibernate.transaction.JTATransactionFactory
#hibernate.transaction.factory_class org.hibernate.transaction.JDBCTransactionFactory


## to use JTATransactionFactory, Hibernate must be able to locate the UserTransaction in JNDI
## default is java:comp/UserTransaction
## you do NOT need this setting if you specify hibernate.transaction.manager_lookup_class

#jta.UserTransaction jta/usertransaction
#jta.UserTransaction javax.transaction.UserTransaction
#jta.UserTransaction UserTransaction


## to use the second-level cache with JTA, Hibernate must be able to obtain the JTA TransactionManager

#hibernate.transaction.manager_lookup_class org.hibernate.transaction.JBossTransactionManagerLookup
#hibernate.transaction.manager_lookup_class org.hibernate.transaction.WeblogicTransactionManagerLookup
#hibernate.transaction.manager_lookup_class org.hibernate.transaction.WebSphereTransactionManagerLookup
#hibernate.transaction.manager_lookup_class org.hibernate.transaction.OrionTransactionManagerLookup
#hibernate.transaction.manager_lookup_class org.hibernate.transaction.ResinTransactionManagerLookup



##############################
### Miscellaneous Settings ###
##############################

## print all generated SQL to the console

#hibernate.show_sql true


## format SQL in log and console

#hibernate.format_sql true


## add comments to the generated SQL

#hibernate.use_sql_comments true


## generate statistics

#hibernate.generate_statistics true


## auto schema export

#hibernate.hbm2ddl.auto create-drop
#hibernate.hbm2ddl.auto create
#hibernate.hbm2ddl.auto update
#hibernate.hbm2ddl.auto validate


## specify a default schema and catalog for unqualified tablenames

#hibernate.default_schema test
#hibernate.default_catalog test


## enable ordering of SQL UPDATEs by primary key

#hibernate.order_updates true


## set the maximum depth of the outer join fetch tree

#hibernate.max_fetch_depth 1


## set the default batch size for batch fetching

#hibernate.default_batch_fetch_size 8


## rollback generated identifier values of deleted entities to default values

#hibernate.use_identifer_rollback true


## enable bytecode reflection optimizer (disabled by default)

#hibernate.bytecode.use_reflection_optimizer true



#####################
### JDBC Settings ###
#####################

## specify a JDBC isolation level

#hibernate.connection.isolation 4


## enable JDBC autocommit (not recommended!)

#hibernate.connection.autocommit true


## set the JDBC fetch size

#hibernate.jdbc.fetch_size 25


## set the maximum JDBC 2 batch size (a nonzero value enables batching)

#hibernate.jdbc.batch_size 5
#hibernate.jdbc.batch_size 0
hibernate.jdbc.batch_size 100

## enable batch updates even for versioned data

hibernate.jdbc.batch_versioned_data true


## enable use of JDBC 2 scrollable ResultSets (specifying a Dialect will cause Hibernate to use a sensible default)

#hibernate.jdbc.use_scrollable_resultset true


## use streams when writing binary types to / from JDBC

hibernate.jdbc.use_streams_for_binary true


## use JDBC 3 PreparedStatement.getGeneratedKeys() to get the identifier of an inserted row

#hibernate.jdbc.use_get_generated_keys false


## choose a custom JDBC batcher

# hibernate.jdbc.factory_class


## enable JDBC result set column alias caching 
## (minor performance enhancement for broken JDBC drivers)

# hibernate.jdbc.wrap_result_sets


## choose a custom SQL exception converter

#hibernate.jdbc.sql_exception_converter



##########################
### Second-level Cache ###
##########################

## optimize chache for minimal \"puts\" instead of minimal \"gets\" (good for clustered cache)

#hibernate.cache.use_minimal_puts true


## set a prefix for cache region names

hibernate.cache.region_prefix hibernate.test


## disable the second-level cache

#hibernate.cache.use_second_level_cache false


## enable the query cache

#hibernate.cache.use_query_cache true


## store the second-level cache entries in a more human-friendly format

#hibernate.cache.use_structured_entries true


## choose a cache implementation

#hibernate.cache.provider_class org.hibernate.cache.EhCacheProvider
#hibernate.cache.provider_class org.hibernate.cache.EmptyCacheProvider
#hibernate.cache.provider_class org.hibernate.cache.HashtableCacheProvider
#hibernate.cache.provider_class org.hibernate.cache.TreeCacheProvider
#hibernate.cache.provider_class org.hibernate.cache.OSCacheProvider
#hibernate.cache.provider_class org.hibernate.cache.SwarmCacheProvider


## choose a custom query cache implementation

#hibernate.cache.query_cache_factory



############
### JNDI ###
############

## specify a JNDI name for the SessionFactory

#hibernate.session_factory_name hibernate/session_factory

## Hibernate uses JNDI to bind a name to a SessionFactory and to look up the JTA UserTransaction;
## if hibernate.jndi.* are not specified, Hibernate will use the default InitialContext() which
## is the best approach in an application server

#file system
#hibernate.jndi.class com.sun.jndi.fscontext.RefFSContextFactory
#hibernate.jndi.url file:/

#WebSphere
#hibernate.jndi.class com.ibm.websphere.naming.WsnInitialContextFactory
#hibernate.jndi.url iiop://localhost:900/";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}	
	
	/*
	 * hibernate.reveng.xml.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/src/main/resources/hibernate.reveng.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/src/main/resources/hibernate.reveng.xml", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE hibernate-reverse-engineering PUBLIC \"-//Hibernate/Hibernate Reverse Engineering DTD 3.0//EN\" \"http://hibernate.org/dtd/hibernate-reverse-engineering-3.0.dtd\" >
<hibernate-reverse-engineering>
	<type-mapping>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"Integer\"
			precision=\"5\" scale=\"0\" />
		<sql-type jdbc-type=\"OTHER\"
			hibernate-type=\"java.sql.Timestamp\" />
		<sql-type jdbc-type=\"DATE\" hibernate-type=\"timestamp\"
			not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"DATE\" hibernate-type=\"timestamp\"
			not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"CHAR\" hibernate-type=\"String\" length=\"1\"
			not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"CHAR\" hibernate-type=\"String\" length=\"1\"
			not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"1\" scale=\"1\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"1\" scale=\"2\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"1\" scale=\"3\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"1\" scale=\"4\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"1\" scale=\"5\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"2\" scale=\"1\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"2\" scale=\"2\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"2\" scale=\"3\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"2\" scale=\"4\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"2\" scale=\"5\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"3\" scale=\"1\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"3\" scale=\"2\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"3\" scale=\"3\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"3\" scale=\"4\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"3\" scale=\"5\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"4\" scale=\"1\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"4\" scale=\"2\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"4\" scale=\"3\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"4\" scale=\"4\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"4\" scale=\"5\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"5\" scale=\"1\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"5\" scale=\"2\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"5\" scale=\"3\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"5\" scale=\"4\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"5\" scale=\"5\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"6\" scale=\"1\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"6\" scale=\"2\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"6\" scale=\"3\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"6\" scale=\"4\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"6\" scale=\"5\" not-null=\"false\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"1\" scale=\"1\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"1\" scale=\"2\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"1\" scale=\"3\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"1\" scale=\"4\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"1\" scale=\"5\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"2\" scale=\"1\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"2\" scale=\"2\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"2\" scale=\"3\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"2\" scale=\"4\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"2\" scale=\"5\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"3\" scale=\"1\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"3\" scale=\"2\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"3\" scale=\"3\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"3\" scale=\"4\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"3\" scale=\"5\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"4\" scale=\"1\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"4\" scale=\"2\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"4\" scale=\"3\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"4\" scale=\"4\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"4\" scale=\"5\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"5\" scale=\"1\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"5\" scale=\"2\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"5\" scale=\"3\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"5\" scale=\"4\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"5\" scale=\"5\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"6\" scale=\"1\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"6\" scale=\"2\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"6\" scale=\"3\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"6\" scale=\"4\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"6\" scale=\"5\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"big_decimal\"
			precision=\"10\" scale=\"2\" not-null=\"true\">
		</sql-type>
		<sql-type jdbc-type=\"NUMERIC\" hibernate-type=\"Long\" />
	</type-mapping>
	<table-filter match-name=\"YOUR_TABLE\" />
	<table name=\"YOUR_TABLE\">
		<column name=\"ID\">
			<meta attribute=\"use-in-tostring\">true</meta>
			<meta attribute=\"use-in-equals\">true</meta>
		</column>
	</table>
</hibernate-reverse-engineering>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}	
	
	/*
	 * mysql.database.cfg.xml
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/src/main/resources/mysql." . $settings->database . ".cfg.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/src/main/resources/mysql." . $settings->database . ".cfg.xml", "w");
		$mapping = "";
		$jgear = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<hibernate-configuration>
    <session-factory name=\"db_factory\">
    	<property name=\"hibernate.dialect\">org.hibernate.dialect.MySQLInnoDBDialect</property>
		<property name=\"hibernate.jmodelc.use_streams_for_binary=\">true</property>   
		<property name=\"hibernate.connection.datasource\">java:/comp/env/jdbc/mysql/" . $settings->database . "</property>
		<property name=\"current_session_context_class\">thread</property>
		<property name=\"hibernate.show_sql\">true</property>
		<property name=\"hibernate.format_sql\">true</property>
		<mapping class=\"br.com." . $settings->enterprise . "." . str_replace("-", "", $settings->project) . ".io.entity.@_ENTITY\" />
    </session-factory>
</hibernate-configuration>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * mysql.database.cfg.xml
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/src/main/resources/oracle." . $settings->database . ".cfg.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/src/main/resources/oracle." . $settings->database . ".cfg.xml", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<hibernate-configuration>
    <session-factory name=\"model_factory\">
        <property name=\"hibernate.dialect\">org.hibernate.dialect.Oracle10gDialect</property> 
		<property name=\"hibernate.jmodelc.use_streams_for_binary=\">true</property>   
		<property name=\"hibernate.connection.datasource\">java:/comp/env/jdbc/oracle/" . $settings->database . "</property>
		<property name=\"current_session_context_class\">thread</property>
		<property name=\"hibernate.show_sql\">true</property>
		<property name=\"hibernate.format_sql\">true</property>		
		<mapping class=\"br.com." . $settings->enterprise . "." . str_replace("-", "", $settings->project) . ".io.entity.@_ENTITY\" />
    </session-factory>
</hibernate-configuration>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * READ-ME.txt
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/src/main/resources/READ-ME.txt")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/src/main/resources/READ-ME.txt", "w");
		$jgear = "/**
 * JGear instructions.
 * @see https://wtag.com.br/jgear
 */

#configuration;
-the database/datasource.txt is a wildfly datasource configuration;
 
#api;
-http://@_YOUR_SERVER/@_YOUR_PROJECT/@_YOUR_URN/@_YOUR_DATABASE;

#POST;
-http://localhost/jgear-api/examples/mysql-jgear;
{
	\"example\":\"string\",
	\"date\":\"13/02/2020 08:00:00\"
}

#READ;
-http://localhost/jgear-api/examples/mysql-jgear;
.filterColumn; /* accept comma */
.filterCondition; /* accept comma */
->equals;
->not-equals;
->more-equals;
->more-than;
->less-equals;
->less-than;
->like;
->between;
->in;
->not-in;
.filterValue; /* accept comma in all cases and semicolon when you use between, in or not-in */
.orderColumn; /* accept comma */
.orderValue; /* accept comma */

#PUT;
-http://localhost/jgear-api/examples/mysql-jgear;
.resource;
{
	\"id\":1,
	\"example\":\"other_string\",
	\"date\":\"13/02/2020 08:15:00\"
}

#DELETE;
-http://localhost/jgear-api/examples/mysql-jgear;
.resource;";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * api/src/main/webapp.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/webapp", 0700);	
	
	/*
	 * api/src/main/webapp/META-INF.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/webapp/META-INF", 0700);	
	
	/*
	 * beans.xml.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/src/main/webapp/META-INF/beans.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/src/main/webapp/META-INF/beans.xml", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<beans xmlns=\"http://xmlns.jcp.org/xml/ns/javaee\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
		xsi:schemaLocation=\"http://xmlns.jcp.org/xml/ns/javaee http://xmlns.jcp.org/xml/ns/javaee/beans_2_0.xsd\"
		version=\"2.0\" bean-discovery-mode=\"annotated\">
</beans>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}	
	
	/*
	 * context.xml.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/src/main/webapp/META-INF/context.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/src/main/webapp/META-INF/context.xml", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<Context>
	<Resource name=\"BeanManager\" auth=\"Container\" type=\"javax.enterprise.inject.spi.BeanManager\"
  			factory=\"org.jboss.weld.resources.ManagerObjectFactory\" />
</Context>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}	
	
	/*
	 * api/src/main/webapp/WEB_INF.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/webapp/WEB-INF", 0700);	
	
	/*
	 * faces-config.xml.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/src/main/webapp/WEB-INF/faces-config.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/src/main/webapp/WEB-INF/faces-config.xml", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<faces-config xmlns=\"http://xmlns.jcp.org/xml/ns/javaee\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
   		xsi:schemaLocation=\"http://xmlns.jcp.org/xml/ns/javaee http://xmlns.jcp.org/xml/ns/javaee/web-facesconfig_2_3.xsd\"
    	version=\"2.3\">
</faces-config>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}	
	
	/*
	 * web.xml.
	 */
	if (!file_exists("../../src/" . $settings->project . "-api/src/main/webapp/WEB-INF/web.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-api/src/main/webapp/WEB-INF/web.xml", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<web-app xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns=\"http://xmlns.jcp.org/xml/ns/javaee\" 
		xsi:schemaLocation=\"http://xmlns.jcp.org/xml/ns/javaee http://xmlns.jcp.org/xml/ns/javaee/web-app_3_1.xsd\" version=\"3.1\">
  	<display-name>" . $settings->project . "</display-name>
  	<description>" . $settings->project . "</description>
 	<session-config>
		<session-timeout>30</session-timeout>
  	</session-config>
  	<filter>
   	 	<filter-name>CorsFilter</filter-name>
    	<filter-class>br.com." . $settings->enterprise . ".common.api.CorsFilter</filter-class>
  	</filter>
  	<filter-mapping>
    	<filter-name>CorsFilter</filter-name>
    	<url-pattern>/*</url-pattern>
  	</filter-mapping>
</web-app>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}	
	
	/*
	 * api/src/main/webapp/WEB_INF/lib.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/main/webapp/WEB-INF/lib", 0700);	
	
	/*
	 * pug-library-jar-2.9.jar.
	 */
	copy("../doc/pug-library-jar-2.9.jar", "../../src/" . $settings->project . 
			"-api/src/main/webapp/WEB-INF/lib/pug-library-jar-2.9.jar");
	
	/*
	 * api/src/test.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/test", 0700);	
		
	/*
	 * api/src/test/java.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/test/java", 0700);	
		
	/*
	 * api/src/test/resources.
	 */
	mkdir("../../src/" . $settings->project . "-api/src/test/resources", 0700);
	
	/*
	 * Io Project package.
	 */
	mkdir("../../src/" . $settings->project . "-io", 0700);

	/*
	 * .classpath.
	 */
	if (!file_exists("../../src/" . $settings->project . "-io/.classpath")) {
		$fopen = fopen("../../src/" . $settings->project . "-io/.classpath", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<classpath>
	<classpathentry kind=\"src\" output=\"target/classes\" path=\"src/main/java\">
		<attributes>
			<attribute name=\"optional\" value=\"true\"/>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
		</attributes>
	</classpathentry>
	<classpathentry excluding=\"**\" kind=\"src\" output=\"target/classes\" path=\"src/main/resources\">
		<attributes>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
		</attributes>
	</classpathentry>
	<classpathentry kind=\"src\" output=\"target/test-classes\" path=\"src/test/java\">
		<attributes>
			<attribute name=\"test\" value=\"true\"/>
			<attribute name=\"optional\" value=\"true\"/>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
		</attributes>
	</classpathentry>
	<classpathentry excluding=\"**\" kind=\"src\" output=\"target/test-classes\" path=\"src/test/resources\">
		<attributes>
			<attribute name=\"test\" value=\"true\"/>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
		</attributes>
	</classpathentry>
	<classpathentry kind=\"con\" path=\"org.eclipse.m2e.MAVEN2_CLASSPATH_CONTAINER\">
		<attributes>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
			<attribute name=\"org.eclipse.jst.component.nondependency\" value=\"\"/>
		</attributes>
	</classpathentry>
	<classpathentry kind=\"con\" path=\"org.eclipse.jdt.launching.JRE_CONTAINER/org.eclipse.jdt.internal.debug.ui.launcher.StandardVMType/JavaSE-1.8\">
		<attributes>
			<attribute name=\"maven.pomderived\" value=\"true\"/>
		</attributes>
	</classpathentry>
	<classpathentry kind=\"output\" path=\"target/classes\"/>
</classpath>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}	

	/*
	 * .project.
	 */
	if (!file_exists("../../src/" . $settings->project . "-io/.project")) {
		$fopen = fopen("../../src/" . $settings->project . "-io/.project", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<projectDescription>
	<name>" . $settings->project . "</name>
	<comment></comment>
	<projects>
	</projects>
	<buildSpec>
		<buildCommand>
			<name>org.eclipse.wst.common.project.facet.core.builder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.eclipse.jdt.core.javabuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.hibernate.eclipse.console.hibernateBuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.jboss.tools.jst.web.kb.kbbuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.jboss.tools.cdi.core.cdibuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.eclipse.wst.validation.validationbuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.fusesource.ide.project.RiderProjectBuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.eclipse.m2e.core.maven2Builder</name>
			<arguments>
			</arguments>
		</buildCommand>
	</buildSpec>
	<natures>
		<nature>org.fusesource.ide.project.RiderProjectNature</nature>
		<nature>org.eclipse.jem.workbench.JavaEMFNature</nature>
		<nature>org.eclipse.wst.common.modulecore.ModuleCoreNature</nature>
		<nature>org.eclipse.jdt.core.javanature</nature>
		<nature>org.eclipse.m2e.core.maven2Nature</nature>
		<nature>org.hibernate.eclipse.console.hibernateNature</nature>
		<nature>org.eclipse.wst.common.project.facet.core.nature</nature>
		<nature>org.jboss.tools.jst.web.kb.kbnature</nature>
		<nature>org.jboss.tools.cdi.core.cdinature</nature>
	</natures>
</projectDescription>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}	
	
	/*
	 * pom.xml.
	 */
	if (!file_exists("../../src/" . $settings->project . "-io/pom.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-io/pom.xml", "w");
		$jgear = "<project xmlns=\"http://maven.apache.org/POM/4.0.0\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
		xsi:schemaLocation=\"http://maven.apache.org/POM/4.0.0 http://maven.apache.org/xsd/maven-4.0.0.xsd\">
	<modelVersion>4.0.0</modelVersion>
	<groupId>br.com." . $settings->enterprise . "</groupId>
	<artifactId>" . $settings->project . "-io</artifactId>
	<version>1.0.0</version>
	<packaging>jar</packaging>
	<properties>
		<maven.compiler.source>1.8</maven.compiler.source>
		<maven.compiler.target>1.8</maven.compiler.target>
		<project.build.sourceEncoding>UTF-8</project.build.sourceEncoding>
	</properties>
	<build>
		<plugins>
			<plugin>
				<artifactId>maven-compiler-plugin</artifactId>
				<version>3.8.1</version>
				<configuration>
					<source>1.8</source>
					<target>1.8</target>
				</configuration>
			</plugin>
			<plugin>
				<groupId>org.apache.maven.plugins</groupId>
				<artifactId>maven-source-plugin</artifactId>
				<executions>
					<execution>
						<id>attach-sources</id>
						<goals>
							<goal>jar</goal>
						</goals>
					</execution>
				</executions>
			</plugin>
		</plugins>
	</build>
	<repositories>
		<repository>
			<id>" . $settings->enterprise . "</id>
			<url>http://docker:8081/artifactory/" . $settings->enterprise . "</url>
		</repository>
	</repositories>
	<dependencies>
		<dependency>
			<groupId>br.com." . $settings->enterprise . "</groupId>
			<artifactId>common</artifactId>
			<version>1.3.1</version>
		</dependency>
	</dependencies>
</project>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}		
	
	/*
	 * io/.settings.
	 */
	mkdir("../../src/" . $settings->project . "-io/.settings", 0700);	
	
	/*
	 * org.eclipse.core.resources.prefs.
	 */
	if (!file_exists("../../src/" . $settings->project . "-io/.settings/org.eclipse.core.resources.prefs")) {
		$fopen = fopen("../../src/" . $settings->project . "-io/.settings/org.eclipse.core.resources.prefs", "w");
		$jgear = "eclipse.preferences.version=1
encoding//src/main/java=UTF-8
encoding//src/main/resources=UTF-8
encoding//src/test/java=UTF-8
encoding//src/test/resources=UTF-8
encoding/<project>=UTF-8";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.jdt.core.prefs.
	 */
	if (!file_exists("../../src/" . $settings->project . "-io/.settings/org.eclipse.jdt.core.prefs")) {
		$fopen = fopen("../../src/" . $settings->project . "-io/.settings/org.eclipse.jdt.core.prefs", "w");
		$jgear = "eclipse.preferences.version=1
org.eclipse.jdt.core.compiler.codegen.inlineJsrBytecode=enabled
org.eclipse.jdt.core.compiler.codegen.targetPlatform=1.8
org.eclipse.jdt.core.compiler.compliance=1.8
org.eclipse.jdt.core.compiler.problem.assertIdentifier=error
org.eclipse.jdt.core.compiler.problem.enablePreviewFeatures=disabled
org.eclipse.jdt.core.compiler.problem.enumIdentifier=error
org.eclipse.jdt.core.compiler.problem.forbiddenReference=warning
org.eclipse.jdt.core.compiler.problem.reportPreviewFeatures=ignore
org.eclipse.jdt.core.compiler.release=disabled
org.eclipse.jdt.core.compiler.source=1.8";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.m2e.core.prefs.
	 */
	if (!file_exists("../../src/" . $settings->project . "-io/.settings/org.eclipse.m2e.core.prefs")) {
		$fopen = fopen("../../src/" . $settings->project . "-io/.settings/org.eclipse.m2e.core.prefs", "w");
		$jgear = "activeProfiles=
eclipse.preferences.version=1
resolveWorkspaceProjects=true
version=1";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.wst.common.component.
	 */
	if (!file_exists("../../src/" . $settings->project . "-io/.settings/org.eclipse.wst.common.component")) {
		$fopen = fopen("../../src/" . $settings->project . "-io/.settings/org.eclipse.wst.common.component", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><project-modules id=\"moduleCoreId\" project-version=\"1.5.0\">
        
    <wb-module deploy-name=\"" . $settings->project . "\">
                
        <wb-resource deploy-path=\"/\" source-path=\"/src/main/java\"/>
                
        <wb-resource deploy-path=\"/\" source-path=\"/src/main/resources\"/>
            
    </wb-module>
    
</project-modules>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.eclipse.wst.common.project.facet.core.xml.
	 */
	if (!file_exists("../../src/" . $settings->project . "-io/.settings/org.eclipse.wst.common.project.facet.core.xml")) {
		$fopen = fopen("../../src/" . $settings->project . "-io/.settings/org.eclipse.wst.common.project.facet.core.xml", "w");
		$jgear = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<faceted-project>
  <installed facet=\"jst.utility\" version=\"1.0\"/>
  <installed facet=\"java\" version=\"1.8\"/>
</faceted-project>";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}

	/*
	 * org.eclipse.wst.validation.prefs.
	 */
	if (!file_exists("../../src/" . $settings->project . "-io/.settings/org.eclipse.wst.validation.prefs")) {
		$fopen = fopen("../../src/" . $settings->project . "-io/.settings/org.eclipse.wst.validation.prefs", "w");
		$jgear = "disabled=06target
eclipse.preferences.version=1";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * org.hibernate.eclipse.console.prefs.
	 */
	if (!file_exists("../../src/" . $settings->project . "-io/.settings/org.hibernate.eclipse.console.prefs")) {
		$fopen = fopen("../../src/" . $settings->project . "-io/.settings/org.hibernate.eclipse.console.prefs", "w");
		$jgear = "default.configuration=" . $settings->project . "
eclipse.preferences.version=1
hibernate3.enabled=true";
		$fwrite = fwrite($fopen, $jgear);
		fclose($fopen);
	}
	
	/*
	 * io/src.
	 */
	mkdir("../../src/" . $settings->project . "-io/src", 0700);	
	
	/*
	 * io/src/main.
	 */
	mkdir("../../src/" . $settings->project . "-io/src/main", 0700);	
	
	/*
	 * io/src/main/java.
	 */
	mkdir("../../src/" . $settings->project . "-io/src/main/java", 0700);	
	
	/*
	 * io/src/main/java/br.
	 */
	mkdir("../../src/" . $settings->project . "-io/src/main/java/br", 0700);	
	
	/*
	 * io/src/main/java/br/com.
	 */
	mkdir("../../src/" . $settings->project . "-io/src/main/java/br/com", 0700);	
	
	/*
	 * io/src/main/java/br/com/@_ENTERPRISE
	 */
	mkdir("../../src/" . $settings->project . "-io/src/main/java/br/com/" . $settings->enterprise, 
			0700);	
	
	/*
	 * io/src/main/java/br/com/@_ENTERPRISE/@_PROJECT
	 */
	mkdir("../../src/" . $settings->project . "-io/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project), 0700);	
	
	/*
	 * io/src/main/java/br/com/@_ENTERPRISE/@_PROJECT/io
	 */
	mkdir("../../src/" . $settings->project . "-io/src/main/java/br/com/" . $settings->enterprise . 
			"/" .str_replace("-", "", $settings->project) . "/io", 0700);	

	/*
	 * io/src/main/java/br/com/@_ENTERPRISE/@_PROJECT/io/entity
	 */
	mkdir("../../src/" . $settings->project . "-io/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project) . "/io/entity", 0700);	

	/*
	 * io/src/main/java/br/com/@_ENTERPRISE/@_PROJECT/io/input
	 */
	mkdir("../../src/" . $settings->project . "-io/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project) . "/io/input", 0700);	
			
	/*
	 * io/src/main/java/br/com/@_ENTERPRISE/@_PROJECT/io/output
	 */
	mkdir("../../src/" . $settings->project . "-io/src/main/java/br/com/" . $settings->enterprise . 
			"/" . str_replace("-", "", $settings->project) . "/io/output", 0700);				

	/*
	 * io/src/main/resources.
	 */
	mkdir("../../src/" . $settings->project . "-io/src/main/resources", 0700);
	
	/*
	 * io/src/test.
	 */
	mkdir("../../src/" . $settings->project . "-io/src/test", 0700);	
		
	/*
	 * io/src/test/java.
	 */
	mkdir("../../src/" . $settings->project . "-io/src/test/java", 0700);	
		
	/*
	 * io/src/test/resources.
	 */
	mkdir("../../src/" . $settings->project . "-io/src/test/resources", 0700);
	
	/**
	 * Conversion to uppercase after hifen.
	 *
	 * @param  value String
	 * @return String
	 */
	function conversionToUppercaseAfterHifen($value) {
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
	 * Conversion to underline after hifen.
	 *
	 * @param  value String
	 * @return String
	 */
	function conversionToUnderlineAfterHifen($value) {
		return str_replace("-", "_", $value); 
	}		
	
?>