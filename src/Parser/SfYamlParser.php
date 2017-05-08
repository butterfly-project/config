<?php

namespace Butterfly\Component\Config\Parser;

use Symfony\Component\Yaml\Yaml;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class SfYamlParser implements IFileSupportedParser
{
    const SUPPORTED_FILE_EXTENSION = 'yml';

    /**
     * @param string $file
     * @return array
     * @throws \InvalidArgumentException if file format is not supported
     */
    public function parse($file)
    {
        if (!$this->isSupport($file)) {
            throw new \InvalidArgumentException(sprintf("This file format '%s' is not supported", $file));
        }

        return (array)Yaml::parse(file_get_contents($file));
    }

    /**
     * @param string $filePath
     * @return bool
     */
    public function isSupport($filePath)
    {
        return (self::SUPPORTED_FILE_EXTENSION === pathinfo($filePath, PATHINFO_EXTENSION));
    }
}
