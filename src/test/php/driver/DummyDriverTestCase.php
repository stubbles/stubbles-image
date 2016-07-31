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
namespace stubbles\img\driver;
use function bovigo\assert\{
    assert,
    expect,
    predicate\equals,
    predicate\isSameAs
};
/**
 * Test for stubbles\img\driver\DummyDriver.
 *
 * @group  img
 * @group  driver
 */
class DummyDriverTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  \stubbles\img\driver\DummyDriver
     */
    private $dummyDriver;
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
        $this->dummyDriver = new DummyDriver();
        $this->testPath    = dirname(__DIR__) . '/../resources/';
    }

    /**
     * @test
     */
    public function loadWithoutHandleThrowsException()
    {
        $this->dummyDriver = new DummyDriver();
        expect(function() { $this->dummyDriver->load('dummy.png'); })
                ->throws(DriverException::class);
    }

    /**
     * @test
     */
    public function loadWithHandleReturnsHandle()
    {
        $handle           = imagecreatefrompng($this->testPath . 'empty.png');
        $imageDummyDriver = new DummyDriver($handle);
        assert($imageDummyDriver->load('dummy.png'), isSameAs($handle));
    }

    /**
     * @test
     */
    public function storeStoresFilenameAsLast()
    {
        $handle = imagecreatefrompng($this->testPath . 'empty.png');
        assert(
                $this->dummyDriver->store('dummy.png', $handle)->lastStoredFileName(),
                equals('dummy.png')
        );
    }

    /**
     * @test
     */
    public function storeStoresHandleAsLast()
    {
        $handle = imagecreatefrompng($this->testPath . 'empty.png');
        assert(
                $this->dummyDriver->store('dummy.png', $handle)->lastStoredHandle(),
                isSameAs($handle)
        );
    }

    /**
     * @test
     */
    public function displayStoresHandleAsLastDisplayed()
    {
        $handle = imagecreatefrompng($this->testPath . 'empty.png');
        $this->dummyDriver->display($handle);
        assert($this->dummyDriver->lastDisplayedHandle(), isSameAs($handle));
    }

    /**
     * @test
     */
    public function extensionIsAlwaysDummy()
    {
        assert($this->dummyDriver->fileExtension(), equals('.dummy'));
    }

    /**
     * @test
     */
    public function contentTypeIsAlwaysPresent()
    {
        assert($this->dummyDriver->mimeType(), equals('image/dummy'));
    }
}
