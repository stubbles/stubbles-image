<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace stubbles\img;

use GdImage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stubbles\img\driver\{DriverException, DummyDriver, PngDriver};

use function bovigo\assert\{
    assertThat,
    assertNotNull,
    expect,
    fail,
    outputOf,
    predicate\equals,
    predicate\isSameAs
};
/**
 * Test for stubbles\img\Image.
 */
#[Group('img')]
#[Group('core')]
class ImageTestCase extends TestCase
{
    private string $testPath;

    protected function setUp(): void
    {
        $this->testPath = dirname(__DIR__) . '/resources/';
    }

    /**
     * @return  array<string,string[]>
     */
    public static function extensionsAndDrivers(): array
    {
        return [
            'extension .png'  => ['example.other.png', '.png', 'image/png'],
            'extension .jpeg' => ['examplejpg.jpeg', '.jpeg', 'image/jpeg'],
            'extension .jpg'  => ['example.jpg', '.jpeg', 'image/jpeg'],
            'no extension'    => ['foo', '.png', 'image/png'],
        ];
    }

    /**
     * @since  6.2.0
     */
    #[Test]
    #[Group('select_driver')]
    #[DataProvider('extensionsAndDrivers')]
    public function instantiateWithoutDriverFallsbackToDriverBasedOnExtensionWhenFileDoesNotExist(
        string $fileName,
        string $expectedExtension,
        string $expectedMimeType
    ): void {
        $image  = Image::create($fileName, 10, 10);
        assertThat($image->fileExtension(), equals($expectedExtension));
        assertThat($image->mimeType(), equals($expectedMimeType));
    }

    /**
     * @return  array<string,string[]>
     */
    public static function mimetypesAndDrivers(): array
    {
        $path = dirname(__DIR__) . '/resources/';
        return [
            'mimetype image/png'  => [$path . 'empty.png', '.png', 'image/png'],
            'mimetype image/jpeg' => [$path . 'empty.jpeg', '.jpeg', 'image/jpeg'],
        ];
    }

    /**
     * @since  7.0.0
     */
    #[Test]
    #[Group('select_driver')]
    #[DataProvider('mimetypesAndDrivers')]
    public function instantiateWithoutDriverFallsbackToDriverBasedOnMimetypeWhenFileDoesExist(
        string $fileName,
        string $expectedExtension,
        string $expectedMimeType
    ): void {
        $image  = Image::load($fileName);
        assertThat($image->fileExtension(), equals($expectedExtension));
        assertThat($image->mimeType(), equals($expectedMimeType));
    }

    /**
     * @since  7.0.0
     */
    #[Test]
    #[Group('select_driver')]
    public function instantiateWithoutDriverThrowsDriverExceptionWhenFileExistsAndNoDriverAvailable(): void
    {
        expect(function() { Image::load(__FILE__); })
            ->throws(DriverException::class)
            ->withMessage('No driver available for mimetype text/x-php');
    }

    #[Test]
    public function instantiateWithLoadCreatesHandle(): void
    {
        $image = Image::load($this->testPath . 'empty.png');
        assertNotNull($image->handle());
    }

    #[Test]
    public function instantiateWithLoadUsesGivenFilename(): void
    {
        $image = Image::load($this->testPath . 'empty.png');
        assertThat($image->fileName(), equals($this->testPath . 'empty.png'));
    }

    #[Test]
    public function instantiateWithLoadWithoutDriverFallsbackToPngDriver(): void
    {
        $image = Image::load($this->testPath . 'empty.png');
        assertThat($image->fileExtension(), equals('.png'));
        assertThat($image->mimeType(), equals('image/png'));
    }

    #[Test]
    public function storeUsesDriver(): void
    {
        $dummyDriver = new DummyDriver();
        $image = Image::create('foo', 10, 10, $dummyDriver);
        $image->store('bar');
        assertThat($dummyDriver->lastStoredFileName(), equals('bar'));
        assertThat($dummyDriver->lastStoredHandle(), isSameAs($image->handle()));
    }

    #[Test]
    public function displayUsesDriver(): void
    {
        $dummyDriver = new DummyDriver();
        $image = Image::create('foo', 10, 10, $dummyDriver);
        $image->display();
        assertThat($dummyDriver->lastDisplayedHandle(), isSameAs($image->handle()));
    }

    /**
     * @since  6.1.0
     */
    #[Test]
    #[Group('content_for_display')]
    public function content(): void
    {
        $pngDriver = new PngDriver();
        $h = $pngDriver->load($this->testPath . 'empty.png');
        outputOf(
            function() use ($pngDriver, $h) { $pngDriver->display($h); },
            equals(Image::load($this->testPath . 'empty.png')->contentForDisplay())
        );
    }
}
