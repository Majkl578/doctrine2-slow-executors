<?php

/*
 * USAGE:
 * php index.php mysql new - create a default schema on MySQL
 * php index.php pgsql new - create a default schema on PostgreSQL
 * php index.mysql php - execute a query on MySQL
 * php index.pgsql php - execute a query on PostgreSQL
 */

const SIZE = 500;

require __DIR__ . '/Doctrine/ORM/Tools/Setup.php';

Doctrine\ORM\Tools\Setup::registerAutoloadDirectory(__DIR__);
(new Doctrine\Common\ClassLoader('Entities', __DIR__ ))->register();

if ($argc < 2) throw new Exception('No parameters given');

$cache = new Doctrine\Common\Cache\ArrayCache;
$config = new Doctrine\ORM\Configuration;
$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver(__DIR__ . '/Entities');
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cache);
$config->setProxyDir('/tmp');
$config->setProxyNamespace('MyProject\Proxies');
$config->setSQLLogger(new Doctrine\DBAL\Logging\EchoSQLLogger);

$config->setAutoGenerateProxyClasses(true);

switch ($argv[1]) {
	case 'mysql' :
		$connectionOptions = array(
			'driver' => 'pdo_mysql',
			'user' => 'changeme',
			'password' => 'changeme',
			'charset' => 'utf8',
			'dbname' => 'doctrine-slow',
		);
		break;
	case 'pgsql' :
		$connectionOptions = array(
			'driver' => 'pdo_pgsql',
			'user' => 'changeme',
			'password' => 'changeme',
			'charset' => 'utf8',
			'dbname' => 'doctrine-slow',
		);
		break;
	default : throw new Exception('Invalid platform');
}

$em = Doctrine\ORM\EntityManager::create($connectionOptions, $config);

if ($argc === 3 && $argv[2] === 'new') {
	// create schema
	(new Doctrine\ORM\Tools\SchemaTool($em))->createSchema($em->getMetadataFactory()->getAllMetadata());

	for ($i = 0; $i < SIZE; $i++) {
		$e = new Entities\Root();
		$e->setXyz($i);
		$em->persist($e);
	}

	ob_start();
	$em->flush();
	ob_end_clean();
} else {
	$query = $em->createQuery('UPDATE Entities\Root r SET r.xyz = 123 WHERE r.id > ' . (int) (SIZE / 2));

	$start = microtime(TRUE);
	$query->execute();
	$end = microtime(TRUE) - $start;

	var_dump($end);
}