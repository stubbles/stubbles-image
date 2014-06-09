<?php
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  net\stubbles\img
 */
namespace net\stubbles\img\driver;
use stubbles\lang\Rootpath;
/**
 * Test for net\stubbles\img\driver\DummyImageDriver.
 *
 * @group  img
 * @group  driver
 */
class DummyImageDriverTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  DummyImageDriver
     */
    private $dummyImageDriver;
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
        $this->dummyImageDriver = new DummyImageDriver();
        $rootpath       = new Rootpath();
        $this->testPath = $rootpath->to('/src/test/resources/');
    }

    /**
     * @test
     * @expectedException  stubbles\lang\exception\IOException
     */
    public function loadWithoutHandleThrowsException()
    {
        $this->dummyImageDriver = new DummyImageDriver();
        $this->dummyImageDriver->load('dummy.png');
    }

    /**
     * @test
     */
    public function loadWithHandleReturnsHandle()
    {
        $handle           = imagecreatefrompng($this->testPath . 'empty.png');
        $imageDummyDriver = new DummyImageDriver($handle);
        $this->assertSame($handle, $imageDummyDriver->load('dummy.png'));
    }

    /**
     * @test
     */
    public function storeSucceeds()
    {
        $handle = imagecreatefrompng($this->testPath . 'empty.png');
        $this->assertSame($this->dummyImageDriver, $this->dummyImageDriver->store('dummy.png', $handle));
        $this->assertEquals('dummy.png', $this->dummyImageDriver->getLastStoredFileName());
        $this->assertSame($handle, $this->dummyImageDriver->getLastStoredHandle());
    }

    /**
     * @test
     */
    public function displaySucceeds()
    {
        $handle = imagecreatefrompng($this->testPath . 'empty.png');
        $this->dummyImageDriver->display($handle);
        $this->assertSame($handle, $this->dummyImageDriver->getLastDisplayedHandle());
    }

    /**
     * @test
     */
    public function extensionIsAlwaysDummy()
    {
        $this->assertEquals('.dummy', $this->dummyImageDriver->getExtension());
    }

    /**
     * @test
     */
    public function contentTypeIsAlwaysPresent()
    {
        $this->assertEquals('image/dummy', $this->dummyImageDriver->getContentType());
    }
}
