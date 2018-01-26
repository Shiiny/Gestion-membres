<?php

class Session {

	static $instance;

	static function getInstance() {
		if(is_null(self::$instance)) {
			self::$instance = new Session();
		}
		return self::$instance;
	}

	public function __construct() {
		session_start();
	}

	public function setFlash($key, $msg) {
		$_SESSION['flash'][$key] = $msg;
	}

	public function hasFlashes() {
		return isset($_SESSION['flash']);
	}

	public function getFlashes() {
		$flash = $_SESSION['flash'];
		unset($_SESSION['flash']);
		return $flash;
	}
}