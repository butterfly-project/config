<?php

namespace Butterfly\Component\Config\Tests\Parser;

use Butterfly\Component\Config\Parser\JsonParser;

class JsonParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $dir;

    protected function setUp()
    {
        $this->dir = __DIR__ . '/config';
    }

    public function testIsSupport()
    {
        $parser = new JsonParser();

        $this->assertTrue($parser->isSupport($this->dir . '/config.json'));
        $this->assertFalse($parser->isSupport($this->dir . '/config.php'));
    }

    public function testParse()
    {
        $configPath     = $this->dir . '/config.json';
        $expectedConfig = require $this->dir . '/expectedConfig.php';

        $parser = new JsonParser();

        $this->assertEquals($expectedConfig, $parser->parse($configPath));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseIfNotSupport()
    {
        $parser = new JsonParser();

        $parser->parse($this->dir . '/config.php');
    }
}
