<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
App::uses('CakeTime', 'Utility');
App::uses('CakeNumber', 'Utility');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $helpers = array('Html','Form');
	public $components = array(
		'Session',
		'Paginator',
		'Auth' => array(
			'loginRedirect' => array('controller' => 'pages', 'action' => 'home'),
			'logoutRedirect' => array('controller' => 'users', 'action' => 'login')
		)
	);

	protected function checkLoggedUser(){
		if($this->Auth->loggedIn() == '1' && $this->Auth->user('role') != 'admin'){
			$this->Session->destroy();
			return $this->redirect($this->Auth->logout());
		}
	}

	public function beforeFilter() {
		$this->checkLoggedUser();

		// Configuration paths
		Configure::write('base_url', 'http://desarrollo.neozink.com/latiendadelabodega/');
		Configure::write('lib', 'lib');
		Configure::write('timthumb', 'thumbs'.DS.'timthumb.php?src=');
		Configure::write('resources_path', DS . 'resources');
		Configure::write('generic_image', 'generic.png');
		Configure::write('datetime_format', '%d/%m/%Y %H:%M:%S');

		// Configuration numbers
		CakeNumber::addFormat(
			"EUR", array(
				'wholeSymbol'      => '€',
				'wholePosition'    => 'after',
				'fractionSymbol'   => false,
				'fractionPosition' => 'after',
				'zero'             => 0,
				'places'           => 2,
				'thousands'        => '.',
				'decimals'         => ',',
				'negative'         => '()',
				'escape'           => true,
				'fractionExponent' => 2
			)
		);
		CakeNumber::defaultCurrency("EUR");
	}

	
	/**
	 * Dumps the MySQL database that this controller's model is attached to.
	 * This action will serve the sql file as a download so that the user can save the backup to their local computer.
	 *
	 * @param string $tables Comma separated list of tables you want to download, or '*' if you want to download them all.
	 */
	function backup_database($tables = '*') {

	    $return = '';

	    $modelName = $this->modelClass;

	    $dataSource = $this->{$modelName}->getDataSource();
	    $databaseName = $dataSource->getSchemaName();


	    // Do a short header
	    $return .= '-- Database: `' . $databaseName . '`' . "\n";
	    $return .= '-- Generation time: ' . date('D jS M Y H:i:s') . "\n\n\n";


	    if ($tables == '*') {
	        $tables = array();
	        $result = $this->{$modelName}->query('SHOW TABLES');
	        foreach($result as $resultKey => $resultValue){
	            $tables[] = current($resultValue['TABLE_NAMES']);
	        }
	    } else {
	        $tables = is_array($tables) ? $tables : explode(',', $tables);
	    }

	    // Run through all the tables
	    foreach ($tables as $table) {
	        $tableData = $this->{$modelName}->query('SELECT * FROM ' . $table);

	        $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
	        $createTableResult = $this->{$modelName}->query('SHOW CREATE TABLE ' . $table);
	        $createTableEntry = current(current($createTableResult));
	        $return .= "\n\n" . $createTableEntry['Create Table'] . ";\n\n";

	        // Output the table data
	        foreach($tableData as $tableDataIndex => $tableDataDetails) {

	            $return .= 'INSERT INTO ' . $table . ' VALUES(';

	            foreach($tableDataDetails[$table] as $dataKey => $dataValue) {

	                if(is_null($dataValue)){
	                    $escapedDataValue = 'NULL';
	                }
	                else {
	                    // Convert the encoding
	                    $escapedDataValue = mb_convert_encoding( $dataValue, "UTF-8", "ISO-8859-1" );

	                    // Escape any apostrophes using the datasource of the model.
	                    $escapedDataValue = $this->{$modelName}->getDataSource()->value($escapedDataValue);
	                }

	                $tableDataDetails[$table][$dataKey] = $escapedDataValue;
	            }
	            $return .= implode(',', $tableDataDetails[$table]);

	            $return .= ");\n";
	        }

	        $return .= "\n\n\n";
	    }

	    // Set the default file name
	    $fileName = $databaseName . '-backup-' . date('Y-m-d_H-i-s') . '.sql';

	    // Serve the file as a download
	    $this->autoRender = false;
	    $this->response->type('Content-Type: text/x-sql');
	    $this->response->download($fileName);
	    $this->response->body($return);
	}



	public function change_visible($id = null){
		//recover the entity
		$this->{$this->modelClass}->id = $id;
		$entity = $this->{$this->modelClass}->read();

		//If trying to edit an entity that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		 $status = false;

        //control visibility
        if($entity[$this->modelClass]['visible']){
            $status = $this->{$this->modelClass}->saveField("visible", "0");
        }else{
            $status = $this->{$this->modelClass}->saveField("visible", "1");
        }

        //if changed
        if(!$status){
            $this->set("status", "ERROR");
        }else{
            $this->set("status", "OK");
        }

        //render special view for ajax
        $this->render('/Pages/change_visible', 'ajax');
	}
}
