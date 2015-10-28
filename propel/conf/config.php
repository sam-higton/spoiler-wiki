<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('spoilerwiki-local', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'dsn' => 'mysql:host=localhost;dbname=scotchbox',
  'user' => 'root',
  'password' => 'root',
  'attributes' =>
  array (
    'ATTR_EMULATE_PREPARES' => false,
  ),
  'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
));
$manager->setName('spoilerwiki-local');
$serviceContainer->setConnectionManager('spoilerwiki-local', $manager);
$serviceContainer->setAdapterClass('spoilerwiki-remote', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'dsn' => 'mysql:host=192.168.33.10;dbname=scotchbox',
  'user' => 'root',
  'password' => 'root',
  'attributes' =>
  array (
    'ATTR_EMULATE_PREPARES' => false,
  ),
  'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
));
$manager->setName('spoilerwiki-remote');
$serviceContainer->setConnectionManager('spoilerwiki-remote', $manager);
$serviceContainer->setDefaultDatasource('spoilerwiki-local');