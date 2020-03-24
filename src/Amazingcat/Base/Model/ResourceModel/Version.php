<?php

namespace Amazingcat\Base\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

/**
 * Class Version
 * @package Amazingcat\Base\Model\ResourceModel
 */
class Version extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('amazingcat_base_versions', 'entity_id');
    }
}
