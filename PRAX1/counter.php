<?php
	$file = 'count.txt';
	// Open the file to get existing content
	$current = file_get_contents($file);
	
	echo $current;

	$current = $current+1;

	echo $current;
	// Write the contents back to the file
	file_put_contents($file, $current);
?>