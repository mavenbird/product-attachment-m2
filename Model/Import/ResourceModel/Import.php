<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Import\ResourceModel;

use Mavenbird\ProductAttachment\Model\Import\Import as ImportModel;
use Mavenbird\ProductAttachment\Setup\Operation\CreateImportTable;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Import extends AbstractDb
{
    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(CreateImportTable::TABLE_NAME, ImportModel::IMPORT_ID);
    }
}
