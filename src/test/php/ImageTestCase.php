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
     * @var  resource
     */
    private $handle;
    /**
     * path to test resource images
     *
     * @var  string
     */
    private $testPath;

    protected function setUp(): void
    {
        $this->testPath = dirname(__DIR__) . '/resources/';
        $handle = imagecreatefrompng($this->testPath . 'empty.png');
        if (false === $handle) {
            fail('Could not load test image');
        }

        $this->handle = $handle;
    }

    /**
     * @return  array<string,string[]>
     */
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
        $image  = Image::create($fileName, 10, 10);
        assertThat($image->fileExtension(), equals($expectedExtension));
        assertThat($image->mimeType(), equals($expectedMimeType));
    }

    /**
     * @return  array<string,string[]>
     */
    public function mimetypesAndDrivers(): array
    {
        $path = dirname(__DIR__) . '/resources/';
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
        $image  = Image::load($fileName);
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
        expect(function() { Image::load(__FILE__); })
            ->throws(DriverException::class)
            ->withMessage('No driver available for mimetype text/x-php');
    }

    /**
     * @test
     */
    public function instantiateWithLoadCreatesHandle(): void
    {
        $image = Image::load($this->testPath . 'empty.png');
        assertNotNull($image->handle());
    }

    /**
     * @test
     */
    public function instantiateWithLoadUsesGivenFilename(): void
    {
        $image = Image::load($this->testPath . 'empty.png');
        assertThat($image->fileName(), equals($this->testPath . 'empty.png'));
    }

    /**
     * @test
     */
    public function instantiateWithLoadWithoutDriverFallsbackToPngDriver(): void
    {
        $image = Image::load($this->testPath . 'empty.png');
        assertThat($image->fileExtension(), equals('.png'));
        assertThat($image->mimeType(), equals('image/png'));
    }

    /**
     * @test
     */
    public function storeUsesDriver(): void
    {
        $dummyDriver = new DummyDriver();
        $image = Image::create('foo', 10, 10, $dummyDriver);
        $image->store('bar');
        assertThat($dummyDriver->lastStoredFileName(), equals('bar'));
        assertThat($dummyDriver->lastStoredHandle(), isSameAs($image->handle()));
    }

    /**
     * @test
     */
    public function displayUsesDriver(): void
    {
        $dummyDriver = new DummyDriver();
        $image = Image::create('foo', 10, 10, $dummyDriver);
        $image->display();
        assertThat($dummyDriver->lastDisplayedHandle(), isSameAs($image->handle()));
    }

    /**
     * @test
     * @group  content_for_display
     * @since  6.1.0
     */
    public function content(): void
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
