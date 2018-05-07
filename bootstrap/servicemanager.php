<?php
declare(strict_types=1);
namespace KiwiSuite\Filesystem;

use KiwiSuite\Filesystem\Adapter\Factory\FilesystemAdapterSubManagerFactory;
use KiwiSuite\Filesystem\Adapter\FilesystemAdapterSubManager;
use KiwiSuite\Filesystem\Storage\Factory\StorageConfigFactory;
use KiwiSuite\Filesystem\Storage\Factory\StorageSubManagerFactory;
use KiwiSuite\Filesystem\Storage\StorageConfig;
use KiwiSuite\Filesystem\Storage\StorageSubManager;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(StorageConfig::class, StorageConfigFactory::class);
$serviceManager->addSubManager(FilesystemAdapterSubManager::class, FilesystemAdapterSubManagerFactory::class);
$serviceManager->addSubManager(StorageSubManager::class, StorageSubManagerFactory::class);
