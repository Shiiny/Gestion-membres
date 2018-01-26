<?php

class App {

	static $db;

	static function load() {
		require 'class/Autoloader.php';
		Autoloader::register();
	}

	static function getDB() {
		if(is_null(self::$db)) {
			self::$db =  new Database('root', '', 'poo');
		}
		return self::$db;
	}

	static function str_random($lenght) {
		$alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
		return substr(str_shuffle(str_repeat($alphabet, $lenght)), 0, $lenght);
	}

	static function redirect($page) {
		header("Location: $page");
	}

}