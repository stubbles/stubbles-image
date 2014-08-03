<?php
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  stubbles\img
 */
namespace stubbles\img\response;
use stubbles\img\Image;
use stubbles\webapp\response\WebResponse;
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
     * @param   \stubbles\img\Image  $image
     * @return  \stubbles\img\response\ImageResponse
     */
    public function setImage(Image $image)
    {
        $this->image = $image;
        $this->addHeader('Content-type', $image->mimeType());
        return $this;
    }

    /**
     * clears the response
     *
     * @return  \stubbles\img\response\ImageResponse
     */
    public function clear()
    {
        $this->image = null;
        parent::clear();
        return $this;
    }

    /**
     * write data into the response
     *
     * @param   string|\stubbles\img\Image  $body
     * @return  \stubbles\img\response\ImageResponse
     */
    public function write($body)
    {
        if ($body instanceof Image) {
            $this->setImage($body);
        } else {
            $this->image = null;
            parent::write($body);
        }

        return $this;
    }

    /**
     * send the response out
     *
     * @return  \stubbles\img\response\ImageResponse
     */
    public function send()
    {
        if ($this->requestAllowsBody() && null !== $this->image) {
            parent::write(null);
            parent::send();
            $this->image->display();
        } else {
            parent::send();
        }

        return $this;
    }
}
