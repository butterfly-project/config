<?php

namespace Butterfly\Component\Config\Tests\Parser;

use Butterfly\Component\Config\Parser;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class CachedProxyParserTest extends \PHPUnit_Framework_TestCase
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
        $configPath     = $this->dir . '/config.php';

        $this->assertEquals($expectedConfig, $parser->parse($configPath));
        $this->assertArrayHasKey($configPath, $parser->getCache());

        $this->assertEquals($expectedConfig, $parser->parse($configPath));
    }

    public function testHasChanges()
    {
        $parser = $this->getCacheProxyParser();

        $configPath = $this->dir . '/config.php';

        $this->assertFalse($parser->hasChanges());
        $parser->parse($configPath);
        $this->assertTrue($parser->hasChanges());

        $cache = $parser->getCache();

        $newParser = $this->getCacheProxyParser($cache);
        $this->assertFalse($newParser->hasChanges());
        $newParser->parse($configPath);
        $this->assertFalse($newParser->hasChanges());
    }

    /**
     * @param array $cache
     * @return Parser\CacheProxyParser
     */
    protected function getCacheProxyParser(array $cache = array())
    {
        return new Parser\CacheProxyParser(new Parser\PhpParser(), $cache);
    }
}
