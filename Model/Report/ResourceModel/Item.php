<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Report\ResourceModel;

use Mavenbird\ProductAttachment\Model\Report\Item as ItemModel;
use Mavenbird\ProductAttachment\Setup\Operation\CreateReportTable;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Item extends AbstractDb
{
    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(CreateReportTable::TABLE_NAME, ItemModel::ITEM_ID);
    }

    /**
     * Clear
     *
     * @return void
     */
    public function clear()
    {
        $this->getConnection()->truncateTable($this->getMainTable());
    }
}
