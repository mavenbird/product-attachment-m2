<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Setup\Operation;

use Mavenbird\ProductAttachment\Model\Import\Import;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class CreateImportTable
{
    const TABLE_NAME = 'mavenbird_file_import';

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
                'Mavenbird Product Attachment Import Table'
            )->addColumn(
                Import::IMPORT_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true
                ]
            )->addColumn(
                Import::STORE_IDS,
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => false
                ]
            )->addColumn(
                Import::CREATED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'default' => Table::TIMESTAMP_INIT, 'nullable' => false
                ]
            );
    }
}
