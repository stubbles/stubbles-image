<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace stubbles\img\driver;

use GdImage;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\{
    assertEmptyString,
    assertThat,
    expect,
    fail,
    predicate\equals,
    predicate\isSameAs
};
/**
 * Test for stubbles\img\driver\DummyDriver.
 */
#[Group('img')]
#[Group('driver')]
class DummyDriverTestCase extends TestCase
{
    private DummyDriver $dummyDriver;
    private string $testPath;

    protected function setUp(): void
    {
        $this->dummyDriver = new DummyDriver();
        $this->testPath    = dirname(__DIR__) . '/../resources/';
    }

    #[Test]
    public function loadWithoutHandleThrowsException(): void
    {
        $this->dummyDriver = new DummyDriver();
        expect(function() { $this->dummyDriver->load('dummy.png'); })
            ->throws(DriverException::class);
    }

    private function loadImage(): GdImage
    {
        $handle = imagecreatefrompng($this->testPath . 'empty.png');
        if (false === $handle) {
            fail('Could not create file handle');
        }

        return $handle;
    }

    #[Test]
    public function loadWithHandleReturnsHandle(): void
    {
        $handle = $this->loadImage();
        $imageDummyDriver = new DummyDriver($handle);
        assertThat($imageDummyDriver->load('dummy.png'), isSameAs($handle));
    }

    #[Test]
    public function storeStoresFilenameAsLast(): void
    {
        $handle = $this->loadImage();
        assertThat(
            $this->dummyDriver->store('dummy.png', $handle)->lastStoredFileName(),
            equals('dummy.png')
        );
    }

    #[Test]
    public function storeStoresHandleAsLast(): void
    {
        $handle = $this->loadImage();
        assertThat(
            $this->dummyDriver->store('dummy.png', $handle)->lastStoredHandle(),
            isSameAs($handle)
        );
    }

    #[Test]
    public function displayStoresHandleAsLastDisplayed(): void
    {
        $handle = $this->loadImage();
        $this->dummyDriver->display($handle);
        assertThat($this->dummyDriver->lastDisplayedHandle(), isSameAs($handle));
    }

    /**
     * @since  6.1.0
     */
    #[Test]
    #[Group('content_for_display')]
    public function contentForDisplayIsEmpty(): void
    {
        $handle = $this->loadImage();
        assertEmptyString($this->dummyDriver->contentForDisplay($handle));
    }

    /**
     * @since  6.1.0
     */
    #[Test]
    #[Group('content_for_display')]
    public function contentForDisplayStoresHandleAsLastDisplayed(): void
    {
        $handle = $this->loadImage();
        $this->dummyDriver->contentForDisplay($handle);
        assertThat($this->dummyDriver->lastDisplayedHandle(), isSameAs($handle));
    }

    #[Test]
    public function extensionIsAlwaysDummy(): void
    {
        assertThat($this->dummyDriver->fileExtension(), equals('.dummy'));
    }

    #[Test]
    public function contentTypeIsAlwaysPresent(): void
    {
        assertThat($this->dummyDriver->mimeType(), equals('image/dummy'));
    }
}
