<?php
	require 'class/App.php';
	App::load();
	$db = App::getDb();
	$auth = App::getAuth();
	$session = Session::getInstance();


	if(!empty($_POST) && !empty($_POST['email'])) {
		if($auth->forget($db, $_POST['email'])) {
			$session->setFlash('success', "Un email vous a été envoyé pour réinitialiser votre mot de passe");
			App::redirect('login.php');
		}
		else {
			$session->setFlash('danger', "Aucun compte ne correspond à cette adresse");
		}
	}
		




?>

<?php require 'inc/header.php'; ?>

<h1>Mot de passe oublié</h1>

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
		<label for="">Email</label>
		<input type="email" name="email" class="form-control"  />
	</div>
	<button type="submit" class="btn btn-primary">Envoyer</button>
</form>

<?php require 'inc/footer.php'; ?>