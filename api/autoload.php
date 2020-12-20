<?php
spl_autoload_register(function($fileName) {
	$directory = 'classes/';
	$extenston = '.php';
	$path = $directory . $fileName . $extenston;
	if(file_exists($path)){
		include_once $path;
	}
});