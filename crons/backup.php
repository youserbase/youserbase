<?php
	require dirname(__FILE__).'/../classes/vendor/simplepie.inc';
	require dirname(__FILE__).'/../classes/ClassLoader.class.php';
	require dirname(__FILE__).'/../includes/classloader.inc.php';
	require dirname(__FILE__).'/../includes/config.inc.php';
	require dirname(__FILE__).'/../includes/functions.inc.php';

	
	//Backupverzeichnis erzeugen
	exec('mkdir /tmp/backup');
	
	// Dumps erzeugen
	exec("mysqldump --opt --quick  --host=dbserver -u youserbase -pefh7tv86 fixed_state_1 | gzip > /tmp/backup/fixed_state_1.sql");
	exec("mysqldump --opt --quick  --host=dbserver -u youserbase -pefh7tv86 fixed_state_2 | gzip > /tmp/backup/fixed_state_2.sql");
	
	//Dumps packen
	$filename = date('Y-m-d-H-i').'.tar.gz';
	exec('tar cfvz /tmp/backup/'.$filename.' /tmp/backup/');

	//Gepackten Dump auf Backupserver schieben
	exec("wput /tmp/backup/$filename ftp://loft1956:ed8in6ot5ji@loft1956.myftpbackup.com/$filename");
	
	//Backupverzeichnis löschen	
	exec('rm /tmp/backup -R');
	
	/*
	$ftp = ftp_connect('loft1956.myftpbackup.com');
	ftp_login($ftp, 'loft1956', 'ed8in6ot5ji');
	exec("mysqldump --opt --quick  --host=dbserver -u youserbase -pefh7tv86 fixed_state_1 -r /tmp/backup/$filename");
	ftp_put($ftp, $filename, '/tmp/backup/'.$filename, FTP_ASCII);
	ftp_close($ftp);
	exec('exit');
	*/
?>
