<?php
$usr = dibi::select('*')->from('users')->where('id = %i', $user->id)->fetch();
?>

<h1>
	<i class="icon-smile"></i> <?= _('User profile') ?>
</h1>

<table class="table table-bordered table-striped table-condensed table-hover">
	<tr><td><?= _('Email') ?></td><td><?php echo $user->getIdentity()->email ?></td></tr>
	<tr><td><?= _('Password') ?></td><td><a href='changepw' class="btn btn-success btn-small"><?= _('change it') ?></a></td></tr>
	<tr><td><?= _('Registered') ?></td><td><time class="timeago" datetime="<?php echo $usr->registered->format(DateTime::ISO8601) ?>"></time></td></tr>
	<tr><td><?= _('Last login') ?></td><td><time class="timeago" datetime="<?php echo $usr->last_login->format(DateTime::ISO8601) ?>"></time></td></tr>
	<tr><td><?= _('Validation') ?></td><td><?php echo $usr->validation ?></td></tr>
</table>

<h2>
	<i class="icon-remove"></i> <?= _('Cancel your account') ?>
</h2>

<div class="cancel">
<?php
use Nette\Forms\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
$form = new Form;
$form->setRenderer(new BootstrapRenderer);
$form->addProtection();

$form->addText('confirm', _('Please type "YES, DELETE"'))
	->setRequired()
	->setAttribute('placeholder', _('Do it! Do it!!! I dare you.'))
	->addRule(Form::EQUAL, _('Incorrect value.'), 'YES, DELETE');

$form->addSubmit('send', _('Cancel my account'));

if ($form->isSuccess()) {
	$values = $form->getValues();
	dibi::query('delete from users where id = %i', $user->id);
	redir('logout');
} else {
	$form->render();
}
?>
</div>

<div class="deleteLink" style="display:none">
	<a href="javascript:showDelete()">
		<?=_('Show delete form') ?>
	</a>
</div>

<script>
$(document).ready(function() {
	$('.cancel').hide();
	$('.deleteLink').show();
});
function showDelete(){
	$('.cancel').show();
	$('.deleteLink').hide();
}
</script>
