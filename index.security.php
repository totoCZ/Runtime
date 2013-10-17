<?php
use Nette\Security as NS;

class RuntimeAuthenticator extends Nette\Object implements NS\IAuthenticator {
	function authenticate(array $credentials) {
		list($email, $password) = $credentials;
		$usr = dibi::select('id, password')->from('users')->where('email = %s', $email)->fetch();
		
		if (!$usr) {
			throw new NS\AuthenticationException('User not found.');
		}
		
		if (!password_verify($password, $usr->password)) {
			throw new NS\AuthenticationException('Invalid password.');
		}
		
		return new NS\Identity($usr->id, '', array(
        	'email' => $email
        ));
		
	}
}

$user->setAuthenticator(new RuntimeAuthenticator);

// information leakage
header_remove('X-Powered-By');
?>