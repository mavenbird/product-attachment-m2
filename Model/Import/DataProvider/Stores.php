<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Import\DataProvider;

use Mavenbird\ProductAttachment\Model\Import\Import;
use Mavenbird\ProductAttachment\Model\Import\Import as ImportModel;
use Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportCollectionFactory;

class Stores extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * Construct
     *
     * @param ImportCollectionFactory $importCollectionFactory
     * @param [type] $name
     * @param [type] $primaryFieldName
     * @param [type] $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        ImportCollectionFactory $importCollectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $importCollectionFactory->create();
    }

    /**
     * GetData
     *
     * @return void
     */
    public function getData()
    {
        $data = parent::getData();
        $data['items'][0][ImportModel::STORE_IDS] = explode(',', $data['items'][0][ImportModel::STORE_IDS]);
        $data[$data['items'][0][ImportModel::IMPORT_ID]] = $data['items'][0];

        return $data;
    }
}
