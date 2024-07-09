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

namespace Mavenbird\ProductAttachment\Setup;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var Operation\UpgradeDataTo200
     */
    private $upgradeDataTo200;

    /**
     * @var State
     */
    private $state;

    /**
     * Construct
     *
     * @param State $state
     */
    public function __construct(
        State $state
    ) {
        $this->state = $state;
    }

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     *
     * @throws \Exception
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if ($context->getVersion() && version_compare($context->getVersion(), '2.0.0', '<')) {
            $this->state->emulateAreaCode(Area::AREA_ADMINHTML, [$this, 'upgradeTo200'], [$setup]);
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '2.3.0', '<')) {
            $setup->getConnection()->update(
                $setup->getTable(Operation\CreateFileTable::TABLE_NAME),
                [FileInterface::URL_HASH => new \Zend_Db_Expr('md5(uuid())')]
            );
        }

        $setup->endSetup();
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    public function upgradeTo200(ModuleDataSetupInterface $setup)
    {
        $this->upgradeDataTo200 = ObjectManager::getInstance()
            ->create(\Mavenbird\ProductAttachment\Setup\Operation\UpgradeDataTo200::class);
        $this->upgradeDataTo200->execute($setup);
    }
}
