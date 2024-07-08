<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\File\FileScope\DataProviders;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Api\Data\FileScopeInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStore;
use Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStoreCategory;
use Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStoreProduct;

class File implements FileScopeDataInterface
{
    /**
     * @var FileStore
     */
    private $fileStore;

    /**
     * @var FileStoreCategory
     */
    private $fileStoreCategory;

    /**
     * @var FileStoreProduct
     */
    private $fileStoreProduct;
    /**
     * Construct
     *
     * @param FileStore $fileStore
     * @param FileStoreCategory $fileStoreCategory
     * @param FileStoreProduct $fileStoreProduct
     */
    public function __construct(
        FileStore $fileStore,
        FileStoreCategory $fileStoreCategory,
        FileStoreProduct $fileStoreProduct
    ) {
        $this->fileStore = $fileStore;
        $this->fileStoreCategory = $fileStoreCategory;
        $this->fileStoreProduct = $fileStoreProduct;
    }

    /**
     * @inheritdoc
     */
    public function execute($params)
    {
        /** @var FileInterface $file */
        $file = $params[RegistryConstants::FILE];
        $store = $params[RegistryConstants::STORE];
        $fileStoreData = [];
        if ($store) {
            $fileStoreData = $this->fileStore->getByStoreId($file->getFileId(), $store);
            foreach (RegistryConstants::USE_DEFAULT_FIELDS as $field) {
                $file->setData(
                    RegistryConstants::USE_DEFAULT_PREFIX . $field,
                    (!isset($fileStoreData[$field]) || $fileStoreData[$field] === null)
                );
            }
        }

        $defaultFileStoreData = $this->fileStore->getByStoreId($file->getFileId(), 0);
        foreach (RegistryConstants::USE_DEFAULT_FIELDS as $field) {
            if (isset($fileStoreData[$field])) {
                if ($fileStoreData[$field] === null) {
                    $file->setData($field, $defaultFileStoreData[$field]);
                } else {
                    $file->setData($field, $fileStoreData[$field]);
                }
            } else {
                $file->setData($field, $defaultFileStoreData[$field]);
            }
        }

        $fileStoreCategories = $this->fileStoreCategory->getStoreCategoryIdsByStoreId($file->getFileId(), $store);
        if (empty($fileStoreCategories)) {
            $file->setData(RegistryConstants::USE_DEFAULT_PREFIX . FileInterface::CATEGORIES, true);
            $fileStoreCategories = $this->fileStoreCategory->getStoreCategoryIdsByStoreId($file->getFileId(), 0);
        }

        if (!empty($fileStoreCategories)) {
            $categoryIds = [];
            foreach ($fileStoreCategories as $fileStoreCategory) {
                $categoryIds[] = $fileStoreCategory[FileScopeInterface::CATEGORY_ID];
            }
            $file->setData(FileInterface::CATEGORIES, $categoryIds);
        }

        $fileStoreProducts = $this->fileStoreProduct->getStoreProductIdsByStoreId($file->getFileId(), $store);
        if (empty($fileStoreProducts)) {
            $file->setData(RegistryConstants::USE_DEFAULT_PREFIX . FileInterface::PRODUCTS, true);
            $fileStoreProducts = $this->fileStoreProduct->getStoreProductIdsByStoreId($file->getFileId(), 0);
        }

        if (!empty($fileStoreProducts)) {
            $productIds = [];
            foreach ($fileStoreProducts as $fileStoreProduct) {
                $productIds[] = $fileStoreProduct[FileScopeInterface::PRODUCT_ID];
            }
            $file->setData(FileInterface::PRODUCTS, $productIds);
        }

        return $file;
    }
}
