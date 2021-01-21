<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use Ixocreate\ServiceManager\SubManager\AbstractSubManager;

final class FilesystemManager extends AbstractSubManager
{
    public static function validation(): ?string
    {
        return FilesystemInterface::class;
    }
}
