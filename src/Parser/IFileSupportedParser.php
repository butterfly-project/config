<?php

namespace Butterfly\Component\Config\Parser;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
interface IFileSupportedParser extends IParser
{
    /**
     * @param string $filePath
     * @return bool
     */
    public function isSupport($filePath);
}
