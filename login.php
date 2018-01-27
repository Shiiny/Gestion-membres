<?php
	require 'class/App.php';
	App::load();

	$db = App::getDb();
	$auth = App::getAuth();
	$auth->connectFromCookie($db);
	$session = Session::getInstance();

	if($auth->user()) {
		App::redirect('account.php');
	}

	if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
		$user = $auth->login($db, $_POST['username'], $_POST['password'], isset($_POST['remember']));
		if($user) {
			$session->setFlash('success', "Vous êtes connecté");
			App::redirect('account.php');
		}
		else {
			$session->setFlash('danger', "Identifiant ou mot de passe incorrecte");
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
		<label for="">Mot de passe <a href="remember.php">(Mot de passe oublié)</a></label>
		<input type="password" name="password" class="form-control"  />
	</div>

	<div class="form-group">
		<label>
			<input type="checkbox" name="remember" value="1" /> Se souvenir de moi
		</label>
	</div>

	<button type="submit" class="btn btn-primary">Se connecter</button>
</form>

<?php require 'inc/footer.php'; ?>