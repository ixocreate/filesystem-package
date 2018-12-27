<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use Ixocreate\Contract\Application\ConfigProviderInterface;

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
}
