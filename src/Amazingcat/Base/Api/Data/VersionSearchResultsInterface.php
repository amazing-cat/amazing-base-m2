<?php

namespace Amazingcat\Base\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface VersionSearchResultsInterface
 * @package Amazingcat\Base\Api\Data
 * @api
 */
interface VersionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Versions list.
     *
     * @return \Amazingcat\Base\Api\Data\VersionInterface[]
     */
    public function getItems();

    /**
     * Set Versions list.
     *
     * @param  \Amazingcat\Base\Api\Data\VersionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
