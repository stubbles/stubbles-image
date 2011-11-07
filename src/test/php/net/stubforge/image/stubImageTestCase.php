<?php
/**
 * Test for net::stubforge::image::stubImage.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubforge_image
 * @subpackage  test
 * @version     $Id: stubImageTestCase.php 1919 2008-11-07 15:08:56Z mikey $
 */
stubClassLoader::load('net::stubforge::image::stubImage');
/**
 * Test for net::stubforge::image::stubImage.
 *
 * @package     stubforge_image
 * @subpackage  test
 */
class stubImageTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * image
     *
     * @var  resource<gd>
     */
    protected $handle;

    /**
     * set up test environment
     */
    public function setUp()
    {
        stubImageType::enableDummy();
        $this->handle = imagecreatefrompng(TEST_SRC_PATH . '/resources/empty.png');
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubImageType::$DUMMY->value()->reset();
    }

    /**
     * instantiate with illegal handle
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function instantiateWithIllegalHandle()
    {
        $image = new stubImage('foo', null, 'illegal');
    }

    /**
     * instantiate with illegal handle
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function instantiateWithIllegalResourceHandle()
    {
        $image = new stubImage('foo', null, fopen(TEST_SRC_PATH . '/resources/empty.png', 'r+'));
    }

    /**
     * instantiate using a handle
     *
     * @test
     */
    public function instantiateWithHandle()
    {
        $this->handle = imagecreatefrompng(TEST_SRC_PATH . '/resources/empty.png');
        $image  = new stubImage('foo', null, $this->handle);
        $this->assertEquals('foo', $image->getName());
        $this->assertSame(stubImageType::$PNG, $image->getType());
        $this->assertSame($this->handle, $image->getHandle());
        $this->assertEquals('.png', $image->getExtension());
        $this->assertEquals('image/png', $image->getContentType());
    }

    /**
     * instantiate using load()
     *
     * @test
     */
    public function instantiateWithLoad()
    {
        $image = stubImage::load(TEST_SRC_PATH . '/resources/empty.png');
        $this->assertEquals(TEST_SRC_PATH . '/resources/empty.png', $image->getName());
        $this->assertSame(stubImageType::$PNG, $image->getType());
        $this->assertNotNull($image->getHandle());
        $this->assertEquals('.png', $image->getExtension());
        $this->assertEquals('image/png', $image->getContentType());
    }

    /**
     * store() should use the given driver
     *
     * @test
     */
    public function storeUsesDriver()
    {
        $image = new stubImage('foo', stubImageType::$DUMMY, $this->handle);
        $this->assertSame($image, $image->store('bar'));
        $this->assertEquals('bar', stubImageType::$DUMMY->value()->getLastStoredFileName());
        $this->assertSame($this->handle, stubImageType::$DUMMY->value()->getLastStoredHandle());
        $this->assertEquals('.dummy', $image->getExtension());
        $this->assertEquals('image/dummy', $image->getContentType());
    }

    /**
     * display() should use the given driver
     *
     * @test
     */
    public function displayUsesDriver()
    {
        $image = new stubImage('foo', stubImageType::$DUMMY, $this->handle);
        $image->display();
        $this->assertSame($this->handle, stubImageType::$DUMMY->value()->getLastDisplayedHandle());
        $this->assertEquals('.dummy', $image->getExtension());
        $this->assertEquals('image/dummy', $image->getContentType());
    }
}
?>