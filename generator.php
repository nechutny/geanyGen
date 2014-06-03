<?php

function recursive_scan($dir,$callback)
{
	$files = glob($dir."/*.php");
	foreach($files as $file)
	{
		$callback($file);
	}

	$dirs = glob($dir."/*",GLOB_ONLYDIR);
	foreach($dirs as $d)
	{
		recursive_scan($d,$callback);
	}
}

if(count($argv) != 2)
{
	die("Required 1 argument with directory for scan.");
}

recursive_scan($argv[1],function($file)
{
	preg_match_all("/function\s+([a-zA-Z0-9_]+)\((.*)\)/",file_get_contents($file),$match);
	echo $file."\n";
	foreach($match[0] as $key => $val)
	{
		file_put_contents("output.tags",$match[1][$key].chr(0xCC)."128".chr(0xCD)."(".$match[2][$key].")".chr(0xCF)."\n",FILE_APPEND);
	}
});

?>
