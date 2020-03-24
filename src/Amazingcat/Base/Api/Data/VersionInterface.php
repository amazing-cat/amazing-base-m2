<?php

namespace Amazingcat\Base\Api\Data;

/**
 * Interface VersionInterface
 * @package Amazingcat\Base\Api\Data
 * @api
 */
interface VersionInterface
{
    /**
     * @return mixed
     */
    public function getEntityId();

    /**
     * @param $entityId
     * @return static
     */
    public function setEntityId($entityId);

    /**
     * @return mixed
     */
    public function getModule();

    /**
     * @param $moduleName
     * @return mixed
     */
    public function setModule($moduleName);

    /**
     * @return mixed
     */
    public function getVersion();

    /**
     * @param $moduleVersion
     * @return mixed
     */
    public function setVersion($moduleVersion);

    /**
     * @return mixed
     */
    public function getReleaseNotes();

    /**
     * @param $moduleVersion
     * @return mixed
     */
    public function setReleaseNotes($releaseNotes);
}
