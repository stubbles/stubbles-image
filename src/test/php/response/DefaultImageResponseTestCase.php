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
use stubbles\img\ImageType;
use stubbles\lang\Rootpath;
/**
 * Test for stubbles\img\response\DefaultImageResponse.
 */
class DefaultImageResponseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  DefaultImageResponse
     */
    private $defaultImageResponse;
    /**
     * image
     *
     * @type  resource
     */
    private $handle;
    /**
     * image to be used in test
     *
     * @type  Image
     */
    private $image;
    /**
     * path to test resource images
     *
     * @type  string
     */
    private $testPath;

    /**
     * set up test environment
     */
    public function setUp()
    {
        ImageType::$DUMMY->value()->reset();
        $this->defaultImageResponse = $this->getMockBuilder('stubbles\img\response\DefaultImageResponse')
                                           ->setMethods(['header', 'sendBody'])
                                           ->getMock();
        $rootpath                   = new Rootpath();
        $this->handle               = imagecreatefrompng($rootpath->to('/src/test/resources/empty.png'));
        $this->image                = new Image('test', ImageType::$DUMMY, $this->handle);
    }

    /**
     * @test
     */
    public function sendWithoutImageShouldStillWork()
    {
        $this->assertSame($this->defaultImageResponse,
                          $this->defaultImageResponse->send()
        );
    }

    /**
     * @test
     */
    public function addingImageSetsContentTypeHeaderToImageContentType()
    {
        $this->defaultImageResponse->expects($this->at(1))
                                   ->method('header')
                                   ->with($this->equalTo('Content-type: ' . ImageType::$DUMMY->getContentType()));
        $this->defaultImageResponse->setImage($this->image)
                                   ->send();
    }

    /**
     * @test
     */
    public function sendResponseWithImage()
    {
        $this->defaultImageResponse->setImage($this->image)->send();
        $this->assertSame($this->handle,
                          ImageType::$DUMMY->value()->getLastDisplayedHandle()
        );
    }

    /**
     * @test
     * @since  2.0.2
     */
    public function clearRemovesImageFromResponse()
    {
        $this->defaultImageResponse->setImage($this->image)->clear()->send();
        $this->assertNull(ImageType::$DUMMY->value()->getLastDisplayedHandle());
    }

    /**
     * @test
     * @since  2.0.3
     */
    public function bodyIsNeverSendWhenImagePresent()
    {
        $this->defaultImageResponse->expects($this->never())
                                   ->method('sendBody');
        $this->defaultImageResponse->setImage($this->image)->write('something')->send();
    }
}
