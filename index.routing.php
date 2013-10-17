<?php
$pg = $_GET['page'];

if (!$user->isLoggedIn()) {
	if ($pg != 'index'
		&& $pg != 'login'
		&& $pg != 'public'
		&& $pg != 'service-trace'
		&& $pg != 'service-show'
		&& $pg != 'status-show'
		&& $pg != 'api-status.json'
		&& $pg != 'api-history.json') {
		redir('login');
	}
}

// ACTIONS
if ($pg == 'logout')
	$user->logout(TRUE);

// SECURITY
if(!preg_match("/^[a-z-.]+$/", $pg)) {
	redir('index');
}

if($_GET['locale'] != 'api') {
	// Normal router.
	
	require './head.php';

	$f = './ctrl/' . $pg . '.php';

	if (file_exists($f)) {
		require $f;
	}
	else {
		redir('index');
	}

	require './foot.php';
} else {
	// API router.

	header('Content-type: application/json; charset=UTF-8');
	header('Access-Control-Allow-Origin: *');

	require './func.api.php';

	$f = './ctrl/' . $pg . '.php';
	if (file_exists($f))
		require $f;	
}
?>