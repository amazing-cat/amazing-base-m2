<?php

namespace Amazingcat\Base\Model\Command;

use Amazingcat\Base\Model\Config;

/**
 * Class CheckUpdates
 * @package Amazingcat\Base\Model\Command
 */
class CheckUpdates
{
    /**
     * @var Config\DataProvider
     */
    protected $configProvider;

    /**
     * @var \Amazingcat\Base\Api\UpdatesDataProviderInterface
     */
    protected $updatesDataProvider;

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    private $moduleList;

    /**
     * @var \Amazingcat\Base\Model\ResourceModel\Version\Collection
     */
    protected $versionCollection;

    /**
     * @var \Amazingcat\Base\Api\Data\VersionInterfaceFactory
     */
    protected $versionFactory;

    /**
     * @var \Amazingcat\Base\Api\VersionRepositoryInterface
     */
    private $versionRepository;

    /**
     * @var \Magento\Framework\Notification\NotifierInterface
     */
    private $notifier;

    /**
     * @var Config\Writer
     */
    protected $configWriter;

    /**
     * CheckUpdates constructor.
     * @param Config\DataProvider $configProvider
     * @param \Amazingcat\Base\Api\UpdatesDataProviderInterface $updatesDataProvider
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param \Amazingcat\Base\Model\ResourceModel\Version\Collection $versionCollection
     * @param \Amazingcat\Base\Api\Data\VersionInterfaceFactory $versionFactory
     * @param \Amazingcat\Base\Api\VersionRepositoryInterface $versionRepository
     * @param \Magento\Framework\Notification\NotifierInterface $notifier
     * @param Config\Writer $configWriter
     */
    public function __construct(
        \Amazingcat\Base\Model\Config\DataProvider $configProvider,
        \Amazingcat\Base\Api\UpdatesDataProviderInterface $updatesDataProvider,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Amazingcat\Base\Model\ResourceModel\Version\Collection $versionCollection,
        \Amazingcat\Base\Api\Data\VersionInterfaceFactory $versionFactory,
        \Amazingcat\Base\Api\VersionRepositoryInterface $versionRepository,
        \Magento\Framework\Notification\NotifierInterface $notifier,
        \Amazingcat\Base\Model\Config\Writer $configWriter
    ) {
        $this->configProvider = $configProvider;
        $this->updatesDataProvider = $updatesDataProvider;
        $this->moduleList = $moduleList;
        $this->versionCollection = $versionCollection;
        $this->versionFactory = $versionFactory;
        $this->versionRepository = $versionRepository;
        $this->notifier = $notifier;
        $this->configWriter = $configWriter;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function run()
    {
        # get info only for existing modules
        $modulesData = array_intersect_key(
            $this->updatesDataProvider->getVersions(),
            $this->moduleList->getAll()
        );

        # update info
        foreach ($modulesData as $moduleName => $moduleData) {
            $this->versionCollection->clear();

            /** @var \Amazingcat\Base\Api\Data\VersionInterface|\Amazingcat\Base\Model\Version $version */
            $version = $this->versionCollection->addFieldToFilter('module', $moduleName)->getFirstItem();

            # update data
            $version->setModule($moduleName);
            $version->setVersion($moduleData['v']);
            $version->setReleaseNotes($moduleData['rn']);

            # save version
            $this->versionRepository->save($version);
        }

        if ($this->configProvider->notificationsEnabled()) {
            # get info updates
            $lastRefId = $this->configProvider->getLastInfoReferenceId() ?? '';
            $info = $this->updatesDataProvider->getInfo($lastRefId);

            foreach ($info as $item) {
                $this->notifier->addNotice($item['t'], $item['d'], $item['l']);
            }

            if (count($info)) {
                $this->configWriter->setLastInfoReferenceId($info[0]['rid'] ?? '');
            }
        }
    }
}
