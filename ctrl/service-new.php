<h1>
	<i class="icon-plus-sign-alt"></i> <?= _('Add a service')?>
</h1>

<?php
use Nette\Forms\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
$form = new Form;
$form->setRenderer(new BootstrapRenderer);
$form->addProtection();

// Service
$form->addGroup(_('Service'));

$form->addText('hostname', _('Hostname'))
		->setRequired()
		->setAttribute('autofocus')
		->setAttribute('placeholder', _('www.google.com'));

$form->addText('info', _('Description'))
		->setAttribute('placeholder', _('Mr. Google\'s webpage'));

$form->addCheckbox('public', _('Publish online'))
		->setDefaultValue(true);

// Check
$form->addGroup(_('Type of check'));

$form->addSelect('type', _('Type of service'), service_types())
		->setRequired();

$form->addText('port', _('Port'))   
		->setAttribute('placeholder', _('only TCP and HTTP other than 80'))
		->addCondition(Form::FILLED)
			->addRule(Form::RANGE, _('Must be in range from %d to %d.'), array(0, 65535));

$form->addText('match', _('Expect this text (HTTP GET)'));

// Alert

$form->addGroup('Alerts');

$form->addText('alert_threshold', _('Alert threshold in ms (only displays warning on website)'))
		->setRequired()
		->setType('number')
    	->setDefaultValue(10000)
    	->addRule(Form::RANGE, _('Must be in range from %d to %d.'), array(0, 10000));

$form->addSubmit('send', _('Add'));

// Edit
$service_id = '';
if(isset($_GET['id'])) {

	$service_id = $_GET['id'];
	$service = dibi::query('select * from services where id = ', $service_id, 'and user_id = %i', $user->id)->fetch();

	if(!$service) {
		alert(_('Not enough permissions.'));
		exit();
	}

	$form->setDefaults($service);
}

if ($form->isSuccess()) {

	$f = $form->getValues();
	
	$ip = gethostbynamel($f['hostname']);
	if($ip) {

		$arr = array(
			'id'				=> $service_id,
			'user_id'			=> $user->id,
			'hostname'			=> $f['hostname'],
			'info'				=> htmlspecialchars($f['info']),
			'type'				=> $f['type'],
			'port'				=> $f['port'],
			'text_match'		=> $f['match'],
			'public'			=> $f['public'],
			'alert_threshold' 	=> $f['alert_threshold'],
			'response_ms'		=> '1',
			'availability'		=> '100',
			'last_checked'		=> '01-01-1970 00:00:00',
			);

		dibi::query('insert into services', $arr, 'on duplicate key update %a', $arr);
	
		include './cron.php';

		redir('services');

	} else {
		alert(_('This is not a DNS hostname or it doesn\'t resolve. You need to use a valid DNS host.'));
		$form->render();
	}
} else {
	$form->render();
}
?>