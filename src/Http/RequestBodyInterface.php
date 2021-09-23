<?php
/**
 * Voip © 2019
 *
 */

namespace Voip\Http;

/**
 * Interface
 *
 * @package Voip
 */
interface RequestBodyInterface
{
    /**
     * Get the body of the request to send to Graph.
     *
     * @return string
     */
    public function getBody();
}
