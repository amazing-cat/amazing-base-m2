<?php

namespace Amazingcat\Base\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

use Amazingcat\Base\Api\VersionRepositoryInterface;

use Amazingcat\Base\Api\Data\VersionInterface;
use Amazingcat\Base\Api\Data\VersionInterfaceFactory;
use Amazingcat\Base\Api\Data\VersionSearchResultsInterfaceFactory;
use Amazingcat\Base\Model\ResourceModel\Version as ResourceVersion;
use Amazingcat\Base\Model\ResourceModel\Version\Collection;
use Amazingcat\Base\Model\ResourceModel\Version\CollectionFactory as VersionCollectionFactory;

/**
 * Class BrandsRepository
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class VersionsRepository implements VersionRepositoryInterface
{
    /**
     * @var array
     */
    public $instances = [];

    /**
     * @var ResourceVersion
     */
    public $resource;

    /**
     * @var StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var VersionCollectionFactory
     */
    public $versionCollectionFactory;

    /**
     * @var VersionSearchResultsInterfaceFactory
     */
    public $searchResultsFactory;

    /**
     * @var VersionInterfaceFactory
     */
    public $versionInterfaceFactory;

    /**
     * @var DataObjectHelper
     */
    public $dataObjectHelper;

    /**
     * VersionsRepository constructor.
     * @param ResourceVersion $resource
     * @param StoreManagerInterface $storeManager
     * @param VersionCollectionFactory $versionCollectionFactory
     * @param VersionSearchResultsInterfaceFactory $versionSearchResultsInterfaceeFactory
     * @param VersionInterfaceFactory $versionInterfaceFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ResourceVersion $resource,
        StoreManagerInterface $storeManager,
        VersionCollectionFactory $versionCollectionFactory,
        VersionSearchResultsInterfaceFactory $versionSearchResultsInterfaceeFactory,
        VersionInterfaceFactory $versionInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource                 = $resource;
        $this->storeManager             = $storeManager;
        $this->versionCollectionFactory = $versionCollectionFactory;
        $this->searchResultsFactory     = $versionSearchResultsInterfaceeFactory;
        $this->versionInterfaceFactory  = $versionInterfaceFactory;
        $this->dataObjectHelper         = $dataObjectHelper;
    }

    /**
     * @param VersionInterface $version
     * @return VersionInterface|\Magento\Framework\Model\AbstractModel
     * @throws CouldNotSaveException
     */
    public function save(VersionInterface $version)
    {
        /**
         * @var VersionInterface|\Magento\Framework\Model\AbstractModel $brand
         */
        try {
            $this->resource->save($version);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the version: %1',
                    $exception->getMessage()
                )
            );
        }
        return $version;
    }

    /**
     * @param int $verionId
     * @return VersionInterface|mixed
     * @throws NoSuchEntityException
     */
    public function getById($verionId)
    {
        if (!isset($this->instances[$verionId])) {
            /**
             * @var \Mage360\Brands\Api\Data\BrandsInterface|\Magento\Framework\Model\AbstractModel $brand
             */
            $verion = $this->versionInterfaceFactory->create();
            $this->resource->load($verion, $verionId);

            if (!$brand->getId()) {
                throw new NoSuchEntityException(__('Requested version doesn\'t exist'));
            }
            $this->instances[$verionId] = $verion;
        }

        return $this->instances[$verionId];
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Amazingcat\Base\Api\Data\VersionSearchResultsInterface
     * @throws \Magento\Framework\Exception\InputException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /**
         * @var \Amazingcat\Base\Api\Data\VersionSearchResultsInterface $searchResults
         */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /**
         * @var \Amazingcat\Base\Model\ResourceModel\Version\Collection $collection
         */
        $collection = $this->versionCollectionFactory->create();

        //Add filters from root filter group to the collection
        /**
         * @var FilterGroup $group
         */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();

        /**
         * @var SortOrder $sortOrder
         */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        } else {
            // set a default sorting order since this method is used constantly in many
            // different blocks
            $field = 'entity_id';
            $collection->addOrder($field, 'ASC');
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        /**
         * @var \Amazingcat\Base\Api\Data\VersionInterface[] $brands
         */
        $brands = [];
        /**
         * @var \Amazingcat\Base\Model\ResourceModel\Version $brand
         */
        foreach ($collection as $brand) {
            /**
             * @var \Amazingcat\Base\Api\Data\VersionInterface $brandDataObject
             */
            $brandDataObject = $this->versionInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray($brandDataObject, $brand->getData(), VersionInterface::class);
            $brands[] = $brandDataObject;
        }
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($brands);
    }

    /**
     * @param VersionInterface $version
     * @return bool
     * @throws CouldNotSaveException
     * @throws StateException
     */
    public function delete(VersionInterface $version)
    {
        $id = $version->getEntityId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($version);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to remove Version %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * Delete Brand by ID.
     *
     * @param  int $brandId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($brandId)
    {
        $brand = $this->getById($brandId);
        return $this->delete($brand);
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param  FilterGroup $filterGroup
     * @param  Collection  $collection
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     */
    public function addFilterGroupToCollection(FilterGroup $filterGroup, Collection $collection)
    {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $filter->getField();
            $conditions[] = [$condition => $filter->getValue()];
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
        return $this;
    }
}
