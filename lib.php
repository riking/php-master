<?php
namespace kyork;

function serve_404()
{
	header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found", true, 404);
	include '404.html';
	die();
}

function libraryfile()
{
	if (__FILE__ == $_SERVER['SCRIPT_FILENAME'])
	{
		// may not be used as page
		serve_404();
	}
}

require('safestring.php');
libraryfile();

