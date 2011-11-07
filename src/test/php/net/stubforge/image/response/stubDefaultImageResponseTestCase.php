<?php
/**
 * Test for net::stubforge::image::response::stubDefaultImageResponse.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubforge_image
 * @subpackage  response_test
 * @version     $Id: stubDefaultImageResponseTestCase.php 2906 2011-01-12 13:14:24Z mikey $
 */
stubClassLoader::load('net::stubforge::image::response::stubDefaultImageResponse');
/**
 * Helper class for the test: prevents sending the header.
 *
 * @package     stubforge_image
 * @subpackage  response_test
 */
class TeststubDefaultImageResponse extends stubDefaultImageResponse
{
    /**
     * helper method to send the header
     *
     * @param  string  $header
     */
    protected function header($header)
    {
        // prevent sending the header
    }
}
/**
 * Test for net::stubforge::image::response::stubDefaultImageResponse.
 *
 * @package     de_ui_imageserver
 * @subpackage  response_test
 */
class stubDefaultImageResponseTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  TeststubDefaultImageResponse
     */
    protected $defaultImageResponse;
    /**
     * image
     *
     * @var  resource<gd>
     */
    protected $handle;
    /**
     * image to be used in test
     *
     * @var  Image
     */
    protected $image;

    /**
     * set up test environment
     */
    public function setUp()
    {
        stubImageType::enableDummy();
        $this->defaultImageResponse = new TeststubDefaultImageResponse();
        $this->handle               = imagecreatefrompng(TEST_SRC_PATH . '/resources/empty.png');
        $this->image                = new stubImage('test', stubImageType::$DUMMY, $this->handle);
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubImageType::$DUMMY->value()->reset();
    }

    /**
     * @test
     */
    public function sendWithoutImageShouldStillWork()
    {
        $this->assertSame($this->defaultImageResponse, $this->defaultImageResponse->send());
    }

    /**
     * @test
     */
    public function sendResponseWithImage()
    {
        $this->assertSame($this->defaultImageResponse, $this->defaultImageResponse->setImage($this->image));
        $this->assertEquals(array('Content-type' => 'image/dummy'),
                            $this->defaultImageResponse->getHeaders()
        );
        $this->assertSame($this->defaultImageResponse, $this->defaultImageResponse->send());
        $this->assertSame($this->handle, stubImageType::$DUMMY->value()->getLastDisplayedHandle());
    }
}
?>