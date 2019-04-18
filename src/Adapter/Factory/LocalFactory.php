<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Filesystem\Adapter\Factory;

use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use League\Flysystem\Adapter\Local;

final class LocalFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        if (\is_array($options)) {
            //TODO Exception
        }
        $config = $this->getConfig($options);

        return new Local($config['root'], $config['writeFlags'], $config['linkHandling'], $config['permissions']);
    }

    private function getConfig(array $options) : array
    {
        $config = [
            'writeFlags' => LOCK_EX,
            'linkHandling' => Local::DISALLOW_LINKS,
            'permissions' => [],
        ];

        if (!\array_key_exists('root', $options)) {
            //TODO Exception
        }
        $config['root'] = '';
        foreach (\array_keys($config) as $key) {
            if (!\array_key_exists($key, $options)) {
                continue;
            }
            $config[$key] = $options[$key];
        }


        return $config;
    }
}
