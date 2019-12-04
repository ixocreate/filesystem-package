<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem;

use Ixocreate\Filesystem\Settings;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    /**
     * @covers \Ixocreate\Filesystem\Settings
     */
    public function testSettings()
    {
        $tests = [
            'integer' => 1,
            'string' => 'a string',
            'object' => new \DateTime(),
        ];

        $array = ['item'];

        $settings = new Settings($tests);

        $settings->set("array", $array);

        foreach ($tests as $name => $value) {
            $this->assertSame($value, $settings->get($name));
        }

        $settings->get('array');

        $this->assertNull($settings->get("not_set"));

        $check = $tests;
        $check['array'] = $array;
        foreach ($settings->settings() as $name => $value) {
            $this->assertArrayHasKey($name, $check);
            $this->assertSame($check[$name], $value);
        }
    }
}
