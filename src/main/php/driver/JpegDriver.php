<?php
declare(strict_types=1);
/**
 * This file is part of stubbles.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace stubbles\img\driver;
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
     * @param   string    $fileName
     * @return  resource
     * @throws  \stubbles\img\driver\DriverException
     */
    public function load(string $fileName)
    {
        if (!file_exists($fileName)) {
            throw new DriverException('The image ' . $fileName . ' could not be found');
        }

        $handle = @imagecreatefromjpeg($fileName);
        if (empty($handle)) {
            $error = \error_get_last();
            throw new DriverException(str_replace('imagecreatefromjpeg(): ', '', $error['message']));
        }

        return $handle;
    }

    /**
     * stores given image
     *
     * @param   string    $fileName
     * @param   resource  $handle
     * @return  \stubbles\img\driver\JpegDriver
     * @throws  \stubbles\img\driver\DriverException
     */
    public function store(string $fileName, $handle): ImageDriver
    {
        if (!@imagejpeg($handle, $fileName)) {
            $error = \error_get_last();
            throw new DriverException(
                'Could not save \'' . $fileName . '\': '
                . str_replace('imagejpeg('.$fileName.'): ', '', $error['message'])
            );
        }

        return $this;
    }

    /**
     * displays given image (raw output to stdout)
     *
     * @param  resource  $handle
     */
    public function display($handle): void
    {
        imagejpeg($handle);
    }

    /**
     * returns file extension for image type
     *
     * @return  string
     */
    public function fileExtension(): string
    {
        return '.jpeg';
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function mimeType(): string
    {
        return 'image/jpeg';
    }
}