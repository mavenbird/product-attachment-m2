<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\File\FileScope;

interface SaveFileScopeInterface
{
    /**
     * Execute
     *
     * @param [type] $params
     * @param [type] $saveProcessorName
     * @return void
     */
    public function execute($params, $saveProcessorName);
}
