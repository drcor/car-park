<?php
	function get_dirs($path = '.') {
		$dirs = array();
	
		foreach (new DirectoryIterator($path) as $file) {
			if ($file->isDir() && !$file->isDot()) {
				$dirs[] = $file->getFilename();
			}
		}

		return $dirs;
	}
	
	function get_sensor_symbol($sensor_name) {
		switch ($sensor_name) {
			case 'Temperatura':
				return 'ºC';
			case 'Humidade':
				return '%';
			case 'CO2':
				return 'ppm';
			default:
				return '';
		}
	}

	function analyze_credentials($file_name) {
		$file = fopen($file_name, 'r');
		$credentials = array();

		while (!feof($file)) {
			$line = trim(fgets($file));
			$credentials[] = explode(':', $line);
		}
		fclose($file);

		return $credentials;
	}
	
	function parse_logs($file_name) {
		$file = fopen($file_name, 'r');
		$logs = array();

		while (!feof($file)) {
			$line = trim(fgets($file));
			$logs[] = explode(';', $line);
		}
		fclose($file);

		return $logs;
	}

	function is_user($username, $credentials) {
		foreach ($credentials as $row) {
			if ($username == $row[0]) {
				return true;
			}
		}

		return false;
	}

	function get_user($username, $credentials) {
		$user = array();
		foreach ($credentials as $row) {
			if ($username == $row[0]) {
				$user = $row;
				break;
			}
		}

		return $user;
	}
?>