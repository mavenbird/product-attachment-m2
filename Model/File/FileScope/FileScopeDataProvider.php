<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\File\FileScope;

class FileScopeDataProvider implements FileScopeDataProviderInterface
{
    /**
     * @var DataProviders\FileScopeDataInterface[]
     */
    private $dataProviders;
    /**
     * Construct
     *
     * @param [type] $dataProviders
     */
    public function __construct(
        $dataProviders
    ) {
        $this->dataProviders = $dataProviders;
    }

    /**
     * @inheritdoc
     */
    public function execute($params, $dataProviderName)
    {
        if (!isset($this->dataProviders[$dataProviderName])) {
            throw new \Mavenbird\ProductAttachment\Exceptions\NoSuchDataProviderException();
        }

        return $this->dataProviders[$dataProviderName]->execute($params);
    }
}
