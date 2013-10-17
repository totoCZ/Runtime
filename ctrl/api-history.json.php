<?php
// INPUT
$key		= $_GET['key'];
$service_id = $_GET['service_id'];

// USER, SECURITY
$usr 		= dibi::query('select id from users where validation = %s', $key)->fetch();
if(!$usr) {
	json_fail('This user does not exist.');
}

// SECURITY
$service 	= dibi::query('select count(*) from services where user_id = %i', $usr->id, 'and id = %i', $service_id)->fetchSingle();
if(!$service) {
	json_fail('This service does not exist.');
}

//
$history = dibi::query('select time, response_ms from history where service_id = %i', $service_id)->fetchAll();

echo json_encode(array(
	'history' => $history,
	)
);
?>