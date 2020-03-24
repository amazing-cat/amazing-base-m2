<?php

namespace Amazingcat\Base\Model\Config;

/**
 * Class Writer
 * @package Amazingcat\Base\Model\Config
 */
class Writer
{
    /**
     * Base config path
     */
    const BASE_CONFIG_PATH = 'amazingcat_base';

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    private $writer;

    /**
     * Writer constructor.
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $writer
     */
    public function __construct(\Magento\Framework\App\Config\Storage\WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param $referenceId
     * @return $this
     */
    public function setLastInfoReferenceId($referenceId)
    {
        $this->writer->save(static::BASE_CONFIG_PATH . '/info/last_ref_id', $referenceId);
        return $this;
    }
}
