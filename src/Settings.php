<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

final class Settings implements SettingsInterface
{
    /**
     * @var array
     */
    protected $settings = [];

    /**
     * Settings constructor.
     * @param array $settings
     */
    public function __construct(array $settings = [])
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (!\array_key_exists($key, $this->settings)) {
            return $default;
        }

        return $this->settings[$key];
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->settings[$key] = $value;
    }

    /**
     * @return array
     */
    public function settings(): array
    {
        return $this->settings;
    }
}
