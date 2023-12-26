<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace stubbles\img\driver;

use GdImage;

/**
 * Driver for jpg images.
 *
 * @since  6.2.0
 */
class JpegDriver implements ImageDriver
{
    use ContentViaOutputBuffer;

    /**
     * loads given image
     *
     * @throws  DriverException
     */
    public function load(string $fileName): GdImage
    {
        if (!file_exists($fileName)) {
            throw new DriverException('The image ' . $fileName . ' could not be found');
        }

        $handle = @imagecreatefromjpeg($fileName);
        if (false === $handle) {
            $error = error_get_last();
            $msg = (null !== $error) ? $error['message'] : 'an unknown error occurred';
            throw new DriverException(str_replace('imagecreatefromjpeg(): ', '', $msg));
        }

        return $handle;
    }

    /**
     * stores given image
     *
     * @throws  DriverException
     */
    public function store(string $fileName, GdImage $handle): ImageDriver
    {
        if (!@imagejpeg($handle, $fileName)) {
            $error = error_get_last();
            $msg = (null !== $error) ? $error['message'] : 'an unknown error occurred';
            throw new DriverException(
                'Could not save "' . $fileName . '": '
                . str_replace('imagejpeg('.$fileName.'): ', '', $msg)
            );
        }

        return $this;
    }

    /**
     * displays given image (raw output to stdout)
     */
    public function display(GdImage $handle): void
    {
        imagejpeg($handle);
    }

    /**
     * returns file extension for image type
     */
    public function fileExtension(): string
    {
        return '.jpeg';
    }

    /**
     * returns content type
     */
    public function mimeType(): string
    {
        return 'image/jpeg';
    }
}