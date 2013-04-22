<?php
	$iterations_left = 60;

	set_time_limit(0);


	// This file is used to indicate http activity by showing the browser's throbber
	while (!connection_aborted() and $iterations_left--)
	{
		echo chr(0);
		flush();
		sleep(1);
	}
?>