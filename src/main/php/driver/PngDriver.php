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
/**
 * Driver for png images.
 */
class PngDriver implements ImageDriver
{
    /**
     * loads given image
     *
     * @param   string    $fileName
     * @return  resource
     * @throws  \stubbles\img\driver\DriverException
     */
    public function load($fileName)
    {
        if (!file_exists($fileName)) {
            throw new DriverException('The image ' . $fileName . ' could not be found');
        }

        $handle = @imagecreatefrompng($fileName);
        if (empty($handle)) {
            throw new DriverException('The image ' . $fileName . ' seems to be broken.');
        }

        return $handle;
    }

    /**
     * stores given image
     *
     * @param   string    $fileName
     * @param   resource  $handle
     * @return  \stubbles\img\driver\PngDriver
     * @throws  \stubbles\img\driver\DriverException
     */
    public function store($fileName, $handle)
    {
        if (!@imagepng($handle, $fileName)) {
            throw new DriverException('Could not save image to ' . $fileName);
        }

        return $this;
    }

    /**
     * displays given image (raw output to browser)
     *
     * @param  resource  $handle
     */
    public function display($handle)
    {
        imagepng($handle);
    }

    /**
     * returns file extension for image type
     *
     * @return  string
     */
    public function fileExtension()
    {
        return '.png';
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function mimeType()
    {
        return 'image/png';
    }
}
