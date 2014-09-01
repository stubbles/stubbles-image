<?php
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  stubbles\img
 */
namespace stubbles\img\driver;
/**
 * Test for stubbles\img\driver\DummyDriver.
 *
 * @group  img
 * @group  driver
 */
class DummyDriverTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  \stubbles\img\driver\DummyDriver
     */
    private $dummyDriver;
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
        $this->dummyDriver = new DummyDriver();
        $this->testPath    = dirname(__DIR__) . '/../resources/';
    }

    /**
     * @test
     * @expectedException  stubbles\img\driver\DriverException
     */
    public function loadWithoutHandleThrowsException()
    {
        $this->dummyDriver = new DummyDriver();
        $this->dummyDriver->load('dummy.png');
    }

    /**
     * @test
     */
    public function loadWithHandleReturnsHandle()
    {
        $handle           = imagecreatefrompng($this->testPath . 'empty.png');
        $imageDummyDriver = new DummyDriver($handle);
        $this->assertSame($handle, $imageDummyDriver->load('dummy.png'));
    }

    /**
     * @test
     */
    public function storeSucceeds()
    {
        $handle = imagecreatefrompng($this->testPath . 'empty.png');
        $this->assertSame($this->dummyDriver, $this->dummyDriver->store('dummy.png', $handle));
        $this->assertEquals('dummy.png', $this->dummyDriver->lastStoredFileName());
        $this->assertSame($handle, $this->dummyDriver->lastStoredHandle());
    }

    /**
     * @test
     */
    public function displaySucceeds()
    {
        $handle = imagecreatefrompng($this->testPath . 'empty.png');
        $this->dummyDriver->display($handle);
        $this->assertSame($handle, $this->dummyDriver->lastDisplayedHandle());
    }

    /**
     * @test
     */
    public function extensionIsAlwaysDummy()
    {
        $this->assertEquals('.dummy', $this->dummyDriver->fileExtension());
    }

    /**
     * @test
     */
    public function contentTypeIsAlwaysPresent()
    {
        $this->assertEquals('image/dummy', $this->dummyDriver->mimeType());
    }
}
