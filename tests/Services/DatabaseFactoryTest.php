<?php

use Zend\ServiceManager\ServiceManager;

class DatabaseFactoryTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$container = new ServiceManager;
		$container->setFactory('loader', 'Services\LoaderFactory');
		$container->setFactory('logger', 'Services\LoggerFactory');
		$container->setFactory('database', 'Services\DatabaseFactory');

		$this->container = $container;
	}

	public function testDatabaseConnection()
	{
		$this->assertInstanceOf('Doctrine\DBAL\Connection', $this->container->get('database'));
	}

	public function testDatabaseQuery()
	{
  		$conn = $this->container->get('database');

		### https://www.codediesel.com/mysql/creating-sql-schemas-with-doctrine-dbal/
		
		$schema = new \Doctrine\DBAL\Schema\Schema;
		$usersTable = $schema->createTable("test_users");
		 
		$usersTable->addColumn("id", "integer", array("unsigned" => true));
		$usersTable->addColumn("name", "string", array("length" => 64));
		$usersTable->setPrimaryKey(array("id"));

  		$conn->setFetchMode(\PDO::FETCH_ASSOC);
		$sm = $conn->getSchemaManager();

		$tables = $sm->listTables();

		$tablenames = array();
		foreach ($tables as $table) {
			$tablenames[] = $table->getName();
		}
		if (false == in_array('test_users', $tablenames)) {
			$platform = $conn->getDatabasePlatform();
			$queries  = $schema->toSql($platform);
			$conn->query($queries[0]);
			$conn->query("INSERT INTO `test_users` (`id`, `name`) VALUES (1, 'user_67'),(2, 'user_78')");
		}
		$stmt  = $conn->query("SELECT name FROM test_users");
		$users = $stmt->fetchAll();

		$this->assertEquals('user_67', $users[0]['name']);
		$this->assertEquals('user_78', $users[1]['name']);
	}
}
