<?php


class Auth {

	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	public function register($username, $password, $email) {
		$password = password_hash($password, PASSWORD_BCRYPT);
		$token = App::str_random(60);

		$this->db->requete("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?", [$username, $password, $email, $token]);
		$user_id = $this->db->lastInsertId();
		$mail_msg = "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost/Gestion-membres/confirm.php?id=$user_id&token=$token";
		mail($email, "Confirmation de votre compte", $mail_msg);
	}




}