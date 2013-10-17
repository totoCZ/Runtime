<?php
$service_id = $_GET['id'];

dibi::delete('services')
		->where('id = %i', $service_id)
		->where('user_id = %i', $user->id)
		->execute();

redir('services');
?>