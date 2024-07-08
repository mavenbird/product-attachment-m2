<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\File\FileScope\SaveProcessors;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Api\Data\FileScopeInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStore;
use Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStoreCategory;
use Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStoreProduct;
use Magento\Framework\App\RequestInterface;

class File implements FileScopeSaveProcessorInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var FileStore
     */
    private $fileStoreResource;

    /**
     * @var FileStoreCategory
     */
    private $fileStoreCategory;

    /**
     * @var FileStoreProduct
     */
    private $fileStoreProduct;

    /**
     * @var array
     */
    private $savedData;

    /**
     * @var \Mavenbird\ProductAttachment\Api\Data\FileInterface
     */
    private $file;

    /**
     * @var int
     */
    private $store;
    /**
     * Construct
     *
     * @param RequestInterface $request
     * @param FileStore $fileStoreResource
     * @param FileStoreCategory $fileStoreCategory
     * @param FileStoreProduct $fileStoreProduct
     */
    public function __construct(
        RequestInterface $request,
        FileStore $fileStoreResource,
        FileStoreCategory $fileStoreCategory,
        FileStoreProduct $fileStoreProduct
    ) {
        $this->request = $request;
        $this->fileStoreResource = $fileStoreResource;
        $this->fileStoreCategory = $fileStoreCategory;
        $this->fileStoreProduct = $fileStoreProduct;
    }

    /**
     * Execute
     *
     * @param [type] $params
     * @return void
     */
    public function execute($params)
    {
        /** @var \Mavenbird\ProductAttachment\Api\Data\FileInterface $file */
        $this->file = $params[RegistryConstants::FILE];
        $this->store = isset($params[RegistryConstants::STORE]) ? (int)$params[RegistryConstants::STORE]
            : (int)$this->request->getParam('store');
        $storeId = $this->store;

        $this->savedData = [
            FileScopeInterface::FILE_ID => $this->file->getFileId(),
            FileScopeInterface::FILENAME => $this->file->getData(FileScopeInterface::FILENAME),
            FileScopeInterface::LABEL => $this->file->getData(FileScopeInterface::LABEL),
            FileScopeInterface::IS_VISIBLE => $this->file->getData(FileScopeInterface::IS_VISIBLE),
            FileScopeInterface::CUSTOMER_GROUPS => $this->file->getData(
                FileScopeInterface::CUSTOMER_GROUPS . '_output'
            ),
            FileScopeInterface::INCLUDE_IN_ORDER => $this->file->getData(FileScopeInterface::INCLUDE_IN_ORDER),
        ];
        $fileStoreId = $this->saveFileStoreRelation();
        if (!$this->file->getData('use_default_categories')) {
            $this->saveFileCategoriesRelation($fileStoreId);
        } elseif ($storeId) {
            $this->fileStoreCategory->deleteAllByStore($this->file->getFileId(), $storeId);
        }

        if (!$this->file->getData('use_default_products')) {
            $this->saveFileProductsRelation($fileStoreId);
        } elseif ($storeId) {
            $this->fileStoreProduct->deleteAllByStore($this->file->getFileId(), $storeId);
        }
    }

    /**
     * SaveFileStoreRelation
     *
     * @return void
     */
    public function saveFileStoreRelation()
    {
        $fileId = $this->file->getFileId();
        $storeId = $this->store;
        $fileStoreId = null;

        $defaultFileStoreData = $this->fileStoreResource->getByStoreId($fileId, 0);
        $savedData = $this->savedData;

        $savedData[FileScopeInterface::STORE_ID] = 0;

        if ($storeId === 0 || !$defaultFileStoreData) {
            $fileStoreId = $this->fileStoreResource->saveFileStoreData(
                $defaultFileStoreData ? array_merge($defaultFileStoreData, $savedData) : $savedData
            );
        }

        if ($storeId) {
            $fileStoreData = $this->fileStoreResource->getByStoreId($fileId, $storeId);
            if ($fileStoreData) {
                $savedData = array_merge($fileStoreData, $savedData);
            }
            $savedData[FileScopeInterface::STORE_ID] = $storeId;
            $fileStoreId = $this->fileStoreResource->saveFileStoreData($savedData);
        }

        return $fileStoreId;
    }

    /**
     * SaveFileCategoriesRelation
     *
     * @param [type] $fileStoreId
     * @return void
     */
    public function saveFileCategoriesRelation($fileStoreId)
    {
        $fileId = $this->file->getFileId();
        $storeId = $this->store;

        $categories = $this->file->getData(FileInterface::CATEGORIES);
        if (empty($categories)) {
            $this->fileStoreCategory->deleteAllByStore($fileId, $storeId);
            if ($storeId && !$this->file->getData('use_default_categories')) {
                $this->fileStoreCategory->insertFileStoreCategoryData(
                    [
                        FileScopeInterface::FILE_ID => $this->file->getFileId(),
                        FileScopeInterface::FILE_STORE_ID => $fileStoreId,
                        FileScopeInterface::CATEGORY_ID => 0,
                        FileScopeInterface::STORE_ID => $storeId
                    ]
                );
            }
        } else {
            $currentCategories = $this->fileStoreCategory->getStoreCategoryIdsByStoreId($fileId, $storeId);
            foreach ($categories as $categoryId) {
                if (array_key_exists($categoryId, $currentCategories)) {
                    $data = [
                        FileScopeInterface::FILE_ID => $this->file->getFileId(),
                        FileScopeInterface::FILE_STORE_CATEGORY_ID =>
                            $currentCategories[$categoryId][FileScopeInterface::FILE_STORE_CATEGORY_ID],
                        FileScopeInterface::FILE_STORE_ID => $fileStoreId,
                        FileScopeInterface::STORE_ID => $storeId
                    ];
                    if ($position = $this->file->getData(FileScopeInterface::POSITION)) {
                        $data[FileScopeInterface::POSITION] = $position;
                    }
                    $this->fileStoreCategory->updateFileStoreCategoryData($data);
                    unset($currentCategories[$categoryId]);
                } else {
                    $data = [
                        FileScopeInterface::FILE_ID => $this->file->getFileId(),
                        FileScopeInterface::FILE_STORE_ID => $fileStoreId,
                        FileScopeInterface::CATEGORY_ID => (int)$categoryId,
                        FileScopeInterface::STORE_ID => $storeId
                    ];
                    if ($position = $this->file->getData(FileScopeInterface::POSITION)) {
                        $data[FileScopeInterface::POSITION] = $position;
                    }
                    $this->fileStoreCategory->insertFileStoreCategoryData($data);
                }
            }
            if (!empty($currentCategories)) {
                $categoriesToDelete = [];
                foreach ($currentCategories as $categoryStore) {
                    $categoriesToDelete[] = $categoryStore[FileScopeInterface::FILE_STORE_CATEGORY_ID];
                }
                $this->fileStoreCategory->deleteByStoreCategoryIds($categoriesToDelete);
            }
        }
    }

    /**
     * SaveFileProductsRelation
     *
     * @param [type] $fileStoreId
     * @return void
     */
    public function saveFileProductsRelation($fileStoreId)
    {
        $fileId = $this->file->getFileId();
        $storeId = $this->store;

        $products = $this->file->getData(FileInterface::PRODUCTS);
        if (empty($products)) {
            $this->fileStoreProduct->deleteAllByStore($fileId, $storeId);
            if ($storeId && !$this->file->getData('use_default_products')) {
                $this->fileStoreProduct->insertFileStoreProductData(
                    [
                        FileScopeInterface::FILE_ID => $this->file->getFileId(),
                        FileScopeInterface::FILE_STORE_ID => $fileStoreId,
                        FileScopeInterface::PRODUCT_ID => 0,
                        FileScopeInterface::STORE_ID => $storeId
                    ]
                );
            }
        } else {
            $currentProducts = $this->fileStoreProduct->getStoreProductIdsByStoreId($fileId, $storeId);
            foreach ($products as $productId) {
                if (array_key_exists($productId, $currentProducts)) {
                    $data = [
                        FileScopeInterface::FILE_ID => $this->file->getFileId(),
                        FileScopeInterface::FILE_STORE_PRODUCT_ID =>
                            $currentProducts[$productId][FileScopeInterface::FILE_STORE_PRODUCT_ID],
                        FileScopeInterface::FILE_STORE_ID => $fileStoreId,
                        FileScopeInterface::STORE_ID => $storeId
                    ];
                    if ($position = $this->file->getData(FileScopeInterface::POSITION)) {
                        $data[FileScopeInterface::POSITION] = $position;
                    }
                    $this->fileStoreProduct->updateFileStoreProductData($data);
                    unset($currentProducts[$productId]);
                } else {
                    $data = [
                        FileScopeInterface::FILE_ID => $this->file->getFileId(),
                        FileScopeInterface::FILE_STORE_ID => $fileStoreId,
                        FileScopeInterface::PRODUCT_ID => (int)$productId,
                        FileScopeInterface::STORE_ID => $storeId
                    ];
                    if ($position = $this->file->getData(FileScopeInterface::POSITION)) {
                        $data[FileScopeInterface::POSITION] = $position;
                    }
                    $this->fileStoreProduct->insertFileStoreProductData($data);
                }
            }
            if (!empty($currentProducts)) {
                $productsToDelete = [];
                foreach ($currentProducts as $productStore) {
                    $productsToDelete[] = $productStore[FileScopeInterface::FILE_STORE_PRODUCT_ID];
                }
                $this->fileStoreProduct->deleteByStoreProductIds($productsToDelete);
            }
        }
    }
}
