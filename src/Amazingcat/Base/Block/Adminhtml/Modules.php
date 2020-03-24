<?php

namespace Amazingcat\Base\Block\Adminhtml;

/**
 * Class Modules
 * @package Amazingcat\Base\Block\Adminhtml
 */
class Modules extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function _getHeaderTitleHtml($element)
    {
        return $this->getLayout()->createBlock(\Amazingcat\Base\Block\Adminhtml\Modules\Content::class)->toHtml();
    }
}
