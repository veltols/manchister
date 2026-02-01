<?php

if ($connect_db == true) {

	$hostname_connecter = "";
	$database_connecter = "";
	$username_connecter = "";
	$password_connecter = "";
	switch ($Running_Environment) {
		case 'local':
			$hostname_connecter = "127.0.0.1";
			$database_connecter = "iqc_db";
			$username_connecter = "root";
			$password_connecter = "";
			break;
		case 'server':
			$hostname_connecter = "localhost";
			$database_connecter = "6001_db";
			$username_connecter = "UUUUUUUUUUUUUUUUUUUUUU";
			$password_connecter = 'PPPPPPPPPPPPPPPPPPPPPP';
			break;
	}



	$KONN = mysqli_connect($hostname_connecter, $username_connecter, $password_connecter, $database_connecter);

	if (mysqli_connect_errno()) {
		if ($error_report == true) {
			printf("<br><br><br>Connect failed: %s\n", mysqli_connect_error());
			echo "no connect";
			exit();
		} else {
			reportError('Db connection error', 'System', '0');
		}
	}

	mysqli_query($KONN, "SET NAMES 'utf8'");
	mysqli_query($KONN, 'SET CHARACTER SET utf8');
	if ($error_report == true) {
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	}
}



