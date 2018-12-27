<?php
declare(strict_types=1);
namespace Ixocreate\Filesystem;

use Ixocreate\Filesystem\Adapter\Factory\FilesystemAdapterSubManagerFactory;
use Ixocreate\Filesystem\Adapter\FilesystemAdapterSubManager;
use Ixocreate\Filesystem\Storage\Factory\StorageConfigFactory;
use Ixocreate\Filesystem\Storage\Factory\StorageSubManagerFactory;
use Ixocreate\Filesystem\Storage\StorageConfig;
use Ixocreate\Filesystem\Storage\StorageSubManager;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(StorageConfig::class, StorageConfigFactory::class);
$serviceManager->addSubManager(FilesystemAdapterSubManager::class, FilesystemAdapterSubManagerFactory::class);
$serviceManager->addSubManager(StorageSubManager::class, StorageSubManagerFactory::class);
