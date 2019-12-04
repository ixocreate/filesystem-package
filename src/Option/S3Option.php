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
use League\Flysystem\AwsS3v3\AwsS3Adapter;

final class S3Option implements OptionInterface
{
    private $config = [
        'key' => '',
        'secret' => '',
        'region' => '',
        'version' => 'latest',
        'bucketName' => '',
        'initialPath' => '',
        'metaData' => [],
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

    public function addMetaData($name, $value): void
    {
        $this->config['metaData'][$name] = $value;
    }

    public function setMetaData(array $metaData): void
    {
        $this->config['metaData'] = $metaData;
    }

    public function metaData(): array
    {
        return $this->config['metaData'];
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
            new AwsS3Adapter(
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
                $this->metaData()
            )
        );
    }
}
