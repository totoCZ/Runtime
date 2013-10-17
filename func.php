<?php
// shit code

function serviceTable($id, $private = True) {
	if($private) { // logged in user
		$limit = array('user_id = %i', $id);
	} elseif($id == 0) { // public, all
		$limit = array('public = %b', true);
	} else { // public, user
		$limit = array('public = true and user_id = %i', $id);
	}

	$all = dibi::select('count(*)')->from('services');
	$off = dibi::select('count(*)')->from('services')->where('response_ms = "9999"');
	$r = dibi::select('*')->from('services')->orderBy('response_ms DESC');

	$all->where($limit[0], $limit[1]);
	$off->where($limit[0], $limit[1]);
	$r->where($limit[0], $limit[1]);

	$r = $r->fetchAll();
	$all = $all->fetchSingle();
	$offline = $off->fetchSingle();
	$online = $all - $offline;
	
	echo '<h2>'. _('Services').' <span class="label label-danger">'
			. $offline .' '. _('OFFLINE').'</span> <span class="label label-success">'
			. $online.' '._('online').'</span></h2>';

	if(!$r) {
		echo '<p class="lead">'._("No services available.").'</p>';
	} else {

		echo '<table class="table table-hover table-condensed">';
		if($private) {
			echo '<thead><tr><th>'._('Hostname').'</th><th>'._('Time').'</th><th></th></tr></thead><tbody>';
		} else {
			echo '<thead><tr><th>'._('Hostname').'</th><th>'._('Time').'</th></tr></thead><tbody>';
		}
		foreach($r as $service) {

			if($service->response_ms == 9999)
				echo '<tr class="danger">';
			elseif($service->response_ms >= $service->alert_threshold)
				echo '<tr class="warning">';
			else
				echo '<tr>';
			echo '<td><h3>'
					. online($service->availability, $service->response_ms, $service->alert_threshold)
					.' <a href="service-show?id='.$service->id.'">'. $service->hostname . '</a>'
					. ' ('.$service->type .':'. $service->port . ')'
					. '</h3><p>'
					. $service->info
					. '</p></td>';
			echo '<td><h3>' . online_ms($service->response_ms, $service->alert_threshold) . '</h3></td>';


			if($private) {
				echo '<td><h3>
					<a class="btn btn-success" href="service-new?id='.$service->id.'"><i class="icon-edit"></i></a>
					<a class="btn btn-danger" '.jsDelete().' href="service-del?id='.$service->id.'"><i class="icon-trash"></i></a>
					</h3></td>';
			}

			echo '</tr>';

		}
		echo '</tbody></table>';

	}
	return true;
}

function incidentTable($id, $private = True) {

	if($private || $id != 0) { // user logged in
		$all = dibi::select('count(*)')->from('outages')->where('user_id = %i', $id)->fetchSingle();
		$outages = dibi::select('*')->from('outages')->where('user_id = %i', $id)->orderBy('outage_start desc')->fetchAll();
	} elseif($id == 0) { // public, all
		$all = dibi::select('count(*)')->from('outages')->fetchSingle();
		$outages = dibi::select('*')->from('outages')->orderBy('outage_start desc')->fetchAll();
	}

	echo '<h2>'. _('Incidents').'</h2>';
		
	if(!$outages) {
		echo '<p class="lead">'._("No recorded outages.").'</p>';
	} else {

		echo '<table class="table table-hover table-condensed">';
		echo '<thead><tr><th>'._('Service').'</th><th>'._('Start of outage').'</th><th>'._('End of outage').'</th></tr></thead><tbody>';
		foreach($outages as $incident) {
			if($incident->outage_end == '0000-00-00 00:00:00')
				echo '<tr class="danger">';
			else
				echo '<tr>';

			echo '<td>'. $incident->name .'</a></td>';
			echo '<td>'. $incident->outage_start .'</time></td>';
			if($incident->outage_end == '0000-00-00 00:00:00')
				echo '<td></td>';
			else
				echo '<td>'. $incident->outage_end .'</time></td>';
			echo '</tr>';

		}
		echo '</tbody></table>';

	}
	return true;
}

function service_types() {
	$types = array('Ping', 'HTTP', 'HTTP GET', 'TCP');
	
	$array = array();
	foreach ($types as $type) {
		$array[$type] = $type;
	}
	return $array;
}

function alert($text) {
	echo '<div class="alert alert-danger">';
	echo $text;
	echo '</div>';
}

function info($text) {
	echo '<div class="alert alert-success">';
	echo $text;
	echo '</div>';
}

function online($availability, $status, $max) {
	if($status === 9999)
		return "<span class='label label-danger'><i class='icon-frown'></i> ".$availability." %</span>";
	elseif($status >= $max)
		return "<span class='label label-warning'><i class='icon-meh'></i> ".$availability." %</span>";
	else
		return "<span class='label label-success'><i class='icon-smile'></i> ".$availability." %</span>";
}

function online_ms($status, $max) {
	if($status === 9999)
		return "";
	elseif($status >= $max)
		return "<span class='label label-warning'>".$status." ms</span>";
	else
		return "<span class='label label-success'>".$status." ms</span>";
}

function jsDelete() {
	return 'onclick="var x=window.confirm(\''._('Are you sure you want to delete this?').'\'); if (!x) return false;"';
}

function redir($dest) {
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: http://' . $_SERVER["SERVER_NAME"] . '/' . $_GET['locale'] . '/' . $dest);
	exit();
}

function generateRandomString($length = 10) {
	$characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}

?>