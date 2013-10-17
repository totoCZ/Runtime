<?php
if(!isset($dibi)){
	require './db.php';
}
require './func.cron.php';

// main schedule
$date = date('c', strtotime('5 minutes ago'));
$to_check = dibi::query('select * from services where last_checked <= %t', $date)->fetchAll();

foreach($to_check as $service) {
	usleep(200000);

	$status1 = getStatus($service->type, $service->hostname, $service->port, $service->text_match);
	// twice for safety
	if($status1 === 9999) {
		usleep(200000);

		$status2 = getStatus($service->type, $service->hostname, $service->port, $service->text_match);

		if ($status1 === 9999 && $status2 !== 9999) {
			// #1 fail, #2 ok
			$status = $status2;
		}  else {
			// #1 fail, #2 fail
			$status = 9999;
		}
	} else {
		// #1 ok
		$status = $status1;
	}


	$history = array(
		'service_id'	=> $service->id,
		'time'			=> new Datetime,
		'response_ms'	=> $status
	);

	dibi::query('insert into history', $history);

	if($status === 9999 && $service->response_ms !== 9999) {
		// online -> OFFLINE

		alertService($service);

		$incident = array(
			'user_id'		=> $service->user_id,
			'service_id' 	=> $service->id,
			'name' 			=> $service->hostname. ' ('.$service->type .':'. $service->port . ')',
			'outage_start' 	=> new DateTime,
			'outage_end' 	=> '0000-00-00 00:00:00'
		);

		dibi::insert('outages', $incident)->execute();
	}

	if($status === 9999) {
		// OFFLINE -> OFFLINE
	}

	if($status !== 9999 && $service->response_ms === 9999) {
		// OFFLINE -> online

		$incident = array(
			'outage_end' => new DateTime
		);

		dibi::update('outages', $incident)->where('service_id = %i', $service->id, 'and outage_end="00-00-0000 00:00:00"')->execute();
	}

	//if($status >= $service->alert_threshold) {
		// Alert threshold reached
	//}

	$arr = array();
	$arr['last_checked']	= new DateTime;
	$arr['response_ms']		= $status;

	// ignore 100% servers
	if($service->availability != 100 || $status === 9999) {
		$all = dibi::select('count(*)')->from('history')->where('service_id = %i', $service->id);
		$off = dibi::select('count(*)')->from('history')->where('service_id = %i', $service->id)->where('response_ms = "9999"');

		$all = $all->fetchSingle();
		$offline = $off->fetchSingle();
		$online = $all - $offline;	

		$arr['availability'] = round((($online / $offline) / $all * 100), 2);
	}

	dibi::query('update services set', $arr, 'where id = %i', $service->id);	
}

// purge old data
$date = date('c', strtotime('30 days ago'));
dibi::query('delete from history where time <= %t', $date);
?>