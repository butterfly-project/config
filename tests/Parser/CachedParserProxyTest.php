<?php

namespace Butterfly\Component\Config\Tests\Parser;

use Butterfly\Component\Config\Parser;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class CachedParserProxyTest extends \PHPUnit_Framework_TestCase
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
        $parser = $this->getCacheProxyParser();

        $expectedConfig = require $this->dir . '/expectedConfig.php';
        $configPath = $this->dir . '/config.php';

        $this->assertEquals($expectedConfig, $parser->parse($configPath));
        $this->assertArrayHasKey($configPath, $parser->getCache());

        $this->assertEquals($expectedConfig, $parser->parse($configPath));
    }

    /**
     * @return Parser\CacheParserProxy
     */
    protected function getCacheProxyParser()
    {
        return new Parser\CacheParserProxy(new Parser\PhpParser());
    }
}
