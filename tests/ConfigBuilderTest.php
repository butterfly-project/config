<?php

namespace Butterfly\Component\Config\Tests;

use Butterfly\Component\Config\ConfigBuilder;
use Butterfly\Component\Config\Parser\DelegatedParser;
use Butterfly\Component\Config\Parser\IParser;
use Butterfly\Component\Config\Parser\JsonParser;
use Butterfly\Component\Config\Parser\PhpParser;

class ConfigBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $parser = new DelegatedParser(array(
            new JsonParser(),
            new PhpParser()
        ));

        $configBuilder = new ConfigBuilder($parser);

        $configBuilder->addPath(__DIR__ . '/config/dev.php');
        $configBuilder->addPath(__DIR__ . '/config/main2.json');

        $expected = array(
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

        $this->assertEquals($expected, $configBuilder->getData());
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|IParser
     */
    protected function getParser()
    {
        return $this->getMock('\Butterfly\Component\Config\Parser\IParser');
    }
}