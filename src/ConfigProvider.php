<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem\Package;

use Ixocreate\Application\ConfigProviderInterface;

final class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'storage' => [],
        ];
    }

    public function configName(): string
    {
        return 'filesystem';
    }

    public function configContent(): string
    {
        return \file_get_contents(__DIR__ . '/../resources/filesystem.config.example.php');
    }
}
