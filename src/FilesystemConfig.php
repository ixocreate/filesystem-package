<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use Ixocreate\Application\Service\SerializableServiceInterface;

final class FilesystemConfig implements SerializableServiceInterface
{
    /**
     * @var OptionInterface[]
     */
    private $storage = [];

    /**
     * FilesystemConfig constructor.
     * @param FilesystemConfigurator $filesystemConfigurator
     */
    public function __construct(FilesystemConfigurator $filesystemConfigurator)
    {
        $this->storage = $filesystemConfigurator->storagePool();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return \array_key_exists($name, $this->storage);
    }

    /**
     * @param string $name
     * @return OptionInterface
     */
    public function get(string $name): OptionInterface
    {
        return $this->storage[$name];
    }

    /**
     * @return array
     */
    public function names(): array
    {
        return \array_keys($this->storage);
    }

    /**
     * @return string|void
     */
    public function serialize()
    {
        return \serialize($this->storage);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->storage = \unserialize($serialized);
    }
}
