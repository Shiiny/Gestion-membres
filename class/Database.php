<?php


class Database {

	private $pdo;

	public function __construct($db_login, $db_pass, $db_name, $db_host = 'localhost') {
		$this->pdo = new PDO("mysql:dbname=$db_name;host=$db_host;charset=utf8", $db_login, $db_pass);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	}

	public function requete($statement, $params = false) {
		if($params) {
			$req = $this->pdo->prepare($statement);
			$req->execute($params);
		}
		else {
			$req = $this->pdo->query($statement);
		}
		return $req;
	}

	public function lastInsertId(){
		return $this->pdo->lastInsertId();
	}
}