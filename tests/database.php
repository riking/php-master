<?php

namespace kyork;

class DB {
	static $pg_dsn_defaults;
	static $pdo_defaults;
	static $dbh;

	public static function pg_connect($settings)
	{
		$dsn_map = array_intersect_key($settings, \kyork\DB::$pg_dsn_defaults);
		$dsn_map += \kyork\DB::$pg_dsn_defaults;

		$dsnpairs = array();
		foreach ($dsn_map as $k => $v)
		{
			if ($v === null) continue;
			$dsnpairs[] = "{$k}={$v}";
		}
		$dsn = 'pgsql:' . implode(' ', $dsnpairs);
		$dbh = new \PDO($dsn, $settings['user'], $settings['pass'], \kyork\DB::$pdo_defaults);

		\kyork\DB::$dbh = $dbh;
		return $dbh;
	}

	public static function migrate($name, $sql)
	{

	}
}

DB::$pg_dsn_defaults = array(
	'host' => null,
	'port' => null,
	'unix_socket' => null,
	'user' => 'php_piscine',
	'dbname' => 'php_piscine',
	'sslmode' => 'disable',
);
DB::$pdo_defaults = array(
	\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
	\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
	\PDO::ATTR_EMULATE_PREPARES => false,
);

