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

class FileStore extends AbstractDb
{
    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(CreateFileScopeTables::FILE_STORE_TABLE_NAME, FileScopeInterface::FILE_STORE_ID);
    }

    /**
     * GetByStoreId
     *
     * @param [type] $fileId
     * @param [type] $storeId
     * @return void
     */
    public function getByStoreId($fileId, $storeId)
    {
         $select = $this->getConnection()->select()
            ->from(['fs' => $this->getMainTable()])
            ->where('fs.' . FileScopeInterface::STORE_ID . ' = ?', (int)$storeId)
            ->where('fs.' . FileScopeInterface::FILE_ID . ' = ?', (int)$fileId);

        if ($result = $this->getConnection()->fetchRow($select)) {
            return $result;
        }

         return false;
    }
    /**
     * SaveFileStoreData
     *
     * @param [type] $fileStoreData
     * @return void
     */
    public function saveFileStoreData($fileStoreData)
    {
        if (!empty($fileStoreData[FileScopeInterface::FILE_STORE_ID])) {
            $fileStoreId = (int)$fileStoreData[FileScopeInterface::FILE_STORE_ID];
            unset($fileStoreData[FileScopeInterface::FILE_STORE_ID]);
            $this->getConnection()->update(
                $this->getMainTable(),
                $fileStoreData,
                [FileScopeInterface::FILE_STORE_ID . ' = ?' => $fileStoreId]
            );

            return $fileStoreId;
        } else {
            $this->getConnection()->insert($this->getMainTable(), $fileStoreData);

            return $this->getConnection()->lastInsertId();
        }
    }

    /**
     * GetSelectFileStoreByStore
     *
     * @param [type] $fileId
     * @param [type] $storeId
     * @return void
     */
    public function getSelectFileStoreByStore($fileId, $storeId)
    {
        return $this->getConnection()->select()
            ->from(['fs' => $this->getMainTable()])
            ->where('fs.' . FileScopeInterface::STORE_ID . ' = ?', (int)$storeId)
            ->where('fs.' . FileScopeInterface::FILE_ID . ' = ?', (int)$fileId)
            ->where(
                '(fs.' . FileScopeInterface::CATEGORY_ID . ' IS NULL OR '
                . 'fs.' . FileScopeInterface::PRODUCT_ID . ' IS NULL)'
            );
    }

    /**
     * GetFileStoreByStore
     *
     * @param [type] $fileId
     * @param [type] $storeId
     * @return void
     */
    public function getFileStoreByStore($fileId, $storeId)
    {
        return $this->getConnection()->fetchAll($this->getSelectFileStoreByStore($fileId, $storeId));
    }

    /**
     * DeleteMultiple
     *
     * @param [type] $fileStoreIds
     * @return void
     */
    public function deleteMultiple($fileStoreIds)
    {
        if (empty($fileStoreIds)) {
            return;
        }
        foreach ($fileStoreIds as &$fileStoreId) {
            $fileStoreId = (int)$fileStoreId;
        }
        $this->getConnection()->delete(
            $this->getMainTable(),
            [FileScopeInterface::FILE_STORE_ID . ' IN (?)' => array_unique($fileStoreIds)]
        );
    }

    /**
     * UpdateFileStoreData
     *
     * @param [type] $fileStoreData
     * @return void
     */
    public function updateFileStoreData($fileStoreData)
    {
        $fileStoreId = (int)$fileStoreData[FileScopeInterface::FILE_STORE_ID];
        if ($fileStoreId) {
            unset($fileStoreData[FileScopeInterface::FILE_STORE_ID]);

            $this->getConnection()->update(
                $this->getMainTable(),
                $fileStoreData,
                [FileScopeInterface::FILE_STORE_ID . ' = ?' => $fileStoreId]
            );
        }
    }

    /**
     * InsertFileStoreData
     *
     * @param [type] $fileStoreData
     * @return void
     */
    public function insertFileStoreData($fileStoreData)
    {
        unset($fileStoreData[FileScopeInterface::FILE_STORE_ID]);
        $this->getConnection()->insert($this->getMainTable(), $fileStoreData);
    }
}
