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
use net\stubbles\img\ImageType;
/**
 * Test for net\stubbles\img\response\DefaultImageResponse.
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
        $this->defaultImageResponse = $this->getMockBuilder('net\stubbles\img\response\DefaultImageResponse')
                                           ->setMethods(array('header'))
                                           ->getMock();
        $this->handle               = imagecreatefrompng(\net\stubbles\lang\ResourceLoader::getRootPath() . '/src/test/resources/empty.png');
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
}
