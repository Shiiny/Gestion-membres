<?php


class Validator {
	private $data;
	private $errors;

	public function __construct($data) {
		$this->data = $data;
	}

	private function getField($field) {
		if(!isset($this->data[$field])) {
			return null;
		}
		return $this->data[$field];
	}

	public function isAlphanumeric($field, $errorMsg) {
		if(!preg_match('/^[a-zA-Z0-9_]+$/', $this->getField($field))) {
			$this->errors[$field] = $errorMsg;
		}

	}

	public function isUniq($field, $db, $table, $errorMsg) {
		$record = $db->requete("SELECT id FROM $table WHERE $field = ?", [$this->getField($field)])->fetch();
			if($record) {
				$this->errors[$field] = $errorMsg;
			}
	}

	public function isEmail($field, $errorMsg) {
		if(!filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)) {
			$this->errors[$field] = $errorMsg;
		}
	}


}