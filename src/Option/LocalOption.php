<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem\Option;

use Ixocreate\Filesystem\Adapter;
use Ixocreate\Filesystem\AdapterInterface;
use Ixocreate\Filesystem\OptionInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use League\Flysystem\Adapter\Local;

final class LocalOption implements OptionInterface
{
    /**
     * @var string
     */
    private const LINKS_SKIP = "skip";

    /**
     * @var string
     */
    private const LINKS_DISALLOW = "disallow";

    /**
     * @var array
     */
    private $config = [
        'directory' => '',
        'flag' => LOCK_EX,
        'linkHandling' => self::LINKS_DISALLOW,
        'permission' => [
            'file' => [
                'public'  => 0644,
                'private' => 0600,
            ],
            'dir'  => [
                'public'  => 0755,
                'private' => 0700,
            ],
        ],
    ];

    /**
     * LocalOption constructor.
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->config['directory'] = $directory;
    }

    /**
     * @return string
     */
    public function directory(): string
    {
        return $this->config['directory'];
    }

    /**
     * @return LocalOption
     */
    public function withDisallowLinks(): LocalOption
    {
        $option = clone $this;
        $option->config['linkHandling'] = self::LINKS_DISALLOW;

        return $option;
    }

    /**
     * @return LocalOption
     */
    public function withSkipLinks(): LocalOption
    {
        $option = clone $this;
        $option->config['linkHandling'] = self::LINKS_SKIP;

        return $option;
    }

    /**
     * @return string
     */
    public function linkHandling(): string
    {
        return $this->config['linkHandling'];
    }

    /**
     * @return LocalOption
     */
    public function withDisableLockEx(): LocalOption
    {
        $option = clone $this;
        $option->config['flag'] = 0;

        return $option;
    }

    /**
     * @return LocalOption
     */
    public function withEnableLockEx(): LocalOption
    {
        $option = clone $this;
        $option->config['flag'] = LOCK_EX;

        return $option;
    }

    public function isLockEx(): bool
    {
        return $this->config['flag'] === LOCK_EX;
    }

    /**
     * @param int $publicUmask
     * @param int $privateUmask
     * @return LocalOption
     */
    public function withFilePermissions(int $publicUmask, int $privateUmask): LocalOption
    {
        $option = clone $this;
        $option->config['permission']['file']['public'] = $publicUmask;
        $option->config['permission']['file']['private'] = $privateUmask;

        return $option;
    }

    /**
     * @return array
     */
    public function filePermissions(): array
    {
        return $this->config['permission']['file'];
    }

    /**
     * @param int $publicUmask
     * @param int $privateUmask
     * @return LocalOption
     */
    public function withDirectoryPermissions(int $publicUmask, int $privateUmask): LocalOption
    {
        $option = clone $this;
        $option->config['permission']['dir']['public'] = $publicUmask;
        $option->config['permission']['dir']['private'] = $privateUmask;

        return $option;
    }

    /**
     * @return array
     */
    public function directoryPermissions(): array
    {
        return $this->config['permission']['dir'];
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
        switch ($this->config['linkHandling']) {
            case self::LINKS_SKIP:
                $linkHandling = Local::SKIP_LINKS;
                break;
            case self::LINKS_DISALLOW:
            default:
                $linkHandling = Local::DISALLOW_LINKS;
                break;
        }


        return new Adapter(
            new Local(
                $this->config['directory'],
                $this->config['flag'],
                $linkHandling,
                $this->config['permission']
            )
        );
    }
}
