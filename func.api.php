<?php
function json_fail($why) {
	echo json_encode(array(
			'error' => $why
		));
	exit();
}
?>