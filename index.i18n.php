<?php
if (!isset($_GET['page'])) {
	$detectedLanguage = prefered_language(array('en', 'cs', 'sk'));

	if($detectedLanguage == 'sk') {
		$detectedLanguage = 'cs';
	}

	$_GET['locale'] = $detectedLanguage;
	
	redir('index');
}

if(isset($_POST['locale'])) {
	$_GET['locale'] = $_POST['locale'];
	redir($_POST['page']);
}

if ($_GET['locale'] == 'cs') {
    $lang = 'cs_CZ.utf8';
} else {
    $lang = 'en_US';
}

// gettext
$directory = dirname(__FILE__) . '/locale';
$domain    = 'messages';

putenv("LANG=" . $lang);
setlocale(LC_MESSAGES, $lang);
bindtextdomain($domain, $directory);
textdomain($domain);
bind_textdomain_codeset($domain, 'UTF-8');

if($_GET['locale'] == 'cs') {
	Nette\Forms\Rules::$defaultMessages = array(
		Nette\Forms\Form::PROTECTION => 'Prosím odešlete formulář znovu.',
		Nette\Forms\Form::EQUAL => 'Prosím zadejte %s.',
		// Nette\Forms\Form::FILLED => 'Prosím vyplňte povinné pole.',
		// Nette\Forms\Form::MIN_LENGTH => 'Please enter a value of at least %d characters.',
		// Nette\Forms\Form::MAX_LENGTH => 'Please enter a value no longer than %d characters.',
		// Nette\Forms\Form::LENGTH => 'Please enter a value between %d and %d characters long.',
		Nette\Forms\Form::EMAIL => 'Prosím zadejte platný email.',
		Nette\Forms\Form::URL => 'Prosím zadejte platné URL.',
		Nette\Forms\Form::INTEGER => 'Prosím zadejte číslo.',
		Nette\Forms\Form::FLOAT => 'Prosím zadejte číslo.',
		// Nette\Forms\Form::RANGE => 'Please enter a value between %d and %d.',
		// Nette\Forms\Form::MAX_FILE_SIZE => 'The size of the uploaded file can be up to %d bytes.',
		// Nette\Forms\Form::IMAGE => 'The uploaded file must be image in format JPEG, GIF or PNG.',
		Nette\Forms\Form::FILLED => 'Položka musí být vyplněna.',
		// Nette\Forms\Form::REGEXP => 'Neplatný Nette\Forms\Formát položky „%label“.',
	);
}
?>