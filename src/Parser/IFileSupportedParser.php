<?php

namespace Butterfly\Component\Config\Parser;

interface IFileSupportedParser extends IParser
{
    /**
     * @param string $filePath
     * @return bool
     */
    public function isSupport($filePath);
}
