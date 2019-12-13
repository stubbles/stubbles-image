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
    assertEmptyString,
    assertThat,
    expect,
    fail,
    predicate\equals,
    predicate\isSameAs
};
/**
 * Test for stubbles\img\driver\DummyDriver.
 *
 * @group  img
 * @group  driver
 */
class DummyDriverTestCase extends TestCase
{
    /**
     * instance to test
     *
     * @var  \stubbles\img\driver\DummyDriver
     */
    private $dummyDriver;
    /**
     * path to test resource images
     *
     * @var  string
     */
    private $testPath;

    protected function setUp(): void
    {
        $this->dummyDriver = new DummyDriver();
        $this->testPath    = dirname(__DIR__) . '/../resources/';
    }

    /**
     * @test
     */
    public function loadWithoutHandleThrowsException(): void
    {
        $this->dummyDriver = new DummyDriver();
        expect(function() { $this->dummyDriver->load('dummy.png'); })
                ->throws(DriverException::class);
    }

    /**
     * @return  resource
     */
    private function loadImage()
    {
        $handle = imagecreatefrompng($this->testPath . 'empty.png');
        if (false === $handle) {
            fail('Could not create file handle');
        }

        return $handle;
    }

    /**
     * @test
     */
    public function loadWithHandleReturnsHandle(): void
    {
        $handle = $this->loadImage();
        $imageDummyDriver = new DummyDriver($handle);
        assertThat($imageDummyDriver->load('dummy.png'), isSameAs($handle));
    }

    /**
     * @test
     */
    public function storeStoresFilenameAsLast(): void
    {
        $handle = $this->loadImage();
        assertThat(
            $this->dummyDriver->store('dummy.png', $handle)->lastStoredFileName(),
            equals('dummy.png')
        );
    }

    /**
     * @test
     */
    public function storeStoresHandleAsLast(): void
    {
        $handle = $this->loadImage();
        assertThat(
            $this->dummyDriver->store('dummy.png', $handle)->lastStoredHandle(),
            isSameAs($handle)
        );
    }

    /**
     * @test
     */
    public function displayStoresHandleAsLastDisplayed(): void
    {
        $handle = $this->loadImage();
        $this->dummyDriver->display($handle);
        assertThat($this->dummyDriver->lastDisplayedHandle(), isSameAs($handle));
    }

    /**
     * @test
     * @group  content_for_display
     * @since  6.1.0
     */
    public function contentForDisplayIsEmpty(): void
    {
        $handle = $this->loadImage();
        assertEmptyString($this->dummyDriver->contentForDisplay($handle));
    }

    /**
     * @test
     * @group  content_for_display
     * @since  6.1.0
     */
    public function contentForDisplayStoresHandleAsLastDisplayed(): void
    {
        $handle = $this->loadImage();
        $this->dummyDriver->contentForDisplay($handle);
        assertThat($this->dummyDriver->lastDisplayedHandle(), isSameAs($handle));
    }

    /**
     * @test
     */
    public function extensionIsAlwaysDummy(): void
    {
        assertThat($this->dummyDriver->fileExtension(), equals('.dummy'));
    }

    /**
     * @test
     */
    public function contentTypeIsAlwaysPresent(): void
    {
        assertThat($this->dummyDriver->mimeType(), equals('image/dummy'));
    }
}
