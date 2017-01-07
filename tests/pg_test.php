<?php

include 'database.php';

function wrap()
{
	$dbh = \kyork\DB::pg_connect(array());
}
wrap();

