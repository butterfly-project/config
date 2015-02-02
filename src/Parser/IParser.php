<?php

namespace Butterfly\Component\Config\Parser;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
interface IParser
{
    /**
     * @param string $file
     * @return array
     */
    public function parse($file);
}
