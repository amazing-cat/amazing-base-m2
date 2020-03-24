<?php

namespace Amazingcat\Base\Model;

use Amazingcat\Base\Api\Data\VersionInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Version
 * @package Amazingcat\Base\Model
 */
class Version extends AbstractModel implements VersionInterface
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'amazingcat_base_versions_events';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Version::class);
    }

    /**
     * @inheritdoc
     */
    public function getEntityId()
    {
        return $this->getData('entity_id');
    }

    /**
     * @inheritdoc
     */
    public function setEntityId($entityId)
    {
        $this->setData('entity_id', $entityId);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->getData('module');
    }

    /**
     * @param $moduleName
     * @return mixed
     */
    public function setModule($moduleName)
    {
        $this->setData('module', $moduleName);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->getData('version');
    }

    /**
     * @param $moduleVersion
     * @return mixed
     */
    public function setVersion($moduleVersion)
    {
        $this->setData('version', $moduleVersion);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReleaseNotes()
    {
        return $this->getData('release_notes');
    }

    /**
     * @param $moduleVersion
     * @return mixed
     */
    public function setReleaseNotes($releaseNotes)
    {
        $this->setData('release_notes', $releaseNotes);
        return $this;
    }
}
