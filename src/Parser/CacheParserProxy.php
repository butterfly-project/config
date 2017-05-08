<?php

namespace Butterfly\Component\Config\Parser;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class CacheParserProxy implements IParser
{
    /**
     * @var IParser
     */
    protected $parser;

    /**
     * @var array
     */
    protected $cache;

    /**
     * @param IParser $parser
     * @param array $cache
     */
    public function __construct(IParser $parser, array $cache = array())
    {
        $this->parser = $parser;
        $this->cache  = $cache;
    }

    /**
     * @param string $file
     * @return array
     * @throws \InvalidArgumentException if file format is not supported
     */
    public function parse($file)
    {
        $mtime = filemtime($file);

        if (!array_key_exists($file, $this->cache) || $mtime != $this->cache[$file]['mtime']) {
            $this->cache[$file] = array(
                'mtime' => filemtime($file),
                'data'  => $this->parser->parse($file),
            );
        }

        return $this->cache[$file]['data'];
    }

    /**
     * @return array
     */
    public function getCache()
    {
        return $this->cache;
    }
}
