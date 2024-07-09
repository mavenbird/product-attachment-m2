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
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class CreateFileTable
{
    const TABLE_NAME = 'mavenbird_file';

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
                'Mavenbird Product Attachment File Table'
            )->addColumn(
                FileInterface::FILE_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true
                ],
                'File Id'
            )->addColumn(
                FileInterface::ATTACHMENT_TYPE,
                Table::TYPE_SMALLINT,
                null,
                [
                    'unsigned' => true, 'nullable' => false, 'default' => 0
                ],
                'Attachment Type'
            )->addColumn(
                FileInterface::FILE_PATH,
                Table::TYPE_TEXT,
                255,
                [
                    'default' => '', 'nullable' => false
                ],
                'File Path'
            )->addColumn(
                FileInterface::LINK,
                Table::TYPE_TEXT,
                null,
                [
                    'default' => '', 'nullable' => false
                ],
                'File Link'
            )->addColumn(
                FileInterface::EXTENSION,
                Table::TYPE_TEXT,
                10,
                [
                    'default' => '', 'nullable' => false
                ],
                'File Extension'
            )->addColumn(
                FileInterface::SIZE,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true, 'nullable' => false
                ],
                'File Size'
            )->addColumn(
                FileInterface::MIME_TYPE,
                Table::TYPE_TEXT,
                255,
                [
                    'default' => '', 'nullable' => false
                ],
                'File Mime Type'
            )->addColumn(
                FileInterface::URL_HASH,
                Table::TYPE_TEXT,
                32,
                [
                    'default' => '', 'nullable' => false
                ],
                'md5 random hash for url creation'
            )->addColumn(
                FileInterface::CREATED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'default' => Table::TIMESTAMP_INIT, 'nullable' => false
                ],
                'Created At'
            )->addColumn(
                FileInterface::UPDATED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'default' => Table::TIMESTAMP_INIT_UPDATE, 'nullable' => false
                ],
                'Updated At'
            )->addIndex(
                $setup->getIdxName(
                    $table,
                    FileInterface::URL_HASH,
                    AdapterInterface::INDEX_TYPE_INDEX
                ),
                FileInterface::URL_HASH
            );
    }
}
