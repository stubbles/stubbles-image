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
 * Test for stubbles\img\driver\PngDriver.
 */
#[Group('img')]
#[Group('driver')]
class PngDriverTest extends TestCase
{
    private PngDriver $pngDriver;
    private string $testPath;

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

    #[Test]
    public function loadFromNonexistingFileThrowsException(): void
    {
        expect(function() { $this->pngDriver->load('doesNotExist.png'); })
                ->throws(DriverException::class);
    }

    #[Test]
    #[WithoutErrorHandler]
    public function loadFromCorruptFileThrowsException(): void
    {
        expect(function() {
            $this->pngDriver->load($this->testPath . 'corrupt.png');
        })
            ->throws(DriverException::class)
            ->withMessage(
                sprintf(
                    '"%scorrupt.png" is not a valid PNG file',
                    $this->testPath
                )
            );
    }

    #[Test]
    public function storeSucceeds(): void
    {
        $handle = $this->pngDriver->load($this->testPath . 'empty.png');
        $this->pngDriver->store($this->testPath . 'new.png', $handle);
        assertThat($this->testPath . 'new.png', isExistingFile());
    }

    #[Test]
    #[WithoutErrorHandler]
    public function storeThrowsExceptionWhenItFails(): void
    {
        $handle = $this->pngDriver->load($this->testPath . 'empty.png');
        expect(function() use ($handle) {
            $this->pngDriver->store($this->testPath . 'foo/new.png', $handle);
        })
            ->throws(DriverException::class)
            ->withMessage(
                sprintf(
                    'Could not save "%sfoo/new.png": Failed to open stream: No such file or directory',
                    $this->testPath
                )
            );
    }

    #[Test]
    public function extensionIsAlwaysPng(): void
    {
        assertThat($this->pngDriver->fileExtension(), equals('.png'));
    }

    #[Test]
    public function contentTypeIsAlwaysPresent(): void
    {
        assertThat($this->pngDriver->mimeType(), equals('image/png'));
    }
}
