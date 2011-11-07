<?php
/**
 * Response which contains only an image.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubforge_image
 * @subpackage  response
 * @version     $Id: stubDefaultImageResponse.php 2906 2011-01-12 13:14:24Z mikey $
 */
stubClassLoader::load('net::stubbles::ipo::response::stubBaseResponse',
                      'net::stubforge::image::response::stubImageResponse'
);
/**
 * Response which contains only an image.
 *
 * @package     stubforge_image
 * @subpackage  response
 */
class stubDefaultImageResponse extends stubBaseResponse implements stubImageResponse
{
    /**
     * the image to be send
     *
     * @var  stubImage
     */
    protected $image;

    /**
     * sets image for the response
     *
     * @param   stubImage          $image
     * @return  stubImageResponse
     */
    public function setImage(stubImage $image)
    {
        $this->image = $image;
        $this->addHeader('Content-type', $image->getContentType());
        return $this;
    }

    /**
     * send the response out
     *
     * @return  stubImageResponse
     */
    public function send()
    {
        if (null !== $this->image) {
            $this->data = null;
            parent::send();
            $this->image->display();
        } else {
            parent::send();
        }

        return $this;
    }
}
?>
