<?php require_once 'inc/function.php'; ?>

<?php
session_start();

if(!empty($_POST)) {
	$errors = [];
	require_once 'inc/db.php';

	if(empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
		$errors['username'] = "Votre pseudo n'est pas valide !";
	}
	else {
		$req = $pdo->prepare('SELECT id FROM users WHERE username = ?');
		$req->execute([$_POST['username']]);
		$user = $req->fetch();
		if($user) {
			$errors['username'] = "Ce pseudo est déjà utilisé";
		}
	}
	if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = "Votre email n'est pas valide !";
	}
	else {
		$req = $pdo->prepare('SELECT id FROM users WHERE email = ?');
		$req->execute([$_POST['email']]);
		$user = $req->fetch();
		if($user) {
			$errors['email'] = "Cet adresse e-mail est déjà utilisé";
		}
	}
	if(empty($_POST['password']) || $_POST['password'] !== $_POST['password_confirm']) {
		$errors['password'] = "Vous devez rentrer un mot de passe valide";
	}


	if(empty($errors)) {
		$req = $pdo->prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?");
		$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
		$token = str_random(60);
		$req->execute([$_POST['username'], $password, $_POST['email'], $token]);
		$user_id = $pdo->lastInsertId();
		$mail_msg = "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost/Gestion-membres/confirm.php?id=$user_id&token=$token";
		$header = 'From: "Web-Shiny"<contact@web-shiny.fr>'."\r\n\r\n";
		mail($_POST['email'], "Confirmation de votre compte", $mail_msg, $header);
		$_SESSION['flash']['success'] = "Un email de confirmation vous a été envoyé.";
		header('Location: login.php');
		exit();
		//lien : http://localhost/Gestion-membres/confirm.php?id=3&token=SC3dD70MHLJRaT1jHiFTR5D99xp1g4WBYg2zuAZHYWgfjWHEyfxrGqcCl66U
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

<?php var_dump($_POST); ?>

<?php require 'inc/footer.php'; ?>