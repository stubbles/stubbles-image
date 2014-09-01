<?php
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  stubbles\img
 */
namespace stubbles\img;
use stubbles\img\driver\DummyDriver;
/**
 * Test for stubbles\img\Image.
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
        $this->testPath = dirname(__DIR__) . '/resources/';
        $this->handle   = imagecreatefrompng($this->testPath . 'empty.png');
    }

    /**
     * @test
     * @expectedException  InvalidArgumentException
     */
    public function instantiateWithIllegalHandleThrowsIllegalArgumentException()
    {
        new Image('foo', null, 'illegal');
    }

    /**
     * @test
     * @expectedException  InvalidArgumentException
     */
    public function instantiateWithIllegalResourceHandleThrowsIllegalArgumentException()
    {
        new Image('foo', null, fopen($this->testPath . 'empty.png', 'r+'));
    }

    /**
     * @test
     */
    public function instantiateWithHandle()
    {
        $this->handle = imagecreatefrompng($this->testPath . 'empty.png');
        $image  = new Image('foo', null, $this->handle);
        $this->assertEquals('foo', $image->fileName());
        $this->assertSame($this->handle, $image->handle());
        $this->assertEquals('.png', $image->fileExtension());
        $this->assertEquals('image/png', $image->mimeType());
    }

    /**
     * @test
     */
    public function instantiateWithLoad()
    {
        $image = Image::load($this->testPath . 'empty.png');
        $this->assertEquals($this->testPath . 'empty.png', $image->fileName());
        $this->assertNotNull($image->handle());
        $this->assertEquals('.png', $image->fileExtension());
        $this->assertEquals('image/png', $image->mimeType());
    }

    /**
     * @test
     */
    public function storeUsesDriver()
    {
        $dummyDriver = new DummyDriver();
        $image = new Image('foo', $dummyDriver, $this->handle);
        $this->assertSame($image, $image->store('bar'));
        $this->assertEquals('bar', $dummyDriver->lastStoredFileName());
        $this->assertSame($this->handle, $dummyDriver->lastStoredHandle());
    }

    /**
     * @test
     */
    public function displayUsesDriver()
    {
        $dummyDriver = new DummyDriver();
        $image = new Image('foo', $dummyDriver, $this->handle);
        $image->display();
        $this->assertSame($this->handle, $dummyDriver->lastDisplayedHandle());
    }
}
