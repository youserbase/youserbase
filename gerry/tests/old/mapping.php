<?php
	require '../../includes/bootstrap.inc.php';
	restore_error_handlers();

	$data = array(
		'location'=>array(
			'module' => 'User',
			'controller' => 'Nickpage',
			'method' => 'Display',
		),
		'parameters'=>array(
			'youser' => 'maunsen',
		),
		'param_separator'=>'&amp;'
	);

	header('Content-Type: text/plain');
	var_dump(FrontController::GetLink('User', 'Nickpage', 'Display', array('youser_id' => '1')));
	var_dump(FrontController::GetLink('System', 'Pages', 'Display', array('page' => 'ABOUT')));
	var_dump(FrontController::GetLink('System', 'Pages', 'Display', array('page' => 'MASTHEAD')));
?>