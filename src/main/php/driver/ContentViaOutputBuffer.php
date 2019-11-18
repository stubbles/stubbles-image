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
 * Trait for returning image content using output buffering.
 *
 * @since  6.1.0
 */
trait ContentViaOutputBuffer
{
    /**
     * returns content of given image ready for display
     *
     * @param   resource  $handle
     * @return  string
     * @throws  DriverException
     */
    public function contentForDisplay($handle): string
    {
        // must use output buffering
        // PHP's image*() functions write directly to stdout
        ob_start();
        $this->display($handle);
        $result = ob_get_contents();
        ob_end_clean();
        if (false === $result) {
            throw new DriverException('Failure with output buffering, could not retrieve image content.');
        }

        return $result;
    }

    /**
     * displays given image (raw output to stdout)
     *
     * @param  resource $handle
     */
    public abstract function display($handle): void;
}