<?php
/**
 * The bootstrap class takes care of providing all necessary data required in the bootstrap process.
 *
 * @author   Frank Kleine <mikey@stubbles.net>
 * @package  stubbles
 * @version  $Id: bootstrap.php 1969 2008-12-19 11:26:24Z mikey $
 */
/**
 * The bootstrap class takes care of providing all necessary data required in the bootstrap process.
 *
 * @package  stubbles
 */
class stubBootstrap
{
    /**
     * path to php source files
     *
     * @var  string
     */
    private static $sourcePath = null;

    /**
     * returns path where common files are stored
     *
     * @return  string
     */
    public static function getCommonPath()
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'projects' . DIRECTORY_SEPARATOR . 'common';
    }

    /**
     * returns root path of the installation
     *
     * @return  string
     */
    public static function getRootPath()
    {
        return dirname(__FILE__);
    }

    /**
     * returns path to php source files

     * @return  string
     */
    public static function getSourcePath()
    {
        if (null == self::$sourcePath) {
            self::$sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'main';
        }
        
        return self::$sourcePath;
    }

    /**
     * loads stubbles core classes and initializes pathes
     *
     * @param  array<string,string>  $pathes     optional  list of pathes: project, [cache, config, log, page]
     * @param  string                $classFile  optional  defaults to stubbles.php
     */
    public static function init(array $pathes = array(), $classFile = 'stubbles.php')
    {
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $classFile;
        stubClassLoader::load('net::stubbles::lang::stubPathRegistry');
        stubPathRegistry::setPathes($pathes);
    }
}
?>