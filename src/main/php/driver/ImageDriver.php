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
 * Driver for images.
 */
interface ImageDriver
{
    /**
     * loads given image
     */
    public function load(string $fileName): GdImage;

    /**
     * stores given image
     */
    public function store(string $fileName, GdImage $handle): self;

    /**
     * displays given image (raw output to stdout)
     */
    public function display(GdImage $handle): void;

    /**
     * returns content of given image ready for display
     *
     * @since   6.1.0
     */
    public function contentForDisplay(GdImage $handle): string;

    /**
     * returns file extension for image type
     */
    public function fileExtension(): string;

    /**
     * returns content type
     */
    public function mimeType(): string;
}
