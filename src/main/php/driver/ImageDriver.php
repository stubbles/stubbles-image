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
 * Driver for images.
 */
interface ImageDriver
{
    /**
     * loads given image
     *
     * @param   string    $fileName
     * @return  resource
     */
    public function load($fileName);

    /**
     * stores given image
     *
     * @param   string    $fileName
     * @param   resource  $handle
     */
    public function store($fileName, $handle);

    /**
     * displays given image (raw output to console
     *
     * @param  resource $handle
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
