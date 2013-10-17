<div class="row">
	<div class="col-sm-6 col">
		<h1><i class="icon-signin"></i> <?= _('Sign in')?></h1>
<?php

use Nette\Forms\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
$loginForm = new Form('login');
$loginForm->setRenderer(new BootstrapRenderer);

$loginForm->addText('email', _('Email'))
			->setRequired()
			->setType('email')
			->setAttribute('autofocus')
			->addRule(Form::EMAIL);

$loginForm->addPassword('password', _('Password'))
			->setRequired()
			->addRule(Form::MIN_LENGTH, _("Password must have at least 6 chars."), 6);

$loginForm->addSubmit('send', _('Sign in'));


if ($loginForm->isSuccess()) {
	$values = $loginForm->getValues();
	try {
		$user->login($values['email'], $values['password']);

		dibi::update('users', array('last_login' => new DateTime))
				->where('email = %s', $values['email'])
				->execute();

		redir('services');
	}
	catch (Exception $e) {
		alert(_('Sorry, login failed.'));
		$loginForm->render();
	}
} else {
	$loginForm->render();
}
?>
	</div>
	<div class="col-sm-6 col">
		<h1><i class="icon-user"></i> <?= _('Sign up') ?></h1>
<?php
$regForm = new Form('reg');
$regForm->setRenderer(new BootstrapRenderer);


$regForm->addText('email', _('Email'))
			->setRequired()
			->setType('email')
			->addRule(Form::EMAIL);

$regForm->addPassword('password1', _('Password'))
			->setRequired()
			->addRule(Form::MIN_LENGTH, _("Password must have at least 6 chars."), 6);

$regForm->addPassword('password2', _('Confirm password'))
			->setRequired()
			->addRule(Form::MIN_LENGTH, _("Password must have at least 6 chars."), 6)
			->addRule(Form::EQUAL, _("Passwords don't match."), $regForm['password1']);

$regForm->addSubmit('send', _('Create account'));

if ($regForm->isSuccess()) {
	$values = $regForm->getValues();

	$usr = dibi::query('select count(*) from users where email = %s', $values["email"])->fetchSingle();
	if(!$usr) {
		$secret = generateRandomString(16);

		$arr = array(
			'email' => $values['email'],
			'password' => password_hash($values['password1'], PASSWORD_DEFAULT),
			'registered' => new DateTime,
			'last_login' => new DateTime,
			'validation' => $secret
			);

		dibi::query('insert into users', $arr);
		$user->login($values['email'], $values['password1']);

		redir('services');
	} else {
		alert(_('This email is already registered.'));
		$regForm->render();
	}

} else {
	$regForm->render();
}
?>
	</div>
</div>
