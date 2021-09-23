<?php
/**
 * Voip © 2019
 *
 */

namespace Voip\Url;

/**
 * Interface UrlDetectionInterface
 *
 * @package Voip
 */
interface UrlDetectionInterface
{
    /**
     * Get the currently active URL.
     *
     * @return string
     */
    public function getCurrentUrl();
}
