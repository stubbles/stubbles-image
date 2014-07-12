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
use stubbles\peer\http\Http;
use stubbles\peer\http\HttpVersion;
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
     * set up test environment
     */
    public function setUp()
    {
        ImageType::$DUMMY->value()->reset();
        $this->defaultImageResponse = $this->createResponse();
        $rootpath                   = new Rootpath();
        $this->handle               = imagecreatefrompng($rootpath->to('/src/test/resources/empty.png'));
        $this->image                = new Image('test', ImageType::$DUMMY, $this->handle);
    }

    /**
     * creates response where output facing methods are mocked
     *
     * @param   string  $requestMethod  optional  http request method to use, defaults to GET
     * @return  DefaultImageResponse
     */
    private function createResponse($requestMethod = Http::GET)
    {
        $mockRequest = $this->getMock('stubbles\input\web\WebRequest');
        $mockRequest->expects($this->once())
                    ->method('protocolVersion')
                    ->will($this->returnValue(HttpVersion::fromString(HttpVersion::HTTP_1_1)));
        $mockRequest->expects($this->any())
                    ->method('method')
                    ->will($this->returnValue($requestMethod));
        return $this->getMock(
                'stubbles\img\response\DefaultImageResponse',
                ['header', 'sendBody'],
                [$mockRequest]
        );
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
                                   ->with($this->equalTo('Content-type: ' . ImageType::$DUMMY->mimeType()));
        $this->defaultImageResponse->write($this->image)
                                   ->send();
    }

    /**
     * @test
     */
    public function sendResponseWithImage()
    {
        $this->defaultImageResponse->write($this->image)->send();
        $this->assertSame($this->handle,
                          ImageType::$DUMMY->value()->lastDisplayedHandle()
        );
    }

    /**
     * @test
     * @since  3.0.0
     */
    public function imageIsNotSendWhenRequestMethodIsHead()
    {
        $this->defaultImageResponse = $this->createResponse(Http::HEAD);
        $this->defaultImageResponse->write($this->image)->send();
        $this->assertNull(ImageType::$DUMMY->value()->lastDisplayedHandle());
    }

    /**
     * @test
     * @since  2.0.2
     */
    public function clearRemovesImageFromResponse()
    {
        $this->defaultImageResponse->write($this->image)->clear()->send();
        $this->assertNull(ImageType::$DUMMY->value()->lastDisplayedHandle());
    }

    /**
     * @test
     * @since  2.0.3
     */
    public function stringBodyIsNeverSendWhenImagePresent()
    {
        $this->defaultImageResponse->expects($this->never())
                                   ->method('sendBody');
        $this->defaultImageResponse->write('something')->write($this->image)->send();
    }

    /**
     * @test
     * @since  3.0.0
     */
    public function writeOverwritesExistingImage()
    {
        $this->defaultImageResponse->expects($this->once())
                                   ->method('sendBody')
                                   ->with($this->equalTo('something'));
        $this->defaultImageResponse->write($this->image)->write('something')->send();
        $this->assertNull(ImageType::$DUMMY->value()->lastDisplayedHandle());
    }
}
