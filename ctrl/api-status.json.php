<?php
// INPUT
$key = $_GET['key'];

// USER
$user_id		= dibi::query('select id from users where validation = %s', $key)->fetchSingle();
if(!$user_id) {
	json_fail('This user does not exist.');
}

//
$count_total	= dibi::query('select count(*) from services where user_id = %i', $user_id)->fetchSingle();
$count_offline	= dibi::query('select count(*) from services where response_ms = "-1" and user_id = %i', $user_id)->fetchSingle();
$count_online	= $count_total - $count_offline;

//
$services		= dibi::query('select * from services where user_id = %i', $user_id, 'order by response_ms desc')->fetchAll();
if(!$services) {
	json_fail('No services exist.');
}

//
$outages		= dibi::select('*')->from('outages')->where('user_id = %i', $user_id)->fetchAll();

echo json_encode(array(
	'count'		=> array(
		'total' 	=> $count_total,
		'offline' 	=> $count_offline,
		'online' 	=> $count_online,
		),
	'services' 	=> $services,
	'outages' 	=> $outages,
	)
);
?>