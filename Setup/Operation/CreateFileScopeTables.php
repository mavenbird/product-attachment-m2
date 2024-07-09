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

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Api\Data\FileScopeInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class CreateFileScopeTables
{
   public const FILE_STORE_TABLE_NAME = 'Mavenbird_file_store';
   public const FILE_STORE_CATEGORY_TABLE_NAME = 'Mavenbird_file_store_category';
   public const FILE_STORE_PRODUCT_TABLE_NAME = 'Mavenbird_file_store_product';
   public const FILE_STORE_CATEGORY_PRODUCT_TABLE_NAME = 'Mavenbird_file_store_category_product';

    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->createTable(
            $this->createFileStoreTable($setup)
        );

        $setup->getConnection()->createTable(
            $this->createFileStoreCategoryTable($setup)
        );

        $setup->getConnection()->createTable(
            $this->createFileStoreProductTable($setup)
        );

        $setup->getConnection()->createTable(
            $this->createFileStoreCategoryProductTable($setup)
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     *
     * @return Table
     */
    private function createFileStoreTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable(self::FILE_STORE_TABLE_NAME);

        $resultTable = $setup->getConnection()
            ->newTable(
                $table
            )->setComment(
                'Mavenbird Product Attachment File Store Relation Table'
            )->addColumn(
                FileScopeInterface::FILE_STORE_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true
                ],
                'File Store ID'
            );

        $this->addBaseFields($resultTable);
        $this->addFileForeignKey($resultTable, $table, $setup);

        return $resultTable;
    }

    /**
     * @param SchemaSetupInterface $setup
     *
     * @return Table
     */
    private function createFileStoreCategoryTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable(self::FILE_STORE_CATEGORY_TABLE_NAME);

        $resultTable = $setup->getConnection()
            ->newTable(
                $table
            )->setComment(
                'Mavenbird Product Attachment File Store Category Relation Table'
            )->addColumn(
                FileScopeInterface::FILE_STORE_CATEGORY_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true
                ],
                'File Store Category ID'
            )->addColumn(
                FileScopeInterface::CATEGORY_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => true
                ],
                'Category Entity ID'
            )->addColumn(
                FileScopeInterface::FILE_STORE_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => true, 'default' => null
                ],
                'File Store ID'
            );

        $this->addBaseFields($resultTable);
        $this->addFileForeignKey($resultTable, $table, $setup);

        return $resultTable;
    }

    /**
     * @param SchemaSetupInterface $setup
     *
     * @return Table
     */
    private function createFileStoreProductTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable(self::FILE_STORE_PRODUCT_TABLE_NAME);

        $resultTable = $setup->getConnection()
            ->newTable(
                $table
            )->setComment(
                'Mavenbird Product Attachment File Store Product Relation Table'
            )->addColumn(
                FileScopeInterface::FILE_STORE_PRODUCT_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true
                ],
                'File Store Product ID'
            )->addColumn(
                FileScopeInterface::PRODUCT_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => true
                ],
                'Product Entity ID'
            )->addColumn(
                FileScopeInterface::FILE_STORE_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => true, 'default' => null
                ],
                'File Store ID'
            );

        $this->addBaseFields($resultTable);
        $this->addFileForeignKey($resultTable, $table, $setup);

        return $resultTable;
    }

    /**
     * @param SchemaSetupInterface $setup
     *
     * @return Table
     */
    private function createFileStoreCategoryProductTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable(self::FILE_STORE_CATEGORY_PRODUCT_TABLE_NAME);

        $resultTable = $setup->getConnection()
            ->newTable(
                $table
            )->setComment(
                'Mavenbird Product Attachment File Store Product Relation Table'
            )->addColumn(
                FileScopeInterface::FILE_STORE_CATEGORY_PRODUCT_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true
                ],
                'File Store Category Product ID'
            )->addColumn(
                FileScopeInterface::CATEGORY_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => true
                ],
                'Category Entity ID'
            )->addColumn(
                FileScopeInterface::PRODUCT_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => true
                ],
                'Product Entity ID'
            )->addColumn(
                FileScopeInterface::FILE_STORE_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => true, 'default' => null
                ],
                'File Store ID'
            );

        $this->addBaseFields($resultTable);
        $this->addFileForeignKey($resultTable, $table, $setup);

        return $resultTable;
    }

    /**
     * @param Table $table
     *
     * @return void
     */
    private function addBaseFields(&$table)
    {
        $table->addColumn(
            FileScopeInterface::FILE_ID,
            Table::TYPE_INTEGER,
            null,
            [
                'unsigned' => true, 'nullable' => false
            ],
            'File Id'
        )->addColumn(
            FileScopeInterface::STORE_ID,
            Table::TYPE_SMALLINT,
            null,
            [
                'unsigned' => true, 'nullable' => false
            ],
            'Store Id'
        )->addColumn(
            FileScopeInterface::FILENAME,
            Table::TYPE_TEXT,
            255,
            [
                'default' => null
            ],
            'File Name'
        )->addColumn(
            FileScopeInterface::LABEL,
            Table::TYPE_TEXT,
            255,
            [
                'default' => null
            ],
            'Label'
        )->addColumn(
            FileScopeInterface::IS_VISIBLE,
            Table::TYPE_BOOLEAN,
            null,
            [
                'default' => null, 'nullable' => true,
            ],
            'Is File Visible'
        )->addColumn(
            FileScopeInterface::CUSTOMER_GROUPS,
            Table::TYPE_TEXT,
            255,
            [
                'default' => null, 'nullable' => true,
            ],
            'Is File Visible'
        )->addColumn(
            FileScopeInterface::INCLUDE_IN_ORDER,
            Table::TYPE_BOOLEAN,
            null,
            [
                'default' => null, 'nullable' => true,
            ],
            'Include in Order'
        )->addColumn(
            FileScopeInterface::POSITION,
            Table::TYPE_INTEGER,
            null,
            [
                'unsigned' => null, 'nullable' => true
            ],
            'Position'
        );
    }

    /**
     * @param Table $resultTable
     * @param string $table
     * @param SchemaSetupInterface $setup
     *
     * @return void
     */
    private function addFileForeignKey(&$resultTable, $table, $setup)
    {
        $resultTable->addForeignKey(
            $setup->getFkName(
                $table,
                FileScopeInterface::FILE_ID,
                $setup->getTable(CreateFileTable::TABLE_NAME),
                FileInterface::FILE_ID
            ),
            FileScopeInterface::FILE_ID,
            $setup->getTable(CreateFileTable::TABLE_NAME),
            FileInterface::FILE_ID,
            Table::ACTION_CASCADE
        );
    }
}
