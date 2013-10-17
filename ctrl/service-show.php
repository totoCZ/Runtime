<?php
$service_id = $_GET['id'];

$service = dibi::query('select * from services where (public = true or user_id = %i)', $user->id, 'and id = %i', $service_id)->fetch();

if(!$service) {
	alert(_('Not enough permissions.'));
	exit();
}

$outages = dibi::query('select * from outages where service_id = %i', $service_id, 'order by outage_start desc')->fetch();

$history = dibi::select('*')->from('history')->where('service_id = %i', $service_id)->fetchAll();
?>
<div class="page-header">

	<h1>
		<?php echo $service->hostname ?> (<?php echo $service->type ?>:<?php echo $service->port ?>)
		<a class="btn btn-success" href='service-trace?id=<?php echo $service->id; ?>'>Traceroute</a>
	</h1>

	<h2>
		<?php echo $service->info; ?>
		<?php echo online($service->availability, $service->response_ms, $service->alert_threshold) ?>
		<?php echo online_ms($service->response_ms, $service->alert_threshold) ?>
	</h2>

	<p class="lead">
		<span class="text-muted">
			<?php echo _('Last checked') ?>
			 <time class="timeago" datetime="<?php echo $service->last_checked->format(DateTime::ISO8601) ?>"></time>.
			<?php if($outages) { echo _('Last outage was') ?>
			<time class="timeago" datetime="<?php echo $outages->outage_start->format(DateTime::ISO8601) ?>"></time>.
			<?php } ?>
		</span>
	</p>

</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load('visualization', '1', {'packages':['annotatedtimeline']});
google.setOnLoadCallback(drawChart);
function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('datetime', '<?= _('Time') ?>');
	data.addColumn('number', '<?= _('Response time') ?>');
	data.addRows([
<?php
foreach($history as $row) {
	echo "[new Date("
			. $row->time->format('U')*1000
			. "),"
			. $row->response_ms
			. "],";
}
?>
	]);

	var options = {
		colors: ["#4cae4c"],
		displayZoomButtons: false,
		displayDateBarSeparator: false,
		thickness: 2,
		allValuesSuffix: " ms",
	};

	var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_div'));
	chart.draw(data, options);
}
</script>

<div id="chart_div" style="width: 100%; height: 500px;"></div>

<h2>
	<?= _('History') ?>
</h2>
<?php

if(!isset($_GET['offset']) || $_GET['offset'] <= 0)
	$offset = '0';
else
	$offset = $_GET['offset'];

$history_limit = dibi::select('*')->from('history')->where('service_id = %i', $service_id)->offset($offset)->limit(10)->orderBy('id desc')->fetchAll();
$count_total = count($history);

echo '<table class="table table-hover table-condensed">';

echo '<thead><tr><th>'._('Time').'</th><th>'._('Response time').'</th></th></tr></thead><tbody>';

foreach($history_limit as $row) {

	if($row->response_ms == -1)
		echo '<tr class="danger">';
	elseif($row->response_ms >= $service->alert_threshold)
		echo '<tr class="warning">';
	else
		echo '<tr>';

	echo '<td><time class="timeago" datetime="'
			. $row->time->format(DateTime::ISO8601)
			. '"></time></td><td>'
			. online_ms($row->response_ms, $service->alert_threshold)
			. '</td>';

	echo '</tr>';

}

echo '</tbody></table>';

?>
