<?php

namespace Butterfly\Component\Config\Parser;

interface IParser
{
    /**
     * @param string $file
     * @return array
     */
    public function parse($file);
}
