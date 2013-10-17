<h1>
	<i class="icon-globe"></i> <?=_('System status')?>
</h1>

<?php
$id = $_GET['id'];
serviceTable($id, False);
incidentTable($id, False);
?>