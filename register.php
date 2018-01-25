<?php 

require_once 'class/App.php';

App::load();


if(!empty($_POST)) {
	$errors = [];
	$db = App::getDb();
	$validator = new Validator($_POST);
	$validator->isAlphanumeric('username', "Votre pseudo n'est pas valide !");
	$validator->isUniq('username', $db, 'users', "Ce pseudo est déjà utilisé");
	$validator->isEmail('email', "Votre email n'est pas valide !");

	var_dump($validator);

	die();

	/*if(empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
		$errors['username'] = "Votre pseudo n'est pas valide !";
	}
	else {
		$user = $db->requete('SELECT id FROM users WHERE username = ?', [$_POST['username']])->fetch();
		if($user) {
			$errors['username'] = "Ce pseudo est déjà utilisé";
		}
	}
	if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = "Votre email n'est pas valide !";
	}*/
	else {
		$user = $db->requete('SELECT id FROM users WHERE email = ?', [$_POST['email']])->fetch();
		if($user) {
			$errors['email'] = "Cet adresse e-mail est déjà utilisé";
		}
	}
	if(empty($_POST['password']) || $_POST['password'] !== $_POST['password_confirm']) {
		$errors['password'] = "Vous devez rentrer un mot de passe valide";
	}


	if(empty($errors)) {		
		$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
		$token = str_random(60);

		$db->requete("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?", [[$_POST['username'], $password, $_POST['email'], $token]]);
		$user_id = $db->lastInsertId();
		$mail_msg = "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost/Gestion-membres/confirm.php?id=$user_id&token=$token";
		$header = 'From: "Web-Shiny"<contact@web-shiny.fr>'."\r\n\r\n";
		mail($_POST['email'], "Confirmation de votre compte", $mail_msg, $header);
		$_SESSION['flash']['success'] = "Un email de confirmation vous a été envoyé.";
		header('Location: login.php');
		exit();
		//lien : http://localhost/Gestion-membres/confirm.php?id=6&token=UJdnQ0nlv5inzGEJz9e49poH8jnTbRL5eRZBWEKXJ9s7XAFW7BcOYNaAccm9
	}
}

?>

<?php require 'inc/header.php'; ?>

<h1>S'inscrire</h1>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">
	<p>Vous n'avez pas rempli le formulaire correctement</p>
	<ul>
	<?php foreach ($errors as $error): ?>
		<li><?= $error; ?></li>
	<?php endforeach; ?>
	</ul>

</div>
<?php endif; ?>

<form action="" method="POST">
	<div class="form-group">
		<label for="">Pseudo</label>
		<input type="text" name="username" class="form-control"  />
	</div>

	<div class="form-group">
		<label for="">Email</label>
		<input type="text" name="email" class="form-control"  />
	</div>

	<div class="form-group">
		<label for="">Mot de passe</label>
		<input type="password" name="password" class="form-control"  />
	</div>

	<div class="form-group">
		<label for="">Confirmez votre mot de passe</label>
		<input type="password" name="password_confirm" class="form-control"  />
	</div>

	<button type="submit" class="btn btn-primary">M'inscrire</button>
</form>


<?php require 'inc/footer.php'; ?>