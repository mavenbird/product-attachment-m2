<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\File\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mavenbird\ProductAttachment\Setup\Operation\CreateFileScopeTables;
use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Api\Data\FileScopeInterface;

class Collection extends AbstractCollection
{
    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(
            \Mavenbird\ProductAttachment\Model\File\File::class,
            \Mavenbird\ProductAttachment\Model\File\ResourceModel\File::class
        );
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    /**
     * AddFileData
     *
     * @param integer $storeId
     * @return void
     */
    public function addFileData($storeId = 0)
    {
        $this->addFilterToMap('file_id', 'main_table.file_id');
        $this->join(
            CreateFileScopeTables::FILE_STORE_TABLE_NAME,
            'main_table.' . FileInterface::FILE_ID .
            ' = ' . CreateFileScopeTables::FILE_STORE_TABLE_NAME . '.' . FileScopeInterface::FILE_ID,
            [
                FileScopeInterface::LABEL,
                FileScopeInterface::FILENAME,
                FileScopeInterface::INCLUDE_IN_ORDER,
                FileScopeInterface::IS_VISIBLE,
            ]
        );
        $this->getSelect()->where(
            CreateFileScopeTables::FILE_STORE_TABLE_NAME . '.' . FileScopeInterface::STORE_ID . ' = ' . $storeId
        );

        return $this;
    }

    /**
     * AddInsertListingFileData
     *
     * @param integer $storeId
     * @return void
     */
    public function addInsertListingFileData($storeId = 0)
    {
        if (!$storeId) {
            $this->addFileData();

            return $this;
        }
        $this->addFieldToSelect(FileInterface::FILE_ID);
        $this->addFieldToSelect(FileInterface::SIZE);
        $this->addFieldToSelect(FileInterface::MIME_TYPE);
        $this->addFieldToSelect(FileInterface::EXTENSION);
        $this->addFilterToMap('file_id', 'main_table.file_id');
        foreach ([
                FileScopeInterface::LABEL,
                FileScopeInterface::FILENAME,
                FileScopeInterface::INCLUDE_IN_ORDER,
                FileScopeInterface::IS_VISIBLE
            ]
 as $field) {
            $field2 = CreateFileScopeTables::FILE_STORE_TABLE_NAME . '_store.' . $field;
            $field1 = CreateFileScopeTables::FILE_STORE_TABLE_NAME . '.' . $field;
            $this->addFieldToSelect(new \Zend_Db_Expr('IFNULL(' . $field2 . ', ' . $field1 . ')'), $field);
        }

        $this->getSelect()->joinLeft(
            [CreateFileScopeTables::FILE_STORE_TABLE_NAME . '_store' => $this->getTable(CreateFileScopeTables::FILE_STORE_TABLE_NAME)],
            '(main_table.' . FileInterface::FILE_ID
                . ' = ' . CreateFileScopeTables::FILE_STORE_TABLE_NAME . '_store'
                . '.' . FileScopeInterface::FILE_ID
                . ' AND ' . CreateFileScopeTables::FILE_STORE_TABLE_NAME . '_store' . '.' . FileScopeInterface::STORE_ID
                . '=' . (int)$storeId . ')',
            []
        );

        $this->join(
            CreateFileScopeTables::FILE_STORE_TABLE_NAME,
            'main_table.' . FileInterface::FILE_ID .
            ' = ' . CreateFileScopeTables::FILE_STORE_TABLE_NAME . '.' . FileScopeInterface::FILE_ID,
            []
        );
        $this->addFieldToFilter(
            CreateFileScopeTables::FILE_STORE_TABLE_NAME . '.' . FileScopeInterface::STORE_ID,
            0
        );

        return $this;
    }
}
