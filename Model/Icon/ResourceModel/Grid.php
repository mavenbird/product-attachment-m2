<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Icon\ResourceModel;

use Mavenbird\ProductAttachment\Api\Data\IconInterface;
use Mavenbird\ProductAttachment\Setup\Operation\CreateIconExtensionTable;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;

class Grid extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    /**
     * Construct
     *
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param [type] $mainTable
     * @param [type] $resourceModel
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = \Mavenbird\ProductAttachment\Setup\Operation\CreateIconTable::TABLE_NAME,
        $resourceModel = \Mavenbird\ProductAttachment\Model\Icon\ResourceModel\Icon::class
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
        $this->addFieldToSelect(
            new \Zend_Db_Expr(
                '`main_table`.*, ('
                    . 'select group_concat(' . IconInterface::EXTENSION . ' separator \', \')'
                    . ' from ' . $this->getTable(CreateIconExtensionTable::TABLE_NAME)
                    . ' where ' . IconInterface::ICON_ID . ' = `main_table`.' . IconInterface::ICON_ID
                .') as extension'
            )
        );
    }
}
