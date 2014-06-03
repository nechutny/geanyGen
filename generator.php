<?php

function recursive_scan($dir,$callback)
{
	// search for .php files and call on them calback
	$files = glob($dir."/*.php");
	foreach($files as $file)
	{
		$callback($file);
	}

	// scan for dirs for recursive walk
	$dirs = glob($dir."/*",GLOB_ONLYDIR);
	foreach($dirs as $d)
	{
		recursive_scan($d,$callback);
	}
}

// Print usage error
if(count($argv) != 2)
{
	die("Required 1 argument with directory for scan.");
}

// main call
recursive_scan($argv[1],function($file)
{
	// Find "function name(args)"
	preg_match_all("/function\s+([a-zA-Z0-9_]+)\((.*)\)/",file_get_contents($file),$match);
	echo $file."\n"; // print parsed file
	foreach($match[0] as $key => $val)
	{
		if(!in_array($match[1][$key],array("__construct","__destroy","__call","__callStatic","__get","__set","__isset","__unset","__sleep","__wakeup","__toString","__invoke","__set_state","__clone")))
		{ // ignore some methods and formate result
			file_put_contents("output.tags",$match[1][$key].chr(0xCC)."128".chr(0xCD)."(".$match[2][$key].")".chr(0xCF)."\n",FILE_APPEND);
		}
	}
});

?>
