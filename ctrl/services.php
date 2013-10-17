<h1>
	<i class="icon-sitemap"></i> <?= _('Services') ?>
	<a class="btn btn-success" href='service-new'>
	<i class="icon-plus-sign-alt"></i> <?= _('Add a new service') ?>
</a>

</h1>


<?php
serviceTable($user->id);
?>