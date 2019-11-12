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
use stubbles\img\driver\DummyDriver;

use function bovigo\assert\{
    assertThat,
    assertNotNull,
    expect,
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
        expect(function() {
                new Image('foo', null, fopen($this->testPath . 'empty.png', 'r+'));
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

    /**
     * @test
     */
    public function instantiateWithoutDriverFallsbackToPngDriver()
    {
        $image  = new Image('foo', null, $this->handle);
        assertThat($image->fileExtension(), equals('.png'));
        assertThat($image->mimeType(), equals('image/png'));
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
}
