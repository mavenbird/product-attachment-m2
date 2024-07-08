<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\File\DataProvider;

use Mavenbird\ProductAttachment\Model\File\ResourceModel\CollectionFactory;
use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Model\Icon\GetIconForFile;

class Listing extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Mavenbird\ProductAttachment\Model\File\ResourceModel\Collection
     */
    protected $collection;

    /**
     * @var GetIconForFile
     */
    private $getIconForFile;

    /**
     * Construct
     *
     * @param CollectionFactory $collectionFactory
     * @param GetIconForFile $getIconForFile
     * @param [type] $name
     * @param [type] $primaryFieldName
     * @param [type] $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        GetIconForFile $getIconForFile,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->collection->addFileData();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->getIconForFile = $getIconForFile;
    }

    /**
     * GetData
     *
     * @return void
     */
    public function getData()
    {
        $data = parent::getData();
        if (!empty($data['items'])) {
            foreach ($data['items'] as &$item) {
                if (!empty($item[FileInterface::EXTENSION])) {
                    $item['icon_src'] = $this->getIconForFile->byFileExtension(
                        $item[FileInterface::EXTENSION]
                    );
                }
                $item[FileInterface::SIZE] = $this->getReadableFileSize((int)$item[FileInterface::SIZE]);
            }
        }

        return $data;
    }

    /**
     * GetReadableFileSize
     *
     * @param integer $bytes
     * @return void
     */
    public function getReadableFileSize($bytes = 0)
    {
        $size   = ['B', 'kB', 'MB', 'GB', 'TB'];
        $factor = (int)floor((strlen($bytes) - 1) / 3);
        if (isset($size[$factor])) {
            $bytes = sprintf("%.2f", $bytes / pow(1024, $factor)) . ' ' . $size[$factor];
        }

        return $bytes;
    }
}
