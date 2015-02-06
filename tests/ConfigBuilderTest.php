<?php

namespace Butterfly\Component\Config\Tests;

use Butterfly\Component\Config\ConfigBuilder;
use Butterfly\Component\Config\Parser\DelegatedParser;
use Butterfly\Component\Config\Parser\IParser;
use Butterfly\Component\Config\Parser\JsonParser;
use Butterfly\Component\Config\Parser\PhpParser;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ConfigBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $expectedConfig = array(
        'env'       => 'dev',
        'parameter' => 'value',
        'routes'    => array(
            'route1'        => 'route1value',
            'route2'        => 'route2value',
            'routeSection'  => array(
                'route1' => 'value1',
                'route2' => 'value2',
                'value'  => '123',
            ),
            'route3'        => 'value3',
            'route4'        => 'value4',
            'routeSection2' => array(
                'route1' => 'value1',
                'route2' => 'value2',
                'value'  => '123',
            ),
        ),
        'developer' => 'user1',
        'p1' => array('v3', 'v4'),
        'p2' => array(
            'p21' => 'v21_new',
            'p22' => 'v22',
            'p23' => 'v23',
        ),
    );

    public function testParse()
    {
        $parser = new DelegatedParser(array(
            new JsonParser(),
            new PhpParser()
        ));

        $configBuilder = new ConfigBuilder($parser);

        $configBuilder->addPath(__DIR__ . '/config/dev.php');
        $configBuilder->addPath(__DIR__ . '/config/main2.json');

        $this->assertEquals($this->expectedConfig, $configBuilder->getData());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseIfFileIsNotReadable()
    {
        $parser = $this->getParser();
        $builder = new ConfigBuilder($parser);

        $builder->addPath('unreadable_file.php');
        $builder->getData();
    }

    public function testAddPaths()
    {
        $parser = new DelegatedParser(array(
            new JsonParser(),
            new PhpParser()
        ));

        $configBuilder = new ConfigBuilder($parser);

        $configBuilder->addPaths(array(
            __DIR__ . '/config/dev.php',
            __DIR__ . '/config/main2.json'
        ));

        $this->assertEquals($this->expectedConfig, $configBuilder->getData());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|IParser
     */
    protected function getParser()
    {
        return $this->getMock('\Butterfly\Component\Config\Parser\IParser');
    }

    public function testCreateInstance()
    {
        $builder = ConfigBuilder::createInstance();

        $this->assertInstanceOf('\Butterfly\Component\Config\ConfigBuilder', $builder);
    }
}
