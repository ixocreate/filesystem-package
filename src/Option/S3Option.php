<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem\Option;

use Aws\S3\S3Client;
use Ixocreate\Filesystem\Adapter;
use Ixocreate\Filesystem\AdapterInterface;
use Ixocreate\Filesystem\OptionInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;

final class S3Option implements OptionInterface
{
    private $config = [
        'key' => '',
        'secret' => '',
        'region' => '',
        'version' => 'latest',
        'bucketName' => '',
        'initialPath' => '',
        'options' => [],
    ];

    public function setKey(string $key): void
    {
        $this->config['key'] = $key;
    }

    public function key(): string
    {
        return $this->config['key'];
    }

    public function setSecret(string $secret): void
    {
        $this->config['secret'] = $secret;
    }

    public function secret(): string
    {
        return $this->config['secret'];
    }

    public function setRegion(string $region): void
    {
        $this->config['region'] = $region;
    }

    public function region(): string
    {
        return $this->config['region'];
    }

    public function setVersion(string $version): void
    {
        $this->config['version'] = $version;
    }

    public function version(): string
    {
        return $this->config['version'];
    }

    public function setBucketName(string $bucketName): void
    {
        $this->config['bucketName'] = $bucketName;
    }

    public function bucketName(): string
    {
        return $this->config['bucketName'];
    }

    public function setInitialPath(string $initialPath): void
    {
        $this->config['initialPath'] = $initialPath;
    }

    public function initialPath(): string
    {
        return $this->config['initialPath'];
    }

    /**
     * @param $name
     * @param $value
     * @deprecated
     */
    public function addMetaData($name, $value): void
    {
        $this->addOption($name, $value);
    }

    public function addOption($name, $value): void
    {
        $this->config['options'][$name] = $value;
    }

    /**
     * @param array $metaData
     * @deprecated
     */
    public function setMetaData(array $metaData): void
    {
        $this->setOptions($metaData);
    }

    public function setOptions(array $options): void
    {
        $this->config['options'] = $options;
    }

    public function options(): array
    {
        return $this->config['options'];
    }

    /**
     * @return string|void
     */
    public function serialize()
    {
        return \serialize($this->config);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->config = \unserialize($serialized);
    }

    /**
     * @param string $name
     * @param ServiceManagerInterface $serviceManager
     * @return AdapterInterface
     */
    public function create(string $name, ServiceManagerInterface $serviceManager): AdapterInterface
    {
        return new Adapter(
            new AwsS3V3Adapter(
                new S3Client([
                    'credentials' => [
                        'key'    => $this->key(),
                        'secret' => $this->secret(),
                    ],
                    'region' => $this->region(),
                    'version' => $this->version(),
                ]),
                $this->bucketName(),
                $this->initialPath(),
                null,
                null,
                $this->options()
            )
        );
    }
}
