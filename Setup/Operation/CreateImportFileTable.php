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
use Mavenbird\ProductAttachment\Model\Import\Import;
use Mavenbird\ProductAttachment\Model\Import\ImportFile;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class CreateImportFileTable
{
    const TABLE_NAME = 'mavenbird_file_import_file';

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
        $importTable = $setup->getTable(CreateImportTable::TABLE_NAME);
        return $setup->getConnection()
            ->newTable(
                $table
            )->setComment(
                'Mavenbird Product Attachment Import File Table'
            )->addColumn(
                ImportFile::IMPORT_FILE_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true
                ]
            )->addColumn(
                ImportFile::IMPORT_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => false
                ]
            )->addColumn(
                FileInterface::FILE_PATH,
                Table::TYPE_TEXT,
                255,
                [
                    'default' => null
                ]
            )->addColumn(
                FileScopeInterface::FILENAME,
                Table::TYPE_TEXT,
                255,
                [
                    'default' => null
                ]
            )->addColumn(
                FileScopeInterface::LABEL,
                Table::TYPE_TEXT,
                255,
                [
                    'default' => null
                ]
            )->addColumn(
                FileScopeInterface::IS_VISIBLE,
                Table::TYPE_BOOLEAN,
                null,
                [
                    'default' => null, 'nullable' => true,
                ]
            )->addColumn(
                FileScopeInterface::CUSTOMER_GROUPS,
                Table::TYPE_TEXT,
                255,
                [
                    'default' => null, 'nullable' => true,
                ]
            )->addColumn(
                FileScopeInterface::INCLUDE_IN_ORDER,
                Table::TYPE_BOOLEAN,
                null,
                [
                    'default' => null, 'nullable' => true,
                ]
            )->addForeignKey(
                $setup->getFkName(
                    $table,
                    ImportFile::IMPORT_ID,
                    $importTable,
                    Import::IMPORT_ID
                ),
                ImportFile::IMPORT_ID,
                $importTable,
                Import::IMPORT_ID,
                Table::ACTION_CASCADE
            );
    }
}
