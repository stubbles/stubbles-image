<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace stubbles\img;
use PHPUnit\Framework\TestCase;
use stubbles\img\driver\{DriverException, DummyDriver, PngDriver};

use function bovigo\assert\{
    assertThat,
    assertNotNull,
    expect,
    fail,
    predicate\equals,
    predicate\isSameAs
};
/**
 * Test for stubbles\img\Image.
 *
 * @group  img
 * @group  core
 */
class ImageTestCase extends TestCase
{
    /**
     * image
     *
     * @type  resource
     */
    private $handle;
    /**
     * path to test resource images
     *
     * @type  string
     */
    private $testPath;

    protected function setUp(): void
    {
        $this->testPath = dirname(__DIR__) . '/resources/';
        $this->handle   = imagecreatefrompng($this->testPath . 'empty.png');
    }

    /**
     * @test
     */
    public function instantiateWithIllegalResourceHandleThrowsIllegalArgumentException()
    {
        $fileHandle = fopen($this->testPath . 'empty.png', 'r+');
        if (false === $fileHandle) {
            fail('Could not create file handle');
            return;
        }

        expect(function() use($fileHandle) {
                new Image('foo', null, $fileHandle);
        })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function instantiateWithHandle()
    {
        $image  = new Image('foo', null, $this->handle);
        assertThat($image->handle(), isSameAs($this->handle));
    }

    /**
     * @test
     */
    public function instantiateWithHandleUsesGivenName()
    {
        $image  = new Image('foo', null, $this->handle);
        assertThat($image->fileName(), equals('foo'));
    }

    public function extensionsAndDrivers(): array
    {
        return [
            'extension .png'  => ['example.other.png', '.png', 'image/png'],
            'extension .jpeg' => ['examplejpg.jpeg', '.jpeg', 'image/jpeg'],
            'extension .jpg'  => ['example.jpg', '.jpeg', 'image/jpeg'],
            'no extension'    => ['foo', '.png', 'image/png'],
        ];
    }

    /**
     * @test
     * @dataProvider  extensionsAndDrivers
     * @group  select_driver
     * @since  6.2.0
     */
    public function instantiateWithoutDriverFallsbackToDriverBasedOnExtensionWhenFileDoesNotExist(string $fileName, string $expectedExtension, string $expectedMimeType): void
    {
        $image  = new Image($fileName);
        assertThat($image->fileExtension(), equals($expectedExtension));
        assertThat($image->mimeType(), equals($expectedMimeType));
    }

    public function mimetypesAndDrivers(): array
    {
        $path = dirname(__DIR__) . '/../resources/';
        return [
            'mimetype image/png'  => [$path . 'empty.png', '.png', 'image/png'],
            'mimetype image/jpeg' => [$path . 'empty.jpeg', '.jpeg', 'image/jpeg'],
        ];
    }

    /**
     * @test
     * @dataProvider  mimetypesAndDrivers
     * @group  select_driver
     * @since  7.0.0
     */
    public function instantiateWithoutDriverFallsbackToDriverBasedOnMimetypeWhenFileDoesExist(string $fileName, string $expectedExtension, string $expectedMimeType): void
    {
        $image  = new Image($fileName);
        assertThat($image->fileExtension(), equals($expectedExtension));
        assertThat($image->mimeType(), equals($expectedMimeType));
    }

    /**
     * @test
     * @group  select_driver
     * @since  7.0.0
     */
    public function instantiateWithoutDriverThrowsDriverExceptionWhenFileExistsAndNoDriverAvailable(): void
    {
        expect(function() { $image  = new Image(__FILE__); })
            ->throws(DriverException::class)
            ->withMessage('No driver available for mimetype text/x-php');
    }

    /**
     * @test
     */
    public function instantiateWithLoadCreatesHandle()
    {
        $image = Image::load($this->testPath . 'empty.png');
        assertNotNull($image->handle());
    }

    /**
     * @test
     */
    public function instantiateWithLoadUsesGivenFilename()
    {
        $image = Image::load($this->testPath . 'empty.png');
        assertThat($image->fileName(), equals($this->testPath . 'empty.png'));
    }

    /**
     * @test
     */
    public function instantiateWithLoadWithoutDriverFallsbackToPngDriver()
    {
        $image = Image::load($this->testPath . 'empty.png');
        assertThat($image->fileExtension(), equals('.png'));
        assertThat($image->mimeType(), equals('image/png'));
    }

    /**
     * @test
     */
    public function storeUsesDriver()
    {
        $dummyDriver = new DummyDriver();
        $image = new Image('foo', $dummyDriver, $this->handle);
        $image->store('bar');
        assertThat($dummyDriver->lastStoredFileName(), equals('bar'));
        assertThat($dummyDriver->lastStoredHandle(), isSameAs($this->handle));
    }

    /**
     * @test
     */
    public function displayUsesDriver()
    {
        $dummyDriver = new DummyDriver();
        $image = new Image('foo', $dummyDriver, $this->handle);
        $image->display();
        assertThat($dummyDriver->lastDisplayedHandle(), isSameAs($this->handle));
    }

    /**
     * @test
     * @group  content_for_display
     * @since  6.1.0
     */
    public function content()
    {
        $pngDriver = new PngDriver();
        $h = $pngDriver->load($this->testPath . 'empty.png');
        ob_start();
        $pngDriver->display($h);
        $displayContent = ob_get_contents();
        ob_end_clean();

        $image = Image::load($this->testPath . 'empty.png');
        assertThat($image->contentForDisplay(), equals($displayContent));
    }
}
