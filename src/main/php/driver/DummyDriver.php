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
 * Dummy driver for images.
 */
class DummyDriver implements ImageDriver
{
    use ContentViaOutputBuffer;
    private ?string $lastStoredFileName = null;
    private ?GdImage $lastStoredHandle = null;
    private ?GdImage $lastDisplayedHandle = null;

    public function __construct(private ?GdImage $handle = null)
    {
        $this->handle = $handle;
    }

    /**
     * loads given image
     *
     * @throws  DriverException
     */
    public function load(string $fileName): GdImage
    {
        if (null === $this->handle) {
            throw new DriverException('The image ' . $fileName . ' seems to be broken.');
        }

        return $this->handle;
    }

    /**
     * stores given image
     */
    public function store(string $fileName, GdImage $handle): self
    {
        $this->lastStoredFileName = $fileName;
        $this->lastStoredHandle   = $handle;
        return $this;
    }

    /**
     * returns last stored file name
     */
    public function lastStoredFileName(): ?string
    {
        return $this->lastStoredFileName;
    }

    /**
     * returns last stored file name
     */
    public function lastStoredHandle(): ?GdImage
    {
        return $this->lastStoredHandle;
    }

    /**
     * displays given image (raw output to browser)
     */
    public function display(GdImage $handle): void
    {
        $this->lastDisplayedHandle = $handle;
    }

    /**
     * returns last displayed handle
     */
    public function lastDisplayedHandle(): ?GdImage
    {
        return $this->lastDisplayedHandle;
    }

    /**
     * returns file extension for image type
     */
    public function fileExtension(): string
    {
        return '.dummy';
    }

    /**
     * returns content type
     */
    public function mimeType(): string
    {
        return 'image/dummy';
    }
}
