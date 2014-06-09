<?php
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package  stubbles\img
 */
namespace stubbles\img;
use stubbles\lang\Enum;
/**
 * Collection of img type constants.
 */
class ImageType extends Enum
{
    /**
     * png images
     *
     * @api
     * @type  ImageType
     */
    public static $PNG;
    /**
     * dummy images
     *
     * @api
     * @type  ImageType
     */
    public static $DUMMY;

    /**
     * static initializer
     */
    public static function __static()
    {
        self::$PNG   = new self('PNG', new driver\PngImageDriver());
        self::$DUMMY = new self('Dummy', new driver\DummyImageDriver());
    }

    /**
     * loads given image
     *
     * @param   string    $fileName
     * @return  resource
     */
    public function load($fileName)
    {
        return $this->value->load($fileName);
    }

    /**
     * stores given image
     *
     * @param  string    $fileName
     * @param  resource  $handle
     */
    public function store($fileName, $handle)
    {
        $this->value->store($fileName, $handle);
    }

    /**
     * displays given image (raw output to console
     *
     * @param  resource  $handle
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
    public function fileExtension()
    {
        return $this->value->fileExtension();
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function mimeType()
    {
        return $this->value->mimeType();
    }
}
ImageType::__static();
