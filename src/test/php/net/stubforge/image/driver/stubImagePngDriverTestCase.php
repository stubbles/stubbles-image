<?php
/**
 * Test for net::stubforge::image::driver::stubImagePngDriver.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubforge_image
 * @subpackage  driver_test
 * @version     $Id: stubImagePngDriverTestCase.php 1919 2008-11-07 15:08:56Z mikey $
 */
stubClassLoader::load('net::stubforge::image::stubImagePngDriver');
/**
 * Test for net::stubforge::image::driver::stubImagePngDriver.
 *
 * @package     stubforge_image
 * @subpackage  driver_test
 */
class stubImagePngDriverTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubImagePngDriver
     */
    protected $imagePngDriver;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->imagePngDriver = new stubImagePngDriver();
        if (file_exists(TEST_SRC_PATH . '/resources/new.png') === true) {
            unlink(TEST_SRC_PATH . '/resources/new.png');
        }
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        if (file_exists(TEST_SRC_PATH . '/resources/new.png') === true) {
            unlink(TEST_SRC_PATH . '/resources/new.png');
        }
    }

    /**
     * load image from non-existing file throws an exception
     *
     * @test
     * @expectedException  stubFileNotFoundException
     */
    public function loadFromNonexistingFileThrowsException()
    {
        $this->imagePngDriver->load(TEST_SRC_PATH . '/resources/doesNotExist.png');
    }

    /**
     * load image from corrupt file throws an exception
     *
     * @test
     * @expectedException  stubIOException
     */
    public function loadFromCorruptFileThrowsException()
    {
        $this->imagePngDriver->load(TEST_SRC_PATH . '/resources/corrupt.png');
    }

    /**
     * loading image from valid file returns the resource handle
     *
     * @test
     */
    public function loadReturnsResource()
    {
        $handle = $this->imagePngDriver->load(TEST_SRC_PATH . '/resources/empty.png');
        $this->assertTrue(is_resource($handle));
        $this->assertEquals('gd', get_resource_type($handle));
    }

    /**
     * storing image succeeds
     *
     * @test
     */
    public function storeSucceeds()
    {
        $handle = $this->imagePngDriver->load(TEST_SRC_PATH . '/resources/empty.png');
        $this->assertSame($this->imagePngDriver, $this->imagePngDriver->store(TEST_SRC_PATH . '/resources/new.png', $handle));
        $this->assertTrue(file_exists(TEST_SRC_PATH . '/resources/new.png'));
    }

    /**
     * failure to store an image throws an exception
     *
     * @test
     * @expectedException  stubIOException
     */
    public function storeThrowsExceptionWhenItFails()
    {
        $handle = $this->imagePngDriver->load(TEST_SRC_PATH . '/resources/empty.png');
        $this->imagePngDriver->store(TEST_SRC_PATH . '/resources/doesNotExist/new.png', $handle);
    }

    /**
     * extension for png driver is always .png
     *
     * @test
     */
    public function extensionIsAlwaysPng()
    {
        $this->assertEquals('.png', $this->imagePngDriver->getExtension());
    }

    /**
     * content type for png driver is always image/png
     *
     * @test
     */
    public function contentTypeIsAlwaysPresent()
    {
        $this->assertEquals('image/png', $this->imagePngDriver->getContentType());
    }
}
?>