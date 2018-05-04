<?php
namespace KiwiSuite\Filesystem;

use KiwiSuite\Contract\Application\ConfigProviderInterface;

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
