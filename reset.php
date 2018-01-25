<?php 

if(isset($_GET['id']) && isset($_GET['token'])) {
	require_once 'inc/db.php';
	$req = $pdo->prepare('SELECT * FROM users WHERE id = ? AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
	$req->execute([$_GET['id'], $_GET['token']]);
	$user = $req->fetch();
	session_start();
	if($user) {
		if(!empty($_POST)) {
			if(!empty($_POST['password']) && $_POST['password'] === $_POST['password_confirm']) {
				$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
				$pdo->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_at = NULL')->execute([$password]);
				$_SESSION['flash']['success'] = "Votre mot de passe a bien été modifié";
				$_SESSION['auth'] = $user;
				header('Location: account.php');
				exit();
			}
		}
	}
	else {
		$_SESSION['flash']['danger'] = "Ce token n'est pas valide";
		header('Location: login.php');
		exit();
	}

}
else {
	header('Location: login.php');
	exit();
}


 ?>

<?php require 'inc/header.php'; ?>

<h1>Réinitialiser mon mot de passe</h1>

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

<form action="" method="post">
	<div class="form-group">
		<label for="">Mot de passe</label>
		<input class="form-control" type="password" name="password" placeholder="Changer de mot de passe"/>
	</div>
	<div class="form-group">
		<label for="">Confirmation du mot de passe</label>
		<input class="form-control" type="password" name="password_confirm" placeholder="Confirmation du mot de passe"/>
	</div>
	<button type="submit" class="btn btn-primary">Réinitialiser mon mot de passe</button>
</form>

<?php require 'inc/footer.php'; ?>