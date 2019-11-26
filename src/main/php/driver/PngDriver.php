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
 * Driver for png images.
 */
class PngDriver implements ImageDriver
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

        $handle = @imagecreatefrompng($fileName);
        if (empty($handle)) {
            $error = \error_get_last();
            throw new DriverException(str_replace('imagecreatefrompng(): ', '', $error['message']));
        }

        return $handle;
    }

    /**
     * stores given image
     *
     * @param   string    $fileName
     * @param   resource  $handle
     * @return  \stubbles\img\driver\PngDriver
     * @throws  \stubbles\img\driver\DriverException
     */
    public function store(string $fileName, $handle): ImageDriver
    {
        if (!@imagepng($handle, $fileName)) {
            $error = \error_get_last();
            throw new DriverException(
                'Could not save \'' . $fileName . '\': '
                . str_replace('imagepng('.$fileName.'): ', '', $error['message'])
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
        imagepng($handle);
    }

    /**
     * returns file extension for image type
     *
     * @return  string
     */
    public function fileExtension(): string
    {
        return '.png';
    }

    /**
     * returns content type
     *
     * @return  string
     */
    public function mimeType(): string
    {
        return 'image/png';
    }
}
