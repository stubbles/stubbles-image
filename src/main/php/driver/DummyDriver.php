<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  stubbles\img
 */
namespace stubbles\img\driver;
/**
 * Dummy driver for images.
 */
class DummyDriver implements ImageDriver
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
     * @throws  \stubbles\img\driver\DriverException
     */
    public function load(string $fileName)
    {
        if (null === $this->handle) {
            throw new DriverException('The image ' . $fileName . ' seems to be broken.');
        }

        return $this->handle;
    }

    /**
     * stores given image
     *
     * @param   string    $fileName
     * @param   resource  $handle
     * @return  \stubbles\img\driver\DummyDriver
     */
    public function store(string $fileName, $handle): ImageDriver
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
    public function lastStoredFileName(): string
    {
        return $this->lastStoredFileName;
    }

    /**
     * returns last stored file name
     *
     * @return  resource
     */
    public function lastStoredHandle()
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
    public function lastDisplayedHandle()
    {
        return $this->lastDisplayedHandle;
    }

    /**
     * returns file extension for image type
     *
     * @return  string
     */
    public function fileExtension(): string
    {
        return '.dummy';
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function mimeType(): string
    {
        return 'image/dummy';
    }
}
