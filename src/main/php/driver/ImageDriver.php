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
 * Driver for images.
 */
interface ImageDriver
{
    /**
     * loads given image
     *
     * @param   string    $fileName
     * @return  resource
     */
    public function load(string $fileName);

    /**
     * stores given image
     *
     * @param   string    $fileName
     * @param   resource  $handle
     * @return  \stubbles\img\driver\ImageDriver
     */
    public function store(string $fileName, $handle): self;

    /**
     * displays given image (raw output to stdout)
     *
     * @param  resource $handle
     */
    public function display($handle): void;

    /**
     * returns content of given image ready for display
     *
     * @since   6.1.0
     * @param   resource  $handle
     * @return  string
     */
    public function contentForDisplay($handle): string;

    /**
     * returns file extension for image type
     *
     * @return  string
     */
    public function fileExtension(): string;

    /**
     * returns content type
     *
     * @return  string
     */
    public function mimeType(): string;
}
