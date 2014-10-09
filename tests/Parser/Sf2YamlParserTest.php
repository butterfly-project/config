<?php

namespace Butterfly\Component\Config\Tests\Parser;

use Butterfly\Component\Config\Parser\Sf2YamlParser;

class Sf2YamlParserTest extends \PHPUnit_Framework_TestCase
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
        $parser = new Sf2YamlParser();

        $this->assertTrue($parser->isSupport($this->dir . '/config.yml'));
        $this->assertFalse($parser->isSupport($this->dir . '/config.json'));
    }

    public function testParse()
    {
        $configPath     = $this->dir . '/config.yml';
        $expectedConfig = require $this->dir . '/expectedConfig.php';

        $parser = new Sf2YamlParser();

        $this->assertEquals($expectedConfig, $parser->parse($configPath));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseIfNotSupport()
    {
        $parser = new Sf2YamlParser();

        $parser->parse($this->dir . '/config.json');
    }

    public function testParseIfEmptyFile()
    {
        $parser = new Sf2YamlParser();

        $this->assertEquals(array(), $parser->parse($this->dir . '/config_empty.yml'));
    }
}
