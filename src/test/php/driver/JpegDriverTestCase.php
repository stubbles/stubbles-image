<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace stubbles\img\driver;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
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
 * @since  6.2.0
 */
#[Group('img')]
#[Group('driver')]
class JpegDriverTestCase extends TestCase
{
    private JpegDriver $jpegDriver;
    private string $testPath;

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

    #[Test]
    public function loadFromNonexistingFileThrowsException(): void
    {
        expect(function() { $this->jpegDriver->load('doesNotExist.jpeg'); })
            ->throws(DriverException::class);
    }

    #[Test]
    #[WithoutErrorHandler]
    public function loadFromCorruptFileThrowsException(): void
    {
        expect(function() {
            $this->jpegDriver->load($this->testPath . 'corrupt.jpeg');
        })
            ->throws(DriverException::class)
            ->withMessage(
                sprintf(
                    '"%scorrupt.jpeg" is not a valid JPEG file',
                    $this->testPath
                )
            );
    }

    #[Test]
    public function storeSucceeds(): void
    {
        $handle = $this->jpegDriver->load($this->testPath . 'empty.jpeg');
        $this->jpegDriver->store($this->testPath . 'new.jpeg', $handle);
        assertThat($this->testPath . 'new.jpeg', isExistingFile());
    }

    #[Test]
    #[WithoutErrorHandler]
    public function storeThrowsExceptionWhenItFails(): void
    {
        $handle = $this->jpegDriver->load($this->testPath . 'empty.jpeg');
        expect(function() use ($handle) {
            $this->jpegDriver->store($this->testPath . 'foo/new.jpeg', $handle);
        })
            ->throws(DriverException::class)
            ->withMessage(
                sprintf(
                    'Could not save "%sfoo/new.jpeg": Failed to open stream: No such file or directory',
                    $this->testPath
                )
            );
    }

    #[Test]
    public function extensionIsAlwaysJpeg(): void
    {
        assertThat($this->jpegDriver->fileExtension(), equals('.jpeg'));
    }

    #[Test]
    public function contentTypeIsAlwaysPresent(): void
    {
        assertThat($this->jpegDriver->mimeType(), equals('image/jpeg'));
    }
}