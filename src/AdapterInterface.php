<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use League\Flysystem\FilesystemAdapter;

interface AdapterInterface
{
    public function adapter(): FilesystemAdapter;
}
