<?php
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  net\stubbles\img
 */
namespace net\stubbles\img\response;
use net\stubbles\img\Image;
use stubbles\webapp\response\Response;
/**
 * Response which contains only an image.
 */
interface ImageResponse extends Response
{
    /**
     * sets image for the response
     *
     * @param   Image          $image
     * @return  ImageResponse
     */
    public function setImage(Image $image);
}

