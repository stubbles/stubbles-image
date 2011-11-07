<?php
/**
 * Dummy driver for images.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubforge_image
 * @subpackage  driver
 * @version     $Id: stubImageDummyDriver.php 1919 2008-11-07 15:08:56Z mikey $
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIOException',
                      'net::stubforge::image::driver::stubImageDriver'
);
/**
 * Dummy driver for images.
 *
 * @package     stubforge_image
 * @subpackage  driver
 */
class stubImageDummyDriver extends stubBaseObject implements stubImageDriver
{
    /**
     * dummy handle to be used
     *
     * @var  resource<gd>
     */
    protected $handle;
    /**
     * last stored file name
     *
     * @var  string
     */
    protected $lastStoredFileName;
    /**
     * last stored handle
     *
     * @var  resource<gd>
     */
    protected $lastStoredHandle;
    /**
     * last displayed handle
     *
     * @var  resource<gd>
     */
    protected $lastDisplayedHandle;

    /**
     * constructor
     *
     * @param  resource<gd>  $handle  optional
     */
    public function __construct($handle = null)
    {
        $this->handle = $handle;
    }

    /**
     * loads given image
     *
     * @param   string        $fileName
     * @return  resource<gd>
     * @throws  stubIOException
     */
    public function load($fileName)
    {
        if (null === $this->handle) {
            throw new stubIOException('The image ' . $fileName . ' seems to be broken.');
        }
        
        return $this->handle;
    }

    /**
     * stores given image
     *
     * @param   string                $fileName
     * @param   resource<gd>          $handle
     * @return  stubImageDummyDriver
     * @throws  stubIOException
     */
    public function store($fileName, $handle)
    {
        $this->lastStoredFileName = $fileName;
        $this->lastStoredHandle   = $handle;
        return $this;
    }

    /**
     * returns last stored file name
     *
     * @return  string
     */
    public function getLastStoredFileName()
    {
        return $this->lastStoredFileName;
    }

    /**
     * returns last stored file name
     *
     * @return  resource<gd>
     */
    public function getLastStoredHandle()
    {
        return $this->lastStoredHandle;
    }

    /**
     * resets stored data
     */
    public function reset()
    {
        $this->lastStoredFileName  = null;
        $this->lastStoredHandle    = null;
        $this->lastDisplayedHandle = null;
    }

    /**
     * displays given image (raw output to browser)
     *
     * @param  resource<gd>  $handle
     */
    public function display($handle)
    {
        $this->lastDisplayedHandle = $handle;
    }

    /**
     * returns last displayed handle
     *
     * @return  resource<gd>
     */
    public function getLastDisplayedHandle()
    {
        return $this->lastDisplayedHandle;
    }

    /**
     * returns file extension for image type
     *
     * @return  string
     */
    public function getExtension()
    {
        return '.dummy';
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function getContentType()
    {
        return 'image/dummy';
    }
}
?>