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
 * Test for stubbles\img\driver\PngImageDriver.
 *
 * @group  img
 * @group  driver
 */
class PngImageDriverTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  PngImageDriver
     */
    private $pngImageDriver;
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
        $this->pngImageDriver = new PngImageDriver();
        $rootpath       = new Rootpath();
        $this->testPath = $rootpath->to('/src/test/resources/');
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
     * @expectedException  stubbles\lang\exception\FileNotFoundException
     */
    public function loadFromNonexistingFileThrowsException()
    {
        $this->pngImageDriver->load('doesNotExist.png');
    }

    /**
     * @test
     * @expectedException  stubbles\lang\exception\IOException
     */
    public function loadFromCorruptFileThrowsException()
    {
        $this->pngImageDriver->load($this->testPath . 'corrupt.png');
    }

    /**
     * @test
     */
    public function loadReturnsResource()
    {
        $handle = $this->pngImageDriver->load($this->testPath . 'empty.png');
        $this->assertTrue(is_resource($handle));
        $this->assertEquals('gd', get_resource_type($handle));
    }

    /**
     * @test
     */
    public function storeSucceeds()
    {
        $handle = $this->pngImageDriver->load($this->testPath . 'empty.png');
        $this->assertSame($this->pngImageDriver, $this->pngImageDriver->store($this->testPath . 'new.png', $handle));
        $this->assertTrue(file_exists($this->testPath . 'new.png'));
    }

    /**
     * @test
     * @expectedException  stubbles\lang\exception\IOException
     */
    public function storeThrowsExceptionWhenItFails()
    {
        $handle = $this->pngImageDriver->load($this->testPath . 'empty.png');
        $this->pngImageDriver->store($this->testPath . 'foo/new.png', $handle);
    }

    /**
     * @test
     */
    public function extensionIsAlwaysPng()
    {
        $this->assertEquals('.png', $this->pngImageDriver->getExtension());
    }

    /**
     * @test
     */
    public function contentTypeIsAlwaysPresent()
    {
        $this->assertEquals('image/png', $this->pngImageDriver->getContentType());
    }
}
