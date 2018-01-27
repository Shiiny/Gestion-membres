<?php
	require 'class/App.php';
	App::load();

	$db = App::getDb();
	$auth = App::getAuth();
	$session = Session::getInstance();

	if(isset($_GET['id']) && isset($_GET['token'])) {
		$user = $auth->checkResetToken($db, $_GET['id'], $_GET['token']);
		if($user) {
			if(!empty($_POST)) {
				if(!empty($_POST['password']) && $_POST['password'] === $_POST['password_confirm']) {
					$auth->resetPassword($db, $_POST['password'], $user);
					$session->setFlash('success', "Votre mot de passe a bien été modifié");
					App::redirect('account.php');
				}
				else {
					$session->setFlash('danger', "Les mots de passe ne sont pas identique");
				}
			}
		}
		else {
			$session->setFlash('danger', "Ce token n'est pas valide");
			App::redirect('login.php');
		}
	}
	else {
		App::redirect('login.php');
	}
?>

<?php require 'inc/header.php'; ?>

<h1>Réinitialiser mon mot de passe</h1>

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