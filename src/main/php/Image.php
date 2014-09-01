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
use stubbles\lang\ResourceLoader;
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
     * file name of image
     *
     * @type  string
     */
    private $fileName;
    /**
     * image type
     *
     * @type  ImageType
     */
    private $type;

    /**
     * constructor
     *
     * @param   string                   $fileName  file name of image to load
     * @param   \stubbles\img\ImageType  $type      optional defaults to ImageType::$PNG
     * @param   resource                 $handle
     * @throws  \InvalidArgumentException
     */
    public function __construct($fileName, ImageType $type = null, $handle = null)
    {
        $this->fileName = $fileName;
        $this->type    = ((null === $type) ? (ImageType::$PNG) : ($type));
        if (null !== $handle && (!is_resource($handle) || get_resource_type($handle) !== 'gd')) {
            throw new \InvalidArgumentException('Given handle is not a valid gd resource.');
        }

        $this->handle = $handle;
    }

    /**
     * loads image from resource
     *
     * @param   string                         $resource        resource uri of image to load
     * @param   \stubbles\lang\ResourceLoader  $resourceLoader  resource loader to be used
     * @param   \stubbles\img\ImageType        $type            optional  defaults to ImageType::$PNG
     * @return  \stubbles\img\Image
     * @since   3.0.0
     */
    public static function loadFromResource($resource, ResourceLoader $resourceLoader, ImageType $type = null)
    {
        return $resourceLoader->load(
                $resource,
                function($fileName) use ($type) { return self::load($fileName, $type); }
        );
    }

    /**
     * loads image from file
     *
     * @param   string                   $fileName  file name of image to load
     * @param   \stubbles\img\ImageType  $type      optional  defaults to ImageType::$PNG
     * @return  \stubbles\img\Image
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
    public function fileName()
    {
        return $this->fileName;
    }

    /**
     * returns type of image
     *
     * @return  \stubbles\img\ImageType
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * returns image handle
     *
     * @return  resource
     */
    public function handle()
    {
        return $this->handle;
    }

    /**
     * stores image under given file name
     *
     * @param   string     $fileName
     * @return  \stubbles\img\Image
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
    public function fileExtension()
    {
        return $this->type->fileExtension();
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function mimeType()
    {
        return $this->type->mimeType();
    }
}
