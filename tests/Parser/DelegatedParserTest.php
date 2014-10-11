<?php

namespace Butterfly\Component\Config\Tests\Parser;

use Butterfly\Component\Config\Parser;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class DelegatedParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $dir;

    protected function setUp()
    {
        $this->dir = __DIR__ . '/config';
    }

    public function testParse()
    {
        $parser = $this->getDelegatedParser();

        $expectedConfig = require $this->dir . '/expectedConfig.php';

        $this->assertEquals($expectedConfig, $parser->parse($this->dir . '/config.php'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseIfNotSupport()
    {
        $parser = $this->getDelegatedParser();

        $parser->parse($this->dir . '/config.json');
    }

    /**
     * @return Parser\DelegatedParser
     */
    protected function getDelegatedParser()
    {
        return new Parser\DelegatedParser(array(
            new Parser\PhpParser()
        ));
    }
}
