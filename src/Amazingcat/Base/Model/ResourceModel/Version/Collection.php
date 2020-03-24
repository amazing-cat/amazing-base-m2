<?php

namespace Amazingcat\Base\Model\ResourceModel\Version;

/**
 * Class Collection
 * @package Amazingcat\Base\Model\ResourceModel\Version
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource collection initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            \Amazingcat\Base\Model\Version::class,
            \Amazingcat\Base\Model\ResourceModel\Version::class
        );
    }
}
