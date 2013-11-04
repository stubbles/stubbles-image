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
use net\stubbles\webapp\response\WebResponse;
/**
 * Response which contains only an image.
 */
class DefaultImageResponse extends WebResponse implements ImageResponse
{
    /**
     * the image to be send
     *
     * @type  Image
     */
    private $image;

    /**
     * sets image for the response
     *
     * @param   Image          $image
     * @return  ImageResponse
     */
    public function setImage(Image $image)
    {
        $this->image = $image;
        $this->addHeader('Content-type', $image->getContentType());
        return $this;
    }

    /**
     * clears the response
     *
     * @return  ImageResponse
     */
    public function clear()
    {
        $this->image = null;
        parent::clear();
        return $this;
    }

    /**
     * send the response out
     *
     * @return  ImageResponse
     */
    public function send()
    {
        if (null !== $this->image) {
            $this->write(null);
            parent::send();
            $this->image->display();
        } else {
            parent::send();
        }

        return $this;
    }
}
