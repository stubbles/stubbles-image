<?php
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  net\stubbles\img
 */
namespace net\stubbles\img;
/**
 * Test for net\stubbles\img\Image.
 *
 * @group  img
 * @group  core
 */
class ImageTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * image
     *
     * @type  resource
     */
    private $handle;
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
        $this->testPath = \net\stubbles\lang\ResourceLoader::getRootPath() . '/src/test/resources/';
        $this->handle   = imagecreatefrompng($this->testPath . 'empty.png');
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        ImageType::$DUMMY->value()->reset();
    }

    /**
     * @test
     * @expectedException  net\stubbles\lang\exception\IllegalArgumentException
     */
    public function instantiateWithIllegalHandleThrowsIllegalArgumentException()
    {
        new Image('foo', null, 'illegal');
    }

    /**
     * @test
     * @expectedException  net\stubbles\lang\exception\IllegalArgumentException
     */
    public function instantiateWithIllegalResourceHandleThrowsIllegalArgumentException()
    {
        new Image('foo', null, fopen($this->testPath . 'empty.png', 'r+'));
    }

    /**
     * instantiate using a handle
     *
     * @test
     */
    public function instantiateWithHandle()
    {
        $this->handle = imagecreatefrompng($this->testPath . 'empty.png');
        $image  = new Image('foo', null, $this->handle);
        $this->assertEquals('foo', $image->getName());
        $this->assertSame(ImageType::$PNG, $image->getType());
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
        $image = Image::load($this->testPath . 'empty.png');
        $this->assertEquals($this->testPath . 'empty.png', $image->getName());
        $this->assertSame(ImageType::$PNG, $image->getType());
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
        $image = new Image('foo', ImageType::$DUMMY, $this->handle);
        $this->assertSame($image, $image->store('bar'));
        $this->assertEquals('bar', ImageType::$DUMMY->value()->getLastStoredFileName());
        $this->assertSame($this->handle, ImageType::$DUMMY->value()->getLastStoredHandle());
        $this->assertEquals('.dummy', $image->getExtension());
        $this->assertEquals('image/dummy', $image->getContentType());
    }

    /**
     * @test
     */
    public function displayUsesDriver()
    {
        $image = new Image('foo', ImageType::$DUMMY, $this->handle);
        $image->display();
        $this->assertSame($this->handle, ImageType::$DUMMY->value()->getLastDisplayedHandle());
        $this->assertEquals('.dummy', $image->getExtension());
        $this->assertEquals('image/dummy', $image->getContentType());
    }
}
