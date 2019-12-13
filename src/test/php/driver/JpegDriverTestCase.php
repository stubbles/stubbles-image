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
 * Test for stubbles\img\driver\JpegDriver.
 *
 * @group  img
 * @group  driver
 * @since  6.2.0
 */
class JpegDriverTestCase extends TestCase
{
    /**
     * instance to test
     *
     * @var  \stubbles\img\driver\JpegDriver
     */
    private $jpegDriver;
    /**
     * path to test resource images
     *
     * @var  string
     */
    private $testPath;

    protected function setUp(): void
    {
        $this->jpegDriver = new JpegDriver();
        $this->testPath  = dirname(__DIR__) . '/../resources/';
        if (file_exists($this->testPath . 'new.jpeg')) {
            unlink($this->testPath . 'new.jpeg');
        }
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testPath . 'new.jpeg')) {
            unlink($this->testPath . 'new.jpeg');
        }
    }

    /**
     * @test
     */
    public function loadFromNonexistingFileThrowsException(): void
    {
        expect(function() { $this->jpegDriver->load('doesNotExist.jpeg'); })
                ->throws(DriverException::class);
    }

    /**
     * @test
     */
    public function loadFromCorruptFileThrowsException(): void
    {
        expect(function() {
            $this->jpegDriver->load($this->testPath . 'corrupt.jpeg');
        })
            ->throws(DriverException::class)
            ->withMessage("'" . $this->testPath . "corrupt.jpeg' is not a valid JPEG file");
    }

    /**
     * @test
     */
    public function loadReturnsResource(): void
    {
        $handle = $this->jpegDriver->load($this->testPath . 'empty.jpeg');
        assertTrue(is_resource($handle));
        assertThat(get_resource_type($handle), equals('gd'));
    }

    /**
     * @test
     */
    public function storeSucceeds(): void
    {
        $handle = $this->jpegDriver->load($this->testPath . 'empty.jpeg');
        $this->jpegDriver->store($this->testPath . 'new.jpeg', $handle);
        assertThat($this->testPath . 'new.jpeg', isExistingFile());
    }

    /**
     * @test
     */
    public function storeThrowsExceptionWhenItFails(): void
    {
        $handle = $this->jpegDriver->load($this->testPath . 'empty.jpeg');
        expect(function() use ($handle) {
            $this->jpegDriver->store($this->testPath . 'foo/new.jpeg', $handle);
        })
            ->throws(DriverException::class)
            ->withMessage("Could not save '" . $this->testPath . "foo/new.jpeg': failed to open stream: No such file or directory");
    }

    /**
     * @test
     */
    public function extensionIsAlwaysJpeg(): void
    {
        assertThat($this->jpegDriver->fileExtension(), equals('.jpeg'));
    }

    /**
     * @test
     */
    public function contentTypeIsAlwaysPresent(): void
    {
        assertThat($this->jpegDriver->mimeType(), equals('image/jpeg'));
    }
}