<?php
/**
 * Driver for png images.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubforge_image
 * @subpackage  driver
 * @version     $Id: stubImagePngDriver.php 1919 2008-11-07 15:08:56Z mikey $
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubFileNotFoundException',
                      'net::stubbles::lang::exceptions::stubIOException',
                      'net::stubforge::image::driver::stubImageDriver'
);
/**
 * Driver for png images.
 *
 * @package     stubforge_image
 * @subpackage  driver
 */
class stubImagePngDriver extends stubBaseObject implements stubImageDriver
{
    /**
     * loads given image
     *
     * @param   string        $fileName
     * @return  resource<gd>
     * @throws  stubFileNotFoundException
     * @throws  stubIOException
     */
    public function load($fileName)
    {
        if (file_exists($fileName) === false) {
            throw new stubFileNotFoundException($fileName);
        }
        
        $handle = @imagecreatefrompng($fileName);
        if (empty($handle) === true) {
            throw new stubIOException('The image ' . $fileName . ' seems to be broken.');
        }
        
        return $handle;
    }

    /**
     * stores given image
     *
     * @param   string              $fileName
     * @param   resource<gd>        $handle
     * @return  stubImagePngDriver
     * @throws  stubIOException
     */
    public function store($fileName, $handle)
    {
        if (@imagepng($handle, $fileName) === false) {
            throw new stubIOException('Could not save image to ' . $fileName);
        }
        
        return $this;
    }

    /**
     * displays given image (raw output to browser)
     *
     * @param  resource<gd>  $handle
     */
    // @codeCoverageIgnoreStart
    public function display($handle)
    {
        imagepng($handle);
    }
    // @codeCoverageIgnoreEnd

    /**
     * returns file extension for image type
     *
     * @return  string
     */
    public function getExtension()
    {
        return '.png';
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function getContentType()
    {
        return 'image/png';
    }
}
?>