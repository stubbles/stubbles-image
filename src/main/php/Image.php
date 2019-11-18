<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace stubbles\img;
use stubbles\img\driver\ImageDriver;
use stubbles\img\driver\PngDriver;
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
     * image driver
     *
     * @type  ImageDriver
     */
    private $driver;

    /**
     * constructor
     *
     * @param   string                            $fileName  file name of image to load
     * @param   \stubbles\img\driver\ImageDriver  $driver    optional defaults to PngDriver
     * @param   resource                          $handle
     * @throws  \InvalidArgumentException
     */
    public function __construct(string $fileName, ImageDriver $driver = null, $handle = null)
    {
        $this->fileName = $fileName;
        $this->driver  = ((null === $driver) ? (new PngDriver()) : ($driver));
        if (null !== $handle && (!is_resource($handle) || get_resource_type($handle) !== 'gd')) {
            throw new \InvalidArgumentException('Given handle is not a valid gd resource.');
        }

        $this->handle = $handle;
    }

    /**
     * loads image from file
     *
     * @param   string                            $fileName  file name of image to load
     * @param   \stubbles\img\driver\ImageDriver  $driver    optional  defaults to PngDriver
     * @return  \stubbles\img\Image
     */
    public static function load(string $fileName, ImageDriver $driver = null): self
    {
        $self = new self($fileName, $driver);
        $self->handle = $self->driver->load($fileName);
        return $self;
    }

    /**
     * returns name of image
     *
     * @return  string
     */
    public function fileName(): string
    {
        return $this->fileName;
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
    public function store(string $fileName): self
    {
        $this->driver->store($fileName, $this->handle);
        return $this;
    }

    /**
     * displays image
     */
    public function display(): void
    {
        $this->driver->display($this->handle);
    }

    /**
     * returns image content for display
     *
     * This can be used if raw output to stdout via display() is not useful.
     *
     * @since   6.1.0
     * @return  string
     */
    public function contentForDisplay(): string
    {
        return $this->driver->contentForDisplay($this->handle);
    }

    /**
     * returns default extension for this type of image (e.g. '.png')
     *
     * @return  string
     */
    public function fileExtension(): string
    {
        return $this->driver->fileExtension();
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function mimeType(): string
    {
        return $this->driver->mimeType();
    }
}
