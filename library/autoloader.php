<?php
spl_autoload_register(function($class){
	$path = 'library' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class);
	
	foreach(['.abstract.php', '.interface.php', '.trait.php', '.class.php'] as $ext)
		if(file_exists($path . $ext))
			return include $path . $ext;
	
	return FALSE;
});