<?php
	require 'class/App.php';
	App::load();

	$db = App::getDb();

	if(App::getAuth()->confirm($db, $_GET['id'], $_GET['token'])) {
		Session::getInstance()->setFlash('success', "Votre compte a bien été activé");
		App::redirect('account.php');
	}
	else {
		Session::getInstance()->setFlash('danger', "Ce token n'est plus valide");
		App::redirect('login.php');
	}