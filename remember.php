<?php 
	session_start();
	if(!empty($_POST) && !empty($_POST['email'])) {
		require_once 'inc/db.php';
		require_once 'inc/function.php';
		$req = $pdo->prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
		$req->execute([$_POST['email']]);
		$user = $req->fetch();
		if($user) {
			$reset_token = str_random(60);
			$req = $pdo->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?');
			$req->execute([$reset_token, $user->id]);
			$_SESSION['flash']['success'] = "Un email vous a été envoyé pour réinitialiser votre mot de passe";
			$mail_msg = "Cliquez sur ce lien\n\nhttp://localhost/Gestion-membres/reset.php?id={$user->id}&token=$reset_token\n\nPour réinitailiser votre mot de passe";
			$header = 'From: "Web-Shiny"<contact@web-shiny.fr>'."\r\n\r\n";
			mail($_POST['email'], "Demande de réinitialisation", $mail_msg, $header);

			header('Location: login.php');
			exit();
			// http://localhost/Gestion-membres/reset.php?id=6&token=N9tylsipk5dqB5T2CUouz5jTmUsQgSRDvco4T12lLH76klZJu7i9pr0atKEK
		}
		else {
			$_SESSION['flash']['danger'] = "Aucun compte ne correspond à cette adresse";
		}
		var_dump($user);
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