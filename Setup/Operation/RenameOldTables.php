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

use Magento\Framework\Setup\SchemaSetupInterface;

class RenameOldTables
{
    public const Mavenbird_FILE_OLD = 'mavenbird_file' . self::PREFIX;
    public const Mavenbird_FILE_ICON_OLD = 'mavenbird_file_icon' . self::PREFIX;
    public const Mavenbird_FILE_STAT_OLD = 'mavenbird_file_stat' . self::PREFIX;
    public const Mavenbird_FILE_STORE_OLD = 'mavenbird_file_store' . self::PREFIX;
    public const Mavenbird_FILE_CUSTOMER_GROUP_OLD = 'mavenbird_file_customer_group' . self::PREFIX;

    public const OLD_TABLES = [
        'mavenbird_file',
        'mavenbird_file_icon',
        'mavenbird_file_stat',
        'mavenbird_file_store',
        'mavenbird_file_customer_group'
    ];

    public const PREFIX = '_old';

    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        foreach (self::OLD_TABLES as $oldTable) {
            if (!$connection->isTableExists($setup->getTable($oldTable . self::PREFIX))) {
                $foreignKeys = $connection->getForeignKeys($setup->getTable($oldTable));
                foreach ($foreignKeys as $foreignKey) {
                    $connection->dropForeignKey($setup->getTable($oldTable), $foreignKey['FK_NAME']);
                }
            }
        }

        foreach (self::OLD_TABLES as $oldTable) {
            if (!$connection->isTableExists($setup->getTable($oldTable . self::PREFIX))) {
                $connection->renameTable($setup->getTable($oldTable), $setup->getTable($oldTable . self::PREFIX));
            }
        }
    }
}
