<?php
/**
 * Container for an image.
 *
 * @author   Frank Kleine <mikey@stubbles.net>
 * @package  stubforge_image
 * @version  $Id: stubImage.php 1919 2008-11-07 15:08:56Z mikey $
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubforge::image::stubImageType'
);
/**
 * Container for an image.
 *
 * @package  stubforge_image
 */
class stubImage extends stubBaseObject
{
    /**
     * image handle
     *
     * @var  resource<gd>
     */
    protected $handle;
    /**
     * name of image
     *
     * @var  string
     */
    protected $name;
    /**
     * image type
     *
     * @var  ImageType
     */
    protected $type;

    /**
     * constructor
     *
     * @param   string         $name
     * @param   stubImageType  $type    optional  defaults to ImageType::$PNG
     * @param   resource<gd>   $handle  optional
     * @throws  stubIllegalArgumentException
     */
    public function __construct($name, stubImageType $type = null, $handle = null)
    {
        $this->name   = $name;
        $this->type   = ((null === $type) ? (stubImageType::$PNG) : ($type));
        if (null !== $handle && (is_resource($handle) === false || get_resource_type($handle) !== 'gd')) {
            throw new stubIllegalArgumentException('Given handle is not a valid gd resource.');
        }
        
        $this->handle = $handle;
    }

    /**
     * loads image from file
     *
     * @param   string         $fileName
     * @param   stubImageType  $type      optional  defaults to ImageType::$PNG
     * @return  Image
     */
    public static function load($fileName, stubImageType $type = null)
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
     * @return  stubImageType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * returns image handle
     *
     * @return  resource<gd>
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
        return $this->type->getExtension();
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function getContentType()
    {
        return $this->type->getContentType();
    }
}
?>