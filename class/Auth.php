<?php


class Auth {

	private $session;
	private $options = [
		'restriction_msg' => "Vous n'avez pas le droit d'accéder à cette page"
	];

	public function __construct($session, $options = []) {
		$this->options = array_merge($this->options, $options);
		$this->session = $session;
	}

	public function register($db, $username, $password, $email) {
		$password = password_hash($password, PASSWORD_BCRYPT);
		$token = App::str_random(60);

		$db->requete("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?", [$username, $password, $email, $token]);
		$user_id = $db->lastInsertId();
		$mail_msg = "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost/Gestion-membres/confirm.php?id=$user_id&token=$token";
		mail($email, "Confirmation de votre compte", $mail_msg);
	}

	public function confirm($db, $user_id, $token) {
		$user = $db->requete('SELECT * FROM users WHERE id = ?', [$user_id])->fetch();

		if($user && $user->confirmation_token == $token) {
			$db->requete('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?', [$user_id]);
			$this->session->write('auth', $user);
			return true;
		}
		return false;
	}

	public function restrict() {
	    if(!$this->session->read('auth')) {
			$this->session->setFlash('danger', $this->options['restriction_msg']);
			App::redirect('login.php');
		}
	}

	public function user() {
		if(!$this->session->read('auth')) {
			return false;
		}
		return $this->session->read('auth');
	}

	public function connect($user) {
		$this->session->write('auth', $user);
	}

	public function connectFromCookie($db) {
		if(isset($_COOKIE['remember']) && !$this->user()) {
		$remember_token = $_COOKIE['remember'];
		$parts = explode('==', $remember_token);
		$user_id = $parts[0];

		$user = $db->requete('SELECT * FROM users WHERE id = ?', [$user_id])->fetch();

			if($user) {
				$expected = $user->id . '==' . $user->remember . sha1($user->id . 'test');
				if($expected == $remember_token) {
					$this->connect($user);
					setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
				}
				else {
					setcookie('remember', NULL, -1);
				}
			}
			else {
				setcookie('remember', NULL, -1);
			}
		}
	}

	public function login($username, $password, $remember = false) {
		
	}
}