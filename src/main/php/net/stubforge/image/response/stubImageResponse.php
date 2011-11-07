<?php
/**
 * Response which contains only an image.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubforge_image
 * @subpackage  response
 * @version     $Id: stubImageResponse.php 1919 2008-11-07 15:08:56Z mikey $
 */
stubClassLoader::load('net::stubbles::ipo::response::stubResponse',
                      'net::stubforge::image::stubImage'
);
/**
 * Response which contains only an image.
 *
 * @package     stubforge_image
 * @subpackage  response
 */
interface stubImageResponse extends stubResponse
{
    /**
     * sets image for the response
     *
     * @param   stubImage          $image
     * @return  stubImageResponse
     */
    public function setImage(stubImage $image);
}
?>
