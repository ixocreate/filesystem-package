<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use Ixocreate\Application\ServiceManager\ServiceManagerConfigurator;
use Ixocreate\Filesystem\Factory\FilesystemManagerFactory;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addSubManager(FilesystemManager::class, FilesystemManagerFactory::class);
