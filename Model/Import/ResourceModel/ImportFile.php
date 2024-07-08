<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Import\ResourceModel;

use Mavenbird\ProductAttachment\Model\Import\ImportFile as ImportFileModel;
use Mavenbird\ProductAttachment\Setup\Operation\CreateImportFileTable;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ImportFile extends AbstractDb
{
    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(CreateImportFileTable::TABLE_NAME, ImportFileModel::IMPORT_FILE_ID);
    }

    /**
     * DeleteFiles
     *
     * @param [type] $importId
     * @param [type] $importFileIds
     * @return void
     */
    public function deleteFiles($importId, $importFileIds)
    {
        $this->getConnection()->delete(
            $this->getMainTable(),
            [
                ImportFileModel::IMPORT_FILE_ID . ' IN (?)' => array_unique($importFileIds),
                ImportFileModel::IMPORT_ID => (int)$importId
            ]
        );
    }
}
