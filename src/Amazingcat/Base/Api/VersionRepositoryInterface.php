<?php

namespace Amazingcat\Base\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface VersionRepositoryInterface
 * @package Amazingcat\Base\Api
 * @api
 */
interface VersionRepositoryInterface
{
    /**
     * Save page.
     *
     * @param  Data\VersionInterface $brands
     * @return Data\VersionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\VersionInterface $version);

    /**
     * Retrieve Store.
     *
     * @param  int $versionId
     * @return Data\VersionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($versionId);

    /**
     * Retrieve pages matching the specified criteria.
     *
     * @param  SearchCriteriaInterface $searchCriteria
     * @return \Amazingcat\Base\Api\Data\VersionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete store.
     *
     * @param  Data\VersionInterface $brand
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\VersionInterface $version);

    /**
     * Delete Store by ID.
     *
     * @param  int $versionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($versionId);
}
