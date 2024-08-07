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
use Magento\Framework\DB\Ddl\TriggerFactory;
use Magento\Framework\DB\Ddl\Trigger;
use Magento\Framework\Setup\SchemaSetupInterface;

class CreateTriggers
{
    /**
     * @var TriggerFactory
     */
    private $triggerFactory;

    public function __construct(
        TriggerFactory $triggerFactory
    ) {
        $this->triggerFactory = $triggerFactory;
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $this->createUpdateTrigger(
            $setup,
            CreateFileScopeTables::FILE_STORE_TABLE_NAME
        );

        $this->createUpdateTrigger(
            $setup,
            CreateFileScopeTables::FILE_STORE_CATEGORY_TABLE_NAME
        );

        $this->createUpdateTrigger(
            $setup,
            CreateFileScopeTables::FILE_STORE_PRODUCT_TABLE_NAME
        );

        $this->createUpdateTrigger(
            $setup,
            CreateFileScopeTables::FILE_STORE_CATEGORY_PRODUCT_TABLE_NAME
        );
    }

    private function createUpdateTrigger($setup, $tableName)
    {
        /** @var Trigger $trigger */
        $trigger = $this->triggerFactory->create()
            ->setName('updated_time_for_' . $tableName)
            ->setTime(Trigger::TIME_AFTER)
            ->setEvent(Trigger::EVENT_UPDATE)
            ->setTable($setup->getTable($tableName));
        $trigger->addStatement($this->getUpdatedRowsStatement($setup));
        $setup->getConnection()->createTrigger($trigger);
    }

    private function getUpdatedRowsStatement($setup)
    {
        return sprintf(
            "UPDATE %s SET %s = CURRENT_TIMESTAMP() WHERE %s = NEW.%s",
            $setup->getTable(CreateFileTable::TABLE_NAME),
            FileInterface::UPDATED_AT,
            FileInterface::FILE_ID,
            FileScopeInterface::FILE_ID
        );
    }
}
