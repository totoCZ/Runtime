<?php
require 'func.cron.php';

$service_id = $_GET['id'];

$service = dibi::query('select * from services where (public = true or user_id = %i)', $user->id, 'and id = %i', $service_id)->fetch();

if(!$service) {
	alert(_('Not enough permissions.'));
	exit();
}
?>

<div class="page-header">
	<h1>
		<?php echo $service->hostname ?>
	</h1>
</div>

<h2>
	<?= _('On-demand check') ?>
</h2>

<p class="lead">
	<?php echo _('Result of the on-demand check was'); ?> 
<?php
$currentStatus = getStatus($service->type, $service->hostname, $service->port);
echo online_ms($currentStatus, $service->alert_threshold);
?>
</p>


<?php
if($currentStatus != '-1') { 
?>

<h2>
	<?= _('Ping') ?>
</h2>

<pre>
<?php
$cmd = 'ping -c 2 ' . $service->hostname;
system($cmd);
?>
</pre>

<h2>
	<?= _('Traceroute') ?>
</h2>

<pre>
<?php
$cmd = 'mtr --report --report-cycles=1 ' . $service->hostname;
system($cmd);
?>
</pre>

<?php
}
?>