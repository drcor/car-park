<?php
	require_once('utils.php');

	session_start();

	$credentials = analyze_credentials('/home/drcorreia/Git/credentials.txt');

	if (!isset($_SESSION['username']) or !is_user($_SESSION['username'], $credentials)) {
		$_SESSION['username'] = 'drcor';
		echo 'in';
	}

	header("Location: /dashboard.php");
?>