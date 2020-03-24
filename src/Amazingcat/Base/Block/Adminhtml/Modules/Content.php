<?php

namespace Amazingcat\Base\Block\Adminhtml\Modules;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template;

/**
 * Class Content
 * @package Amazingcat\Base\Block\Adminhtml\Modules
 */
class Content extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'Amazingcat_Base::modules.phtml';

    /**
     * @var \Amazingcat\Base\Api\VersionRepositoryInterface
     */
    protected $versionRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    private $moduleList;

    /**
     * @var array
     */
    protected $instances = [];

    /**
     * Content constructor.
     * @param \Amazingcat\Base\Api\VersionRepositoryInterface $versionRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Amazingcat\Base\Api\VersionRepositoryInterface $versionRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        Template\Context $context,
        array $data = []
    ) {
        $this->versionRepository = $versionRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->moduleList = $moduleList;

        parent::__construct($context, $data);
    }

    /**
     * @return \Amazingcat\Base\Api\Data\VersionInterface[]|\Amazingcat\Base\Model\Version[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getModules()
    {
        if (empty($this->instances)) {
            $moduleList = $this->moduleList;

            $this->instances = array_map(function (\Amazingcat\Base\Api\Data\VersionInterface $version) use ($moduleList) {
                return $version->setData(
                    'current_version',
                    $moduleList->getOne($version->getModule())['setup_version']
                );
            }, $this->versionRepository->getList(
                $this->searchCriteriaBuilder->addFilter(
                    'module',
                    $this->moduleList->getNames(),
                    'in'
                )->create()
            )->getItems());
        }

        return $this->instances;
    }

    /**
     * @param $name
     * @return string
     */
    public function formatModuleName($name)
    {
        return explode('_', $name)[1];
    }

    /**
     * @param string $version1
     * @param string $version2
     * @return string
     */
    public function getCompareVersionColor($version1, $version2)
    {
        return $this->moduleIsNew($version1, $version2) ? 'green' : 'red';
    }

    /**
     * @param string $version1
     * @param string $version2
     * @return bool
     */
    public function moduleIsNew($version1, $version2)
    {
        return (bool)version_compare($version1, $version2, '<=');
    }
}
