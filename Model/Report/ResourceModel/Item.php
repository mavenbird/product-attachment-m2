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
