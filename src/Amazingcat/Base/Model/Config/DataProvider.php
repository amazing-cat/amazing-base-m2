<?php

namespace Amazingcat\Base\Model\Config;

/**
 * Class DataProvider
 * @package Amazingcat\Base\Model\Config
 */
class DataProvider
{
    /**
     * Base config path
     */
    const BASE_CONFIG_PATH = 'amazingcat_base';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    private $cacheTypeList;

    /**
     * DataProvider constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @return bool
     */
    public function notificationsEnabled()
    {
        $this->cacheTypeList->cleanType(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
        $this->cacheTypeList->cleanType(\Magento\PageCache\Model\Cache\Type::TYPE_IDENTIFIER);

        return !((bool)$this->scopeConfig->getValue(static::BASE_CONFIG_PATH . '/notifications/disable'));
    }

    /**
     * @return string
     */
    public function getLastInfoReferenceId()
    {
        $this->cacheTypeList->cleanType(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
        $this->cacheTypeList->cleanType(\Magento\PageCache\Model\Cache\Type::TYPE_IDENTIFIER);

        return $this->scopeConfig->getValue(static::BASE_CONFIG_PATH . '/info/last_ref_id');
    }
}
