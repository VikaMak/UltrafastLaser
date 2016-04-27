<?php

class DataBase {
	
	/**
	 * Переменные, определяющие параметры соединения с БД 
	 * @var string
	 */
	protected static $db_host = 'localhost';
	protected static $db_user = 'root';
	protected static $db_pass = '';
	protected static $db_name = 'main1';
	
	public $mysqli;
	
	public function __construct() {
		
		/**
		 * Соединение с БД
		 * @var object
		 */
		$this->mysqli = new mysqli(self::$db_host, self::$db_user, self::$db_pass, self::$db_name);
		
		if ($this->mysqli->connect_errno) {
			printf("Соединение не удалось:%s<br>", $this->mysqli->connect_error);
			die();
		}
		
		/**
		 * Установка кодировки utf-8
		 */
		$this->mysqli->set_charset("utf8");
		
	}
		
}


