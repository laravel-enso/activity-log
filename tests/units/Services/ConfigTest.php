<?php

namespace LaravelEnso\ActivityLog\Test\units\Services;

use LaravelEnso\ActivityLog\Services\Config;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    /** @test */
    public function can_set_default_alias()
    {
        $config = new Config(self::class, []);

        $this->assertEquals('config test', $config->alias());
    }

    /** @test */
    public function can_get_alias()
    {
        $config = new Config(self::class, ['alias' => 'aliasTest']);

        $this->assertEquals('aliasTest', $config->alias());
    }
}
