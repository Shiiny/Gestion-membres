1<?php 

	require 'class/App.php';

	App::load();

	$db = App::getDb();


	if(!empty($_POST)) {
		$errors = [];
		
		$validator = new Validator($_POST);
		$validator->isAlphanumeric('username', '/^[a-zA-Z0-9_]+$/', "Votre pseudo n'est pas valide !");
		if($validator->isValid()) {
			$validator->isUniq('username', $db, 'users', "Ce pseudo est déjà utilisé");
		}
		$validator->isEmail('email', "Votre email n'est pas valide !");
		if($validator->isValid()) {
			$validator->isUniq('email', $db, 'users', "Cet adresse e-mail est déjà utilisé");
		}
		$validator->isConfirmed('password', "Vous devez rentrer un mot de passe valide");

		if($validator->isValid()) {
			App::getAuth()->register($db, $_POST['username'], $_POST['password'], $_POST['email']);
			Session::getInstance()->setFlash('success', "Un email de confirmation vous a été envoyé.");
			App::redirect('login.php');
		}
		else {
			$errors = $validator->getErrors();
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