<?php

namespace Amazingcat\Base\Model;

/**
 * Class UpdatesDataProvider
 * @package Amazingcat\Base\Model
 */
class UpdatesDataProvider implements \Amazingcat\Base\Api\UpdatesDataProviderInterface
{
    /**
     * Urls
     */
    const URL_VERSIONS = '/version';
    const URL_INFO = '/info';

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    private $dataObjectFactory;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * UpdatesDataProvider constructor.
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param string $baseUrl
     */
    public function __construct(
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        $baseUrl = ''
    ) {
        $this->dataObjectFactory = $dataObjectFactory;
        $this->baseUrl = $baseUrl;
        $this->productMetadata = $productMetadata;
    }

    /**
     * @return array
     */
    public function getVersions()
    {
        return  json_decode($this->get($this->baseUrl . static::URL_VERSIONS), true);
    }

    /**
     * @return array
     */
    public function getInfo($refId = '')
    {
        return json_decode($this->get($this->baseUrl . static::URL_INFO . (($refId) ? '/' . $refId : '')), true);
    }

    /**
     * @param string $url
     * @return bool|string
     */
    protected function get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
            'Magento-Version: ' . $this->productMetadata->getVersion(),
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $curlResult = curl_exec ($ch);

        curl_close ($ch);

        return $curlResult;
    }
}
