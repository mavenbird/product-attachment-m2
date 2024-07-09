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

namespace Mavenbird\ProductAttachment\Setup\Operation;

use Mavenbird\ProductAttachment\Model\Report\Item;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class CreateReportTable
{
    const TABLE_NAME = 'mavenbird_file_report';

    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->createTable(
            $this->createTable($setup)
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     *
     * @return Table
     */
    private function createTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable(self::TABLE_NAME);

        return $setup->getConnection()
            ->newTable(
                $table
            )->setComment(
                'Mavenbird Product Attachment Report Table'
            )->addColumn(
                Item::ITEM_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true
                ],
                'Item Id'
            )->addColumn(
                Item::FILE_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => false
                ],
                'File Id'
            )->addColumn(
                Item::STORE_ID,
                Table::TYPE_SMALLINT,
                null,
                [
                    'unsigned' => true, 'nullable' => true
                ],
                'Store ID'
            )->addColumn(
                Item::CUSTOMER_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => true
                ],
                'Customer ID'
            )->addColumn(
                Item::DOWNLOAD_SOURCE,
                Table::TYPE_SMALLINT,
                null,
                [
                    'unsigned' => true, 'nullable' => true
                ],
                'Download Source'
            )->addColumn(
                Item::PRODUCT_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => true
                ],
                'Product Id'
            )->addColumn(
                Item::CATEGORY_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => true
                ],
                'Category Id'
            )->addColumn(
                Item::ORDER_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => true
                ],
                'Order Id'
            )->addColumn(
                Item::DOWNLOADED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'default' => Table::TIMESTAMP_INIT, 'nullable' => false
                ],
                'Downloaded at'
            );
    }
}
