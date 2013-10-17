<h1><i class="icon-lock"></i> <?= _('Change password') ?></h1>
<?php
use Nette\Forms\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
$form = new Form;
$form->setRenderer(new BootstrapRenderer);
$form->addProtection();

$form->addPassword('oldpassword', _('Current password'))
		->setRequired()
		->setAttribute('autofocus')
		->setAttribute('placeholder', '12345')
		->addRule(Form::MIN_LENGTH, _('Password must have at least 6 chars.'), 6);

$form->addPassword('password1', _('New password'))
		->setRequired()
		->setAttribute('placeholder', '1234567890')
		->addRule(Form::MIN_LENGTH, _('Password must have at least 6 chars.'), 6);

$form->addPassword('password2', _('Confirm new password'))
		->setRequired()
		->setAttribute('placeholder', '1234567890')
		->addRule(Form::MIN_LENGTH, _('Password must have at least 6 chars.'), 6)
		->addRule(Form::EQUAL, _("Passwords don't match."), $form['password1']);

$form->addSubmit('send', _('Change'));

if ($form->isSuccess()) {
	$values = $form->getValues();
	$r      = dibi::query('select password from users where id = %i', $user->id)->fetch();

	if (password_verify($values['oldpassword'], $r->password)) {
		$hash = password_hash($values['password1'], PASSWORD_DEFAULT);
		dibi::query('update users set password = %s', $hash, 'where id = %i', $user->id);
		
		info(_('Password successfully changed.'));
	} else {
		alert(_('Current password is incorrect.'));
		$form->render();
	}
	
} else
	$form->render();
?>