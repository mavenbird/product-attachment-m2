<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Setup\Operation;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchemaTo235
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $setup->getConnection()
            ->addColumn(
                $setup->getTable(CreateFileTable::TABLE_NAME),
                FileInterface::CREATED_AT,
                [
                    'type' => Table::TYPE_TIMESTAMP,
                    'length' => 32,
                    'default' => Table::TIMESTAMP_INIT,
                    'nullable' => false,
                    'comment' => 'Created at'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable(CreateFileTable::TABLE_NAME),
                FileInterface::UPDATED_AT,
                [
                    'type' => Table::TYPE_TIMESTAMP,
                    'length' => 32,
                    'default' => Table::TIMESTAMP_INIT_UPDATE,
                    'nullable' => false,
                    'comment' => 'Updated at'
                ]
            );
    }
}