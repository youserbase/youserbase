<?php
	require '../../includes/bootstrap.inc.php';
	restore_error_handlers();


	$filename = 'master_list.csv';
	$phrase_ids = array();

	$fp = fopen($filename, 'r');

	$languages = array_map('strtolower', array_map('trim', array_slice(fgetcsv($fp), 1)));
	fgetcsv($fp);

	while ($row = fgetcsv($fp))
	{
		$phrase_id = strtoupper(array_shift($row));

		echo $phrase_id.': ';

		foreach ($languages as $index=>$language)
		{
			if (empty($row[$index]))
			{
				continue;
			}

			DBManager::Query("INSERT IGNORE INTO phrases (phrase_id, language, phrase, youser_id, last_update) VALUES (?, ?, ?, 1, NOW())",
				$phrase_id,
				$language,
				$row[$index]
			);

			echo $language.' ';
		}

		echo "\n";
		flush();
	}

	fclose($fp);
?>