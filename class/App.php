<?php

class App {

	static $db;

	public static function load() {
		session_start();
		require 'class/Autoloader.php';
		Autoloader::register();
	}

	public static function getDB() {
		if(is_null(self::$db)) {
			self::$db =  new Database('root', '', 'poo');
		}
		return self::$db;
	}


}