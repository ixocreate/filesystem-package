<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem\Option;

use Ixocreate\Filesystem\Adapter;
use Ixocreate\Filesystem\Option\S3Option;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Filesystem\Option\S3Option
 */
class S3OptionTest extends TestCase
{
    public function testDefaults()
    {
        $s3Option = new S3Option();

        $this->assertSame('', $s3Option->key());
        $this->assertSame('', $s3Option->secret());
        $this->assertSame('', $s3Option->region());
        $this->assertSame('latest', $s3Option->version());
        $this->assertSame('', $s3Option->bucketName());
        $this->assertSame('', $s3Option->initialPath());
        $this->assertSame([], $s3Option->options());
    }

    public function testWithKey()
    {
        $s3Option = new S3Option();
        $s3Option->setKey('testKey');

        $this->assertSame('testKey', $s3Option->key());
    }

    public function testWithSecret()
    {
        $s3Option = new S3Option();
        $s3Option->setSecret('testSecret');

        $this->assertSame('testSecret', $s3Option->secret());
    }

    public function testWithRegion()
    {
        $s3Option = new S3Option();
        $s3Option->setRegion('testRegion');

        $this->assertSame('testRegion', $s3Option->region());
    }

    public function testWithVersion()
    {
        $s3Option = new S3Option();
        $s3Option->setVersion('testVersion');

        $this->assertSame('testVersion', $s3Option->version());
    }

    public function testWithBucketName()
    {
        $s3Option = new S3Option();
        $s3Option->setBucketName('testBucket');

        $this->assertSame('testBucket', $s3Option->bucketName());
    }

    public function testWithInitialPath()
    {
        $s3Option = new S3Option();
        $s3Option->setInitialPath('testPath');

        $this->assertSame('testPath', $s3Option->initialPath());
    }

    public function testOptions()
    {
        $s3Option = new S3Option();
        $s3Option->setOptions(['foo' => 'bar']);

        $this->assertSame(['foo' => 'bar'], $s3Option->options());

        $s3Option->addOption('foo2', 'bar2');

        $this->assertSame(['foo' => 'bar', 'foo2' => 'bar2'], $s3Option->options());
    }

    /**
     * @covers \Ixocreate\Filesystem\Option\S3Option::serialize
     * @covers \Ixocreate\Filesystem\Option\S3Option::unserialize
     */
    public function testSerialize()
    {
        $s3Option = new S3Option();
        $s3Option->setKey('key');
        $s3Option->setSecret('secret');
        $s3Option->setRegion('region');
        $s3Option->setVersion('version123');
        $s3Option->setBucketName('bucketName');
        $s3Option->setInitialPath('path');
        $s3Option->setOptions([
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
        ]);

        /** @var S3Option $newOption */
        $newOption = \unserialize(\serialize($s3Option));

        $this->assertInstanceOf(S3Option::class, $newOption);
        $this->assertSame($s3Option->key(), $newOption->key());
        $this->assertSame($s3Option->secret(), $newOption->secret());
        $this->assertSame($s3Option->region(), $newOption->region());
        $this->assertSame($s3Option->version(), $newOption->version());
        $this->assertSame($s3Option->bucketName(), $newOption->bucketName());
        $this->assertSame($s3Option->initialPath(), $newOption->initialPath());
        $this->assertSame($s3Option->options(), $newOption->options());
    }

    public function testCreate()
    {
        $serviceManager = $this->createMock(ServiceManagerInterface::class);

        $localOption = new S3Option();
        $localOption->setRegion('us-west-1');

        /** @var Adapter $adapter */
        $adapter = $localOption->create('test', $serviceManager);
        $reflection = new \ReflectionClass($adapter);
        $property = $reflection->getProperty('adapter');
        $property->setAccessible(true);

        $this->assertInstanceOf(AwsS3V3Adapter::class, $property->getValue($adapter));
    }
}
