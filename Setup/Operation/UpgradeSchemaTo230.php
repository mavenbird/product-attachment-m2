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
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchemaTo230
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $setup->getConnection()
            ->addColumn(
                $setup->getTable(CreateFileTable::TABLE_NAME),
                FileInterface::URL_HASH,
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 32,
                    'default' => '',
                    'nullable' => false,
                    'comment' => 'md5 random hash for url creation'
                ]
            );
        $setup->getConnection()->addIndex(
            $setup->getTable(CreateFileTable::TABLE_NAME),
            $setup->getIdxName(
                $setup->getTable(CreateFileTable::TABLE_NAME),
                FileInterface::URL_HASH,
                AdapterInterface::INDEX_TYPE_INDEX
            ),
            FileInterface::URL_HASH,
            AdapterInterface::INDEX_TYPE_INDEX
        );
    }
}
