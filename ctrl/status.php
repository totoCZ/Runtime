<h1>
	<i class="icon-bar-chart"></i> <?= _('Your own status page') ?>
</h1>

<?php
$r = dibi::select('id, validation')->from('users')->where('id = %i', $user->id)->fetch();
?>

<h2><?= _('Your status page') ?></h2>
<h3>
	<a href='status-show?id=<?php echo $r->id; ?>'>http://<?php echo $_SERVER['SERVER_NAME']; ?>/<code>(cs, en)</code>/status-show?id=<?php echo $r->id; ?></a>
</h3>

<h2><?= _('JSON API, CORS') ?></h2>
<h3>
	<a href='/api/api-status.json?key=<?php echo $r->validation; ?>'>http://<?php echo $_SERVER['SERVER_NAME']; ?>/api/api-status.json?key=<?php echo $r->validation; ?></a>
</h3>

<h3>
	http://<?php echo $_SERVER['SERVER_NAME']; ?>/api/api-history.json?key=<?php echo $r->validation; ?>&amp;service_id=<code>int</code>
</h3>
