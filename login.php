<?php 
	session_start();
	if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
		require_once 'inc/db.php';
		$req = $pdo->prepare('SELECT * FROM users WHERE username = :username OR email = :username');
		$req->execute(['username' => $_POST['username']]);
		$user = $req->fetch();
		var_dump($user);
		if(password_verify($_POST['password'], $user->password)) {
			$_SESSION['auth'] = $user;
			header('Location: account.php');
			exit();
		}
		else {
			$_SESSION['flash']['danger'] = "Identifiant ou mot de passe incorrecte";
		}
	}




 ?>

<?php require 'inc/header.php'; ?>

<h1>Se connecter</h1>

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
		<label for="">Pseudo ou email</label>
		<input type="text" name="username" class="form-control"  />
	</div>

	<div class="form-group">
		<label for="">Mot de passe</label>
		<input type="password" name="password" class="form-control"  />
	</div>

	<button type="submit" class="btn btn-primary">Se connecter</button>
</form>

<?php var_dump($_POST, $_SESSION['auth']); ?>

<?php require 'inc/footer.php'; ?>