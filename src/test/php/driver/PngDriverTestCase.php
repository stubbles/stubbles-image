<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace stubbles\img\driver;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\{
    assertThat,
    assertTrue,
    expect,
    predicate\equals,
    predicate\isExistingFile
};
/**
 * Test for stubbles\img\driver\PngDriver.
 *
 * @group  img
 * @group  driver
 */
class PngDriverTestCase extends TestCase
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

    protected function setUp(): void
    {
        $this->pngDriver = new PngDriver();
        $this->testPath  = dirname(__DIR__) . '/../resources/';
        if (file_exists($this->testPath . 'new.png')) {
            unlink($this->testPath . 'new.png');
        }
    }

    /**
     * clean up test environment
     */
    protected function tearDown(): void
    {
        if (file_exists($this->testPath . 'new.png')) {
            unlink($this->testPath . 'new.png');
        }
    }

    /**
     * @test
     */
    public function loadFromNonexistingFileThrowsException()
    {
        expect(function() { $this->pngDriver->load('doesNotExist.png'); })
                ->throws(DriverException::class);
    }

    /**
     * @test
     */
    public function loadFromCorruptFileThrowsException()
    {
        expect(function() {
                $this->pngDriver->load($this->testPath . 'corrupt.png');
        })
                ->throws(DriverException::class);
    }

    /**
     * @test
     */
    public function loadReturnsResource()
    {
        $handle = $this->pngDriver->load($this->testPath . 'empty.png');
        assertTrue(is_resource($handle));
        assertThat(get_resource_type($handle), equals('gd'));
    }

    /**
     * @test
     */
    public function storeSucceeds()
    {
        $handle = $this->pngDriver->load($this->testPath . 'empty.png');
        $this->pngDriver->store($this->testPath . 'new.png', $handle);
        assertThat($this->testPath . 'new.png', isExistingFile());
    }

    /**
     * @test
     */
    public function storeThrowsExceptionWhenItFails()
    {
        $handle = $this->pngDriver->load($this->testPath . 'empty.png');
        expect(function() use ($handle) {
                $this->pngDriver->store($this->testPath . 'foo/new.png', $handle);
        })
                ->throws(DriverException::class);
    }

    /**
     * @test
     */
    public function extensionIsAlwaysPng()
    {
        assertThat($this->pngDriver->fileExtension(), equals('.png'));
    }

    /**
     * @test
     */
    public function contentTypeIsAlwaysPresent()
    {
        assertThat($this->pngDriver->mimeType(), equals('image/png'));
    }
}
