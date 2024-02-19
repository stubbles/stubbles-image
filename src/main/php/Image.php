<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace stubbles\img;

use GdImage;
use RuntimeException;
use stubbles\img\driver\{DriverException, ImageDriver, JpegDriver, PngDriver};
/**
 * Container for an image.
 *
 * @api
 */
class Image
{
    /**
     * list of registered drivers
     *
     * @var  array<string,string>
     */
    private static array $drivers = [
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
    private static function selectDriver(string $fileName): ImageDriver
    {
        if (file_exists($fileName)) {
            $mimeType = @mime_content_type($fileName);
            if (false === $mimeType) {
                $error = error_get_last();
                $msg = (null !== $error) ? $error['message'] : 'an unknown error occurred';
                throw new DriverException('Could not detect mimetype to select driver: ' . $msg);
            }

            if (isset(self::$drivers[$mimeType])) {
               return new self::$drivers[$mimeType]();
            }

            throw new DriverException('No driver available for mimetype ' . $mimeType);
        }

        $extension = array_values(array_slice(explode('.', $fileName), -1))[0];
        if (isset(self::$drivers[$extension])) {
            return new self::$drivers[$extension]();
        }

        return new PngDriver();
    }

    private function __construct(
        private string $fileName,
        private ImageDriver $driver,
        private GdImage $handle
    ) {
        $this->fileName = $fileName;
        $this->driver   = $driver;
        $this->handle   = $handle;
    }

    /**
     * loads image from file
     *
     * Driver is selected based on the mimetype of the file. In case no driver is available
     * for the detected mimetype or detection fails a DriverException is thrown.
     *
     * Driver selection can always be overruled by passing a driver explicitly.
     *
     * @throws  DriverException  in case no driver for given image available
     */
    public static function load(string $fileName, ImageDriver $driver = null): self
    {
        if (null === $driver) {
            $driver = self::selectDriver($fileName);
        }

        return new self($fileName, $driver, $driver->load($fileName));
    }

    /**
     * Creates a new image with given dimensions.
     *
     * Driver is selected based on the extension of the file. In case no driver is available
     * for the detected extensions it falls back to the png driver.
     *
     * Driver selection can always be overruled by passing a driver explicitly.
     *
     * @param   string  $fileName  file name of image to load
     * @param   int     $width     width of the new image
     * @param   int     $height    height of the new image
     * @throws  RuntimeException
     */
    public static function create(string $fileName, int $width, int $height, ImageDriver $driver = null): self
    {
        if (null === $driver) {
          $driver = self::selectDriver($fileName);
        }

        $handle = \imagecreatetruecolor($width, $height);
        if (false === $handle) {
            $error = \error_get_last();
            $msg = (null !== $error) ? $error['message'] : 'an unknown error occurred';
            throw new RuntimeException('Could not create image: ' . $msg);
        }

        return new self($fileName, $driver, $handle);
    }

    /**
     * returns name of image
     */
    public function fileName(): string
    {
        return $this->fileName;
    }

    /**
     * returns image handle
     */
    public function handle(): GdImage
    {
        return $this->handle;
    }

    /**
     * stores image under given file name
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
     */
    public function contentForDisplay(): string
    {
        return $this->driver->contentForDisplay($this->handle);
    }

    /**
     * returns default extension for this type of image (e.g. '.png')
     */
    public function fileExtension(): string
    {
        return $this->driver->fileExtension();
    }

    /**
     * returns content type
     */
    public function mimeType(): string
    {
        return $this->driver->mimeType();
    }
}
