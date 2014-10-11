<?php

return array(
    'services' => array(
        'butterfly.config.builder'     => array(
            'class'     => 'Butterfly\Component\Config\ConfigBuilder',
            'arguments' => '@butterfly.config.parser'
        ),
        'butterfly.config.parser'      => array(
            'class'     => 'Butterfly\Component\Config\Parser\DelegatedParser',
            'arguments' => '#butterfly.config.parsers'
        ),
        'butterfly.config.php_parser'  => array(
            'class' => 'Butterfly\Component\Config\Parser\PhpParser',
        ),
        'butterfly.config.json_parser' => array(
            'class' => 'Butterfly\Component\Config\Parser\JsonParser',
        ),
        'butterfly.config.yaml_parser' => array(
            'class' => 'Butterfly\Component\Config\Parser\Sf2YamlParser',
        ),
    ),
    'tags'     => array(
        'butterfly.config.parsers' => array(
            'butterfly.config.php_parser',
            'butterfly.config.json_parser',
            'butterfly.config.yaml_parser',
        ),
    ),
);
