<?php
/**
 * Collection of img type constants.
 *
 * @author   Frank Kleine <frank.kleine@1und1.de>
 * @package  stubforge_image
 * @version  $Id: stubImageType.php 1919 2008-11-07 15:08:56Z mikey $
 */
stubClassLoader::load('net::stubbles::lang::stubEnum',
                      'net::stubforge::image::driver::stubImageDriver',
                      'net::stubforge::image::driver::stubImagePngDriver'
);
/**
 * Collection of img type constants.
 *
 * @package  stubforge_image
 */
class stubImageType extends stubEnum implements stubImageDriver
{
    /**
     * png images
     *
     * @var  stubImageType
     */
    public static $PNG;
    /**
     * dummy images
     *
     * @var  stubImageType
     */
    public static $DUMMY;
    /**
     * the concrete image driver instance
     *
     * @var  stubImageDriver
     */
    protected $value;

    /**
     * static initializer
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        self::$PNG = new self('PNG', new stubImagePngDriver());
    }
    // @codeCoverageIgnoreEnd

    /**
     * enabled dummy image type
     *
     * @param  resource<gd>  $handle  optional
     */
    public static function enableDummy($handle = null)
    {
        stubClassLoader::load('net::stubforge::image::driver::stubImageDummyDriver');
        self::$DUMMY = new self('Dummy', new stubImageDummyDriver($handle));
    }

    /**
     * loads given image
     *
     * @param   string        $fileName
     * @return  resource<gd>
     */
    public function load($fileName)
    {
        return $this->value->load($fileName);
    }

    /**
     * stores given image
     *
     * @param  string        $fileName
     * @param  resource<gd>  $handle
     */
    public function store($fileName, $handle)
    {
        $this->value->store($fileName, $handle);
    }

    /**
     * displays given image (raw output to console
     *
     * @param  resource<gd>  $handle
     */
    public function display($handle)
    {
        $this->value->display($handle);
    }

    /**
     * returns file extension for image type
     *
     * @return  string
     */
    public function getExtension()
    {
        return $this->value->getExtension();
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function getContentType()
    {
        return $this->value->getContentType();
    }
}
?>