<?php
require './lib/Stachl_Ping.php';

stream_context_set_default(
	array(
		'http' => array(
			'method' => 'HEAD',
			'user_agent' => 'Mozilla/5.0 (compatible; RuntimeBot)',
			'timeout' => '10'
			)
	)
);

function getStatus($type, $domain, $port, $match = '') {

	if($type == 'Ping') {
		$ping = new Stachl_Ping($domain, 1);
		$ping->ping();
		$status = $ping->getAvg();

		if($status) {
			return round($status);
		}
		else {
			return 9999;
		}
	}

	if($type == 'HTTP GET' && $match == '') {
		// optimize, haha
		$type = 'HTTP';
	}

	if($port == 0) {
		switch($type) {
			case 'HTTP':
				$port = 80;
				break;
			case 'HTTP GET':
				$port = 80;
				break;
			case 'TCP':
				break;
		}
	}

	$starttime = microtime(true);

	switch($type) {
		case 'HTTP':
			$status = httpHeadCheck( 'http://' . $domain . ':' . $port );
			break;
		case 'HTTP GET':
			$status = httpGetCheck( 'http://' . $domain . ':' . $port, $match);
			break;
		case 'TCP':
			$status = fsockCheck( 'tcp://' . $domain, $port );
			break;
	}

	$stoptime  = microtime(true);

	if (!$status || $status == 0) {
		$status = 9999;
	} else {
		$status = ($stoptime - $starttime) * 1000;
		$status = round($status);
	}

	return $status;
}

function fsockCheck($domain, $port){
	$fp = @fsockopen($domain, $port, $errno, $errstr, 10);

	if(!$fp) {
		return false;
	}

	fclose($fp);

	return true;
}

function httpHeadCheck($url) {
	$headers = @get_headers($url);

	if (!$headers
		&& $headers[0] != 'HTTP/1.1 200 OK'
		&& $headers[0] != 'HTTP/1.0 200 OK') {
    	return false;
	}

	return true;
}

function httpGetCheck($url, $match) {

	$ctxGet = stream_context_create(
		array(
			'http' => array(
				'method' => 'GET',
				'user_agent' => 'Mozilla/5.0 (compatible; RuntimeBot)',
				'timeout' => '10'
				)
		)
	);

	$page = @file_get_contents($url, false, $ctxGet);

	if(!$page) {
		return false;
	}

	if (!preg_match('/'. $match  .'/i', $page)) {
        return false;
    }

    return true;
}

function alertService($service) {
	$alert_email = dibi::query('select email from users where id = %i', $service->user_id)->fetchSingle();
	$name = $service->hostname . ' (' . $service->info . ')';

	require_once './lib/nette.min.php';
	$mail = new Nette\Mail\Message;
	$mail->setFrom('Runtime Cron <root@localhost.localdomain>')
		->addTo($alert_email)
		->setSubject('Your service ' . $name . ' is down!')
		->setBody("Hi, your service ".$name." has stopped responding. Please check it on the Runtime website.")
		->send();
	return true;
}
?>
