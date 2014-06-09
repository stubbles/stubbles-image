<?php
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  stubbles\img
 */
namespace stubbles\img\driver;
use stubbles\lang\exception\IOException;
/**
 * Dummy driver for images.
 */
class DummyImageDriver implements ImageDriver
{
    /**
     * dummy handle to be used
     *
     * @type  resource
     */
    private $handle;
    /**
     * last stored file name
     *
     * @type  string
     */
    private $lastStoredFileName;
    /**
     * last stored handle
     *
     * @type  resource
     */
    private $lastStoredHandle;
    /**
     * last displayed handle
     *
     * @type  resource
     */
    private $lastDisplayedHandle;

    /**
     * constructor
     *
     * @param  resource  $handle
     */
    public function __construct($handle = null)
    {
        $this->handle = $handle;
    }

    /**
     * loads given image
     *
     * @param   string    $fileName
     * @return  resource
     * @throws  IOException
     */
    public function load($fileName)
    {
        if (null === $this->handle) {
            throw new IOException('The image ' . $fileName . ' seems to be broken.');
        }

        return $this->handle;
    }

    /**
     * stores given image
     *
     * @param   string    $fileName
     * @param   resource  $handle
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
     * @return  resource
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
     * @param  resource  $handle
     */
    public function display($handle)
    {
        $this->lastDisplayedHandle = $handle;
    }

    /**
     * returns last displayed handle
     *
     * @return  resource
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
