<?php
/**
 * Driver for images.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubforge_image
 * @subpackage  driver
 * @version     $Id: stubImageDriver.php 1919 2008-11-07 15:08:56Z mikey $
 */
/**
 * Driver for images.
 *
 * @package     stubforge_image
 * @subpackage  driver
 */
interface stubImageDriver extends stubObject
{
    /**
     * loads given image
     *
     * @param   string        $fileName
     * @return  resource<gd>
     * @throws  stubFileNotFoundException
     * @throws  stubIOException
     */
    public function load($fileName);

    /**
     * stores given image
     *
     * @param   string           $fileName
     * @param   resource<gd>     $handle
     * @return  stubImageDriver
     * @throws  stubIOException
     */
    public function store($fileName, $handle);

    /**
     * displays given image (raw output to console
     *
     * @param  resource<gd> $handle
     */
    public function display($handle);

    /**
     * returns file extension for image type
     *
     * @return  string
     */
    public function getExtension();

    /**
     * returns content type
     *
     * @return  string
     */
    public function getContentType();
}
?>