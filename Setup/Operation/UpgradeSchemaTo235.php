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
