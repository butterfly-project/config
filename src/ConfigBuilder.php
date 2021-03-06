<?php

namespace Butterfly\Component\Config;

use Butterfly\Component\Config\Parser\CacheProxyParser;
use Butterfly\Component\Config\Parser\DelegatedParser;
use Butterfly\Component\Config\Parser\IParser;
use Butterfly\Component\Config\Parser\JsonParser;
use Butterfly\Component\Config\Parser\PhpParser;
use Butterfly\Component\Config\Parser\SfYamlParser;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class ConfigBuilder
{
    const NOT_IGNORE_UNREADABLE_FILES = 1;

    const INCLUDE_PATH_SYMBOL = '&';

    /**
     * @var IParser
     */
    protected $parser;

    /**
     * @var bool
     */
    protected $ignoreUnreadableFiles;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param array $cache
     * @param int $options
     * @return static
     */
    public static function createInstance(array $cache = array(), $options = 0)
    {
        $parser = new DelegatedParser(array(
            new PhpParser(),
            new JsonParser(),
            new SfYamlParser(),
        ));

        $parser = new CacheProxyParser($parser, $cache);

        return new static($parser, $options);
    }

    /**
     * @param IParser $parser
     * @param int $options
     */
    public function __construct(IParser $parser, $options = 0)
    {
        $this->parser                = $parser;
        $this->ignoreUnreadableFiles = !($options & self::NOT_IGNORE_UNREADABLE_FILES);
    }

    /**
     * @param array $paths
     * @throws \RuntimeException if file is not readable
     */
    public function addPaths(array $paths)
    {
        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    /**
     * @param string $path
     * @throws \RuntimeException if file is not readable
     */
    public function addPath($path)
    {
        $this->addConfiguration($this->parse($path));
    }

    /**
     * @param array $data
     */
    public function addConfiguration(array $data)
    {
        $this->data = array_replace_recursive($this->data, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return IParser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param string $path
     * @return array
     * @throws \InvalidArgumentException if file is not readable
     */
    protected function parse($path)
    {
        if (is_readable($path)) {
            $baseDir = pathinfo($path, PATHINFO_DIRNAME);

            $data = $this->parser->parse($path);
            $data = $this->resolveImports($data, $baseDir);
            $data = $this->recursiveResolveIncludes($data, $baseDir);
            $data = $this->resolveExtends($data, $baseDir);

            return $data;
        }

        if ($this->ignoreUnreadableFiles) {
            return array();
        }

        throw new \InvalidArgumentException(sprintf("File %s is not readable", $path));
    }

    /**
     * @param array $data
     * @param string $baseDir
     * @return array
     */
    protected function resolveImports(array $data, $baseDir)
    {
        if (!isset($data['__import'])) {
            return $data;
        }

        $importPaths = (array)$data['__import'];
        unset($data['__import']);

        foreach ($importPaths as $path) {
            $data = array_replace_recursive($data, $this->parse($baseDir . '/' . $path));
        }

        return $data;
    }

    /**
     * @param array $data
     * @param string $baseDir
     * @return array
     */
    protected function resolveExtends(array $data, $baseDir)
    {
        if (!isset($data['__extend'])) {
            return $data;
        }

        $path = $data['__extend'];
        unset($data['__extend']);

        return array_replace_recursive($this->parse($baseDir . '/' . $path), $data);
    }

    /**
     * @param mixed $data
     * @param string $baseDir
     * @return mixed
     */
    protected function recursiveResolveIncludes($data, $baseDir)
    {
        return is_array($data)
            ? $this->doRecursiveResolveIncludes($data, $baseDir)
            : $this->includeFile($data, $baseDir);
    }

    /**
     * @param array $data
     * @param string $baseDir
     * @return array
     */
    protected function doRecursiveResolveIncludes(array $data, $baseDir)
    {
        $resolvedData = array();

        foreach ($data as $key => $valueInArray) {
            $resolvedData[$key] = $this->recursiveResolveIncludes($valueInArray, $baseDir);
        }

        return $resolvedData;
    }

    /**
     * @param mixed $value
     * @param string $baseDir
     * @return mixed
     */
    public function includeFile($value, $baseDir)
    {
        $firstSymbol = substr($value, 0, 1);

        if (self::INCLUDE_PATH_SYMBOL != $firstSymbol) {
            return $value;
        }

        $path = $baseDir . '/' . substr($value, 1);

        return $this->parse($path);
    }
}
