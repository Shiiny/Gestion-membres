<?php
	require 'class/App.php';
	App::load();
	App::getAuth()->logout();

	Session::getInstance()->setFlash('success', "Vous êtes déconnecté");
	App::redirect('login.php');