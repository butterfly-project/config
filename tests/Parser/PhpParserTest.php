<?php

namespace Butterfly\Component\Config\Tests\Parser;

use Butterfly\Component\Config\Parser\PhpParser;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class PhpParserTest extends \PHPUnit_Framework_TestCase
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
        $parser = new PhpParser();

        $this->assertTrue($parser->isSupport($this->dir . '/config.php'));
        $this->assertFalse($parser->isSupport($this->dir . '/config.json'));
    }

    public function testParse()
    {
        $configPath     = $this->dir . '/config.php';
        $expectedConfig = require $this->dir . '/expectedConfig.php';

        $parser = new PhpParser();

        $this->assertEquals($expectedConfig, $parser->parse($configPath));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseIfNotSupport()
    {
        $parser = new PhpParser();

        $parser->parse($this->dir . '/config.json');
    }
}
