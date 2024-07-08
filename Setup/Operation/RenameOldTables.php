<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Setup\Operation;

use Magento\Framework\Setup\SchemaSetupInterface;

class RenameOldTables
{
    const Mavenbird_FILE_OLD = 'mavenbird_file' . self::PREFIX;
    const Mavenbird_FILE_ICON_OLD = 'mavenbird_file_icon' . self::PREFIX;
    const Mavenbird_FILE_STAT_OLD = 'mavenbird_file_stat' . self::PREFIX;
    const Mavenbird_FILE_STORE_OLD = 'mavenbird_file_store' . self::PREFIX;
    const Mavenbird_FILE_CUSTOMER_GROUP_OLD = 'mavenbird_file_customer_group' . self::PREFIX;

    const OLD_TABLES = [
        'mavenbird_file',
        'mavenbird_file_icon',
        'mavenbird_file_stat',
        'mavenbird_file_store',
        'mavenbird_file_customer_group'
    ];

    const PREFIX = '_old';

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
