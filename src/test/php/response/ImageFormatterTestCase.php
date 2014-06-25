<?php
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  stubbles\img
 */
namespace stubbles\img\response;
use stubbles\img\Image;
use stubbles\lang;
use stubbles\lang\ResourceLoader;
use stubbles\webapp\response\Headers;
/**
 * Test for stubbles\img\response\ImageFormatter.
 *
 * @group  response
 * @since  3.0.0
 */
class ImageFormatterTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  ImageFormatter
     */
    private $imageFormatter;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->imageFormatter = new ImageFormatter(new ResourceLoader());
    }

    /**
     * @test
     */
    public function annotationsPresentOnConstructor()
    {
        $this->assertTrue(
                lang\reflectConstructor($this->imageFormatter)->hasAnnotation('Inject')
        );
    }

    /**
     * @test
     */
    public function annotationsPresentOnUseErrorImgResourceMethod()
    {
        $method = lang\reflect($this->imageFormatter, 'useErrorImgResource');
        $this->assertTrue($method->hasAnnotation('Inject'));
        $this->assertTrue($method->getAnnotation('Inject')->isOptional());
        $this->assertTrue($method->hasAnnotation('Property'));
        $this->assertEquals(
                'stubbles.img.error',
                $method->getAnnotation('Property')->getValue()
        );
    }

    /**
     * @test
     */
    public function formatReturnsPassedImage()
    {
        $image = Image::loadFromResource('pixel.png', new ResourceLoader());
        $this->assertSame(
                $image,
                $this->imageFormatter->format($image, new Headers())
        );
    }

    /**
     * @test
     */
    public function formatReturnsImageDefinedByGivenResource()
    {
        $this->assertEquals(
                Image::loadFromResource('pixel.png', new ResourceLoader())->fileName(),
                $this->imageFormatter->format('pixel.png', new Headers())->fileName()
        );
    }

    /**
     * data provider for all image formatter methods
     *
     * @return  array
     */
    public function imageFormatterErrorMethods()
    {
        return [['formatForbiddenError', []],
                ['formatNotFoundError', []],
                ['formatMethodNotAllowedError', ['POST', ['HEAD, GET']]],
                ['formatInternalServerError', ['some error message']]
        ];
    }

    /**
     * @test
     * @dataProvider  imageFormatterErrorMethods
     */
    public function returnsImageForAllErrorMethods($method, $params)
    {
        $this->assertEquals(
                Image::loadFromResource('pixel.png', new ResourceLoader())->fileName(),
                call_user_func_array([$this->imageFormatter, $method], $params)->fileName()
        );
    }

    /**
     * @test
     * @dataProvider  imageFormatterErrorMethods
     */
    public function returnsImageForAllErrorMethodsWithDifferentImage($method, $params)
    {
        $this->imageFormatter->useErrorImgResource('error.png');
        $this->assertEquals(
                Image::loadFromResource('error.png', new ResourceLoader())->fileName(),
                call_user_func_array([$this->imageFormatter, $method], $params)->fileName()
        );
    }
}
