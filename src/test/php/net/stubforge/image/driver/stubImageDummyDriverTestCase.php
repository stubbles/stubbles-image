<?php
/**
 * Test for net::stubforge::image::driver::stubImageDummyDriver.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubforge_image
 * @subpackage  driver_test
 * @version     $Id: stubImageDummyDriverTestCase.php 1919 2008-11-07 15:08:56Z mikey $
 */
stubClassLoader::load('net::stubforge::image::driver::stubImageDummyDriver');
/**
 * Test for net::stubforge::image::driver::stubImageDummyDriver.
 *
 * @package     stubforge_image
 * @subpackage  driver_test
 */
class stubImageDummyDriverTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubImageDummyDriver
     */
    protected $imageDummyDriver;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->imageDummyDriver = new stubImageDummyDriver();
    }

    /**
     * load image without giving a handle on construction throws an exception
     *
     * @test
     * @expectedException  stubIOException
     */
    public function loadWithoutHandleThrowsException()
    {
        $this->imageDummyDriver = new stubImageDummyDriver();
        $this->imageDummyDriver->load('dummy.png');
    }

    /**
     * load image with handle returns handle
     *
     * @test
     */
    public function loadWithHandleReturnsHandle()
    {
        $handle           = imagecreatefrompng(TEST_SRC_PATH . '/resources/empty.png');
        $imageDummyDriver = new stubImageDummyDriver($handle);
        $this->assertSame($handle, $imageDummyDriver->load('dummy.png'));
    }

    /**
     * storing image succeeds
     *
     * @test
     */
    public function storeSucceeds()
    {
        $handle = imagecreatefrompng(TEST_SRC_PATH . '/resources/empty.png');
        $this->assertSame($this->imageDummyDriver, $this->imageDummyDriver->store('dummy.png', $handle));
        $this->assertEquals('dummy.png', $this->imageDummyDriver->getLastStoredFileName());
        $this->assertSame($handle, $this->imageDummyDriver->getLastStoredHandle());
    }

    /**
     * displaying image succeeds
     *
     * @test
     */
    public function displaySucceeds()
    {
        $handle = imagecreatefrompng(TEST_SRC_PATH . '/resources/empty.png');
        $this->imageDummyDriver->display($handle);
        $this->assertSame($handle, $this->imageDummyDriver->getLastDisplayedHandle());
    }

    /**
     * extension for dummy driver is always .dummy
     *
     * @test
     */
    public function extensionIsAlwaysDummy()
    {
        $this->assertEquals('.dummy', $this->imageDummyDriver->getExtension());
    }

    /**
     * content type for dummy driver is always image/dummy
     *
     * @test
     */
    public function contentTypeIsAlwaysPresent()
    {
        $this->assertEquals('image/dummy', $this->imageDummyDriver->getContentType());
    }
}
?>