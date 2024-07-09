<?php
/**
* Mavenbird Technologies Private Limited
*
* NOTICE OF LICENSE
*
* This source file is subject to the EULA
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://mavenbird.com/Mavenbird-Module-License.txt
*
* =================================================================
*
* @category   Mavenbird
* @package    Mavenbird_ProductAttechment
* @author     Mavenbird Team
* @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
* @license    http://mavenbird.com/Mavenbird-Module-License.txt
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
