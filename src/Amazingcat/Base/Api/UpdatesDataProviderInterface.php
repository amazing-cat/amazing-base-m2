<?php

namespace Amazingcat\Base\Api;

/**
 * Interface UpdatesDataProviderInterface
 * @package Amazingcat\Base\Api
 * @api
 */
interface UpdatesDataProviderInterface
{
    /**
     * @return array
     */
    public function getVersions();

    /**
     * @return array
     */
    public function getInfo($refId = '');
}
