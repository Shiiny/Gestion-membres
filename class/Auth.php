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

	public function hashPassword($password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}

	public function register($db, $username, $password, $email) {
		$password = $this->hashPassword($password);
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


	public function login($db, $username, $password, $remember = false) {
		$user = $db->requete('SELECT * FROM users LEFT JOIN roles ON users.role_id = roles.id WHERE username = :username OR email = :username AND confirmed_at IS NOT NULL', ['username' => $username])->fetch();
		if(password_verify($password, $user->password)) {
			$this->connect($user);
			if($remember) {
				$this->remember($db, $user->id);
			}
			return $user;
		}
		return false;
	}

	public function remember($db, $user_id) {
		$remember_token = App::str_random(250);
		$db->requete('UPDATE users SET remember = ? WHERE id = ?', [$remember_token, $user_id]);
		setcookie('remember', $user_id . '==' .$remember_token. sha1($user_id. 'test'), time() + 60 * 60 * 24 * 7);
	}

	public function logout() {
		setcookie('remember', NULL, -1);
		$this->session->destroy('auth');
	}

	public function forget($db, $email) {
		$user = $db->requete('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL', [$email])->fetch();
		if($user) {
			$reset_token = App::str_random(60);
			$db->requete('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?',[$reset_token, $user->id]);
			$mail_msg = "Cliquez sur ce lien\n\nhttp://localhost/Gestion-membres/reset.php?id={$user->id}&token=$reset_token\n\nPour réinitailiser votre mot de passe";
			mail($email, "Demande de réinitialisation", $mail_msg);
			return $user;
		}
		return false;
	}

	public function checkResetToken($db, $user_id, $token) {
		return $db->requete('SELECT * FROM users WHERE id = ? AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)', [$user_id, $token])->fetch();
	}

	public function resetPassword($db, $password, $user) {
		$password = $this->hashPassword($password);
		$db->requete('UPDATE users SET password = ?, reset_token = NULL, reset_at = NULL WHERE id = ?', [$password, $user->id]);
		$this->connect($user);
	}
}


