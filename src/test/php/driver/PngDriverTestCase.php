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
use stubbles\lang\Rootpath;
/**
 * Test for stubbles\img\driver\PngDriver.
 *
 * @group  img
 * @group  driver
 */
class PngDriverTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  \stubbles\img\driver\PngDriver
     */
    private $pngDriver;
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
        $this->pngDriver = new PngDriver();
        $rootpath        = new Rootpath();
        $this->testPath  = $rootpath->to('/src/test/resources/');
        if (file_exists($this->testPath . 'new.png')) {
            unlink($this->testPath . 'new.png');
        }
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        if (file_exists($this->testPath . 'new.png')) {
            unlink($this->testPath . 'new.png');
        }
    }

    /**
     * @test
     * @expectedException  stubbles\img\driver\DriverException
     */
    public function loadFromNonexistingFileThrowsException()
    {
        $this->pngDriver->load('doesNotExist.png');
    }

    /**
     * @test
     * @expectedException  stubbles\img\driver\DriverException
     */
    public function loadFromCorruptFileThrowsException()
    {
        $this->pngDriver->load($this->testPath . 'corrupt.png');
    }

    /**
     * @test
     */
    public function loadReturnsResource()
    {
        $handle = $this->pngDriver->load($this->testPath . 'empty.png');
        $this->assertTrue(is_resource($handle));
        $this->assertEquals('gd', get_resource_type($handle));
    }

    /**
     * @test
     */
    public function storeSucceeds()
    {
        $handle = $this->pngDriver->load($this->testPath . 'empty.png');
        $this->assertSame($this->pngDriver, $this->pngDriver->store($this->testPath . 'new.png', $handle));
        $this->assertTrue(file_exists($this->testPath . 'new.png'));
    }

    /**
     * @test
     * @expectedException  stubbles\img\driver\DriverException
     */
    public function storeThrowsExceptionWhenItFails()
    {
        $handle = $this->pngDriver->load($this->testPath . 'empty.png');
        $this->pngDriver->store($this->testPath . 'foo/new.png', $handle);
    }

    /**
     * @test
     */
    public function extensionIsAlwaysPng()
    {
        $this->assertEquals('.png', $this->pngDriver->fileExtension());
    }

    /**
     * @test
     */
    public function contentTypeIsAlwaysPresent()
    {
        $this->assertEquals('image/png', $this->pngDriver->mimeType());
    }
}
