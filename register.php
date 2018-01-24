<?php require 'inc/header.php'; ?>

<?php 

if(!empty($_POST)) {
	$errors = [];

	if(empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
		$errors['username'] = "Votre pseudo n'est pas valide !";
	}
	if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = "Votre email n'est pas valide !";
	}
	if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']) {
		$errors['password'] = "Vous devez rentrer un mot de passe valide";
	}


if(empty($errors)) {
	require_once 'inc/db.php';
	$req = $pdo->prepare("INSERT INTO users SET username = ?, password = ?, email = ?");
	$req->execute([$_POST['username'], sha1($_POST['password']), $_POST['email']])
}

	var_dump($errors);

}

?>


<h1>S'inscrire</h1>


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