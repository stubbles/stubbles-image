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
use stubbles\lang\exception\IllegalArgumentException;
/**
 * Container for an image.
 *
 * @api
 */
class Image
{
    /**
     * image handle
     *
     * @type  resource
     */
    private $handle;
    /**
     * name of image
     *
     * @type  string
     */
    private $name;
    /**
     * image type
     *
     * @type  ImageType
     */
    private $type;

    /**
     * constructor
     *
     * @param   string     $name
     * @param   ImageType  $type    defaults to ImageType::$PNG
     * @param   resource   $handle
     * @throws  IllegalArgumentException
     */
    public function __construct($name, ImageType $type = null, $handle = null)
    {
        $this->name   = $name;
        $this->type   = ((null === $type) ? (ImageType::$PNG) : ($type));
        if (null !== $handle && (is_resource($handle) === false || get_resource_type($handle) !== 'gd')) {
            throw new IllegalArgumentException('Given handle is not a valid gd resource.');
        }

        $this->handle = $handle;
    }

    /**
     * loads image from file
     *
     * @param   string     $fileName
     * @param   ImageType  $type      defaults to ImageType::$PNG
     * @return  Image
     */
    public static function load($fileName, ImageType $type = null)
    {
        $self = new self($fileName, $type);
        $self->handle = $self->type->load($fileName);
        return $self;
    }

    /**
     * returns name of image
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * returns type of image
     *
     * @return  ImageType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * returns image handle
     *
     * @return  resource
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * stores image under given file name
     *
     * @param   string     $fileName
     * @return  stubImage
     */
    public function store($fileName)
    {
        $this->type->store($fileName, $this->handle);
        return $this;
    }

    /**
     * displays image
     */
    public function display()
    {
        $this->type->display($this->handle);
    }

    /**
     * returns default extension for this type of image (e.g. '.png')
     *
     * @return  string
     */
    public function getExtension()
    {
        return $this->type->fileExtension();
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function getContentType()
    {
        return $this->type->mimeType();
    }
}
