<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  stubbles\img
 */
namespace stubbles\img;
use stubbles\img\driver\DummyDriver;

use function bovigo\assert\{
    assert,
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
class ImageTestCase extends \PHPUnit_Framework_TestCase
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

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->testPath = dirname(__DIR__) . '/resources/';
        $this->handle   = imagecreatefrompng($this->testPath . 'empty.png');
    }

    /**
     * @test
     */
    public function instantiateWithIllegalHandleThrowsIllegalArgumentException()
    {
        expect(function() { new Image('foo', null, 'illegal'); })
                ->throws(\InvalidArgumentException::class);
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
        assert($image->fileName(), equals('foo'));
        assert($image->handle(), isSameAs($this->handle));
        assert($image->fileExtension(), equals('.png'));
        assert($image->mimeType(), equals('image/png'));
    }

    /**
     * @test
     */
    public function instantiateWithLoad()
    {
        $image = Image::load($this->testPath . 'empty.png');
        assert($image->fileName(), equals($this->testPath . 'empty.png'));
        assertNotNull($image->handle());
        assert($image->fileExtension(), equals('.png'));
        assert($image->mimeType(), equals('image/png'));
    }

    /**
     * @test
     */
    public function storeUsesDriver()
    {
        $dummyDriver = new DummyDriver();
        $image = new Image('foo', $dummyDriver, $this->handle);
        assert($image->store('bar'), isSameAs($image));
        assert($dummyDriver->lastStoredFileName(), equals('bar'));
        assert($dummyDriver->lastStoredHandle(), isSameAs($this->handle));
    }

    /**
     * @test
     */
    public function displayUsesDriver()
    {
        $dummyDriver = new DummyDriver();
        $image = new Image('foo', $dummyDriver, $this->handle);
        $image->display();
        assert($dummyDriver->lastDisplayedHandle(), isSameAs($this->handle));
    }
}
