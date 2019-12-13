<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace stubbles\img;
use stubbles\img\driver\{DriverException, ImageDriver, JpegDriver, PngDriver};
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
     * @var  resource
     */
    private $handle;
    /**
     * file name of image
     *
     * @var  string
     */
    private $fileName;
    /**
     * image driver
     *
     * @var  ImageDriver
     */
    private $driver;
    /**
     * list of registered drivers
     *
     * @var  array<string,string>
     */
    private static $drivers = [
      'png'        => PngDriver::class,
      'image/png'  => PngDriver::class,
      'jpeg'       => JpegDriver::class,
      'image/jpeg' => JpegDriver::class,
      'jpg'        => JpegDriver::class,
    ];

    /**
     * selects driver based on mimetype of existing file of extension of filename
     *
     * @throws  DriverException
     */
    private function selectDriver(string $fileName): ImageDriver
    {
        if (\file_exists($fileName)) {
            $mimeType = @\mime_content_type($fileName);
            if (false === $mimeType) {
                $error = \error_get_last();
                $msg = (null !== $error) ? $error['message'] : 'an unknown error occurred';
                throw new DriverException('Could not detect mimetype to select driver: ' . $msg);
            }

            if (isset(self::$drivers[$mimeType])) {
               return new self::$drivers[$mimeType]();
            }

            throw new DriverException('No driver available for mimetype ' . $mimeType);
        }

        $extension = \array_values(\array_slice(\explode('.', $fileName), -1))[0];
        if (isset(self::$drivers[$extension])) {
            return new self::$drivers[$extension]();
        }

        return new PngDriver();
    }

    /**
     * constructor
     *
     * In case the file exists the driver is selected based on the mimetype of the file.
     * In case no driver is available for the detected mimetype or detection fails a
     * DriverException is thrown.
     *
     * If the file doesn't exist the driver is selected based on the extension of the
     * filename. In case no such extension is present or no driver is known for the
     * extension it falls back to png.
     *
     * Driver selection can always be overruled by passing a driver explicitly.
     *
     * @param   string                            $fileName  file name of image to load
     * @param   \stubbles\img\driver\ImageDriver  $driver    optional
     * @param   resource                          $handle
     * @throws  \InvalidArgumentException
     */
    public function __construct(string $fileName, ImageDriver $driver = null, $handle = null)
    {
        $this->fileName = $fileName;
        $this->driver   = (null === $driver ? self::selectDriver($fileName) : $driver);
        if (null !== $handle && (!\is_resource($handle) || \get_resource_type($handle) !== 'gd')) {
            throw new \InvalidArgumentException('Given handle is not a valid gd resource.');
        }

        $this->handle = $handle;
    }

    /**
     * loads image from file
     *
     * Driver is selected based on the mimetype of the file. In case no driver is available
     * for the detected mimetype or detection fails a DriverException is thrown.
     *
     * Driver selection can always be overruled by passing a driver explicitly.
     *
     * @param   string                            $fileName  file name of image to load
     * @param   \stubbles\img\driver\ImageDriver  $driver    optional
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
