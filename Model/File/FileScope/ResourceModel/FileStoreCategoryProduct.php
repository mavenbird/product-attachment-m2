<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel;

use Mavenbird\ProductAttachment\Api\Data\FileScopeInterface;
use Mavenbird\ProductAttachment\Setup\Operation\CreateFileScopeTables;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class FileStoreCategoryProduct extends AbstractDb
{
    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            CreateFileScopeTables::FILE_STORE_CATEGORY_PRODUCT_TABLE_NAME,
            FileScopeInterface::FILE_STORE_CATEGORY_PRODUCT_ID
        );
    }

    /**
     * GetFilesProductCategoryData
     *
     * @param [type] $fileIds
     * @param [type] $productId
     * @param [type] $categoryId
     * @param [type] $store
     * @return void
     */
    public function getFilesProductCategoryData($fileIds, $productId, $categoryId, $store)
    {
        $select = $this->getConnection()->select()->from(
            ['fspc' => $this->getMainTable()]
        )
            ->where('fspc.' . FileScopeInterface::FILE_ID . ' IN (?)', $fileIds)
            ->where('fspc.' . FileScopeInterface::PRODUCT_ID . ' = ?', $productId)
            ->where('fspc.' . FileScopeInterface::CATEGORY_ID . ' = ?', $categoryId)
            ->where('fspc.' . FileScopeInterface::STORE_ID . ' = ?', $store);

        if ($result = $this->getConnection()->fetchAll($select)) {
            return $result;
        }

        return [];
    }
    /**
     * GetProductCategoryStoreFile
     *
     * @param [type] $fileId
     * @param [type] $productId
     * @param [type] $categoryId
     * @param [type] $store
     * @return void
     */
    public function getProductCategoryStoreFile($fileId, $productId, $categoryId, $store)
    {
        $select = $this->getConnection()->select()->from(
            ['fspc' => $this->getMainTable()]
        )
            ->where('fspc.' . FileScopeInterface::FILE_ID . ' = ?', $fileId)
            ->where('fspc.' . FileScopeInterface::PRODUCT_ID . ' = ?', $productId)
            ->where('fspc.' . FileScopeInterface::CATEGORY_ID . ' = ?', $categoryId)
            ->where('fspc.' . FileScopeInterface::STORE_ID . ' = ?', $store);

        if ($result = $this->getConnection()->fetchRow($select)) {
            return $result;
        }

        return [];
    }
    /**
     * SaveProductCategoryStoreFile
     *
     * @param [type] $data
     * @return void
     */
    public function saveProductCategoryStoreFile($data)
    {
        if (!empty($data[FileScopeInterface::FILE_STORE_CATEGORY_PRODUCT_ID])) {
            $this->updateFileStoreProductCategoryData($data);
        } else {
            $this->insertFileStoreProductCategoryData($data);
        }
    }
    /**
     * UpdateFileStoreProductCategoryData
     *
     * @param [type] $data
     * @return void
     */
    public function updateFileStoreProductCategoryData($data)
    {
        $productCategoryId = (int)$data[FileScopeInterface::FILE_STORE_CATEGORY_PRODUCT_ID];
        if ($productCategoryId) {
            unset($data[FileScopeInterface::FILE_STORE_CATEGORY_PRODUCT_ID]);

            $this->getConnection()->update(
                $this->getMainTable(),
                $data,
                [FileScopeInterface::FILE_STORE_CATEGORY_PRODUCT_ID. ' = ?' => $productCategoryId]
            );
        }
    }
    /**
     * InsertFileStoreProductCategoryData
     *
     * @param [type] $data
     * @return void
     */
    public function insertFileStoreProductCategoryData($data)
    {
        unset($data[FileScopeInterface::FILE_STORE_CATEGORY_PRODUCT_ID]);
        $this->getConnection()->insert($this->getMainTable(), $data);
    }
}
