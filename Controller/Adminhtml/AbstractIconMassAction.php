<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Mavenbird\ProductAttachment\Api\IconRepositoryInterface;
use Mavenbird\ProductAttachment\Model\Icon\ResourceModel\CollectionFactory;
use Mavenbird\ProductAttachment\Api\Data\IconInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractIconMassAction extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Mavenbird_ProductAttachment::icon';

    /**
     * Filters
     *
     * @var [type]
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $iconCollectionFactory;

    /**
     * @var IconRepositoryInterface
     */
    protected $repository;

    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * Construct
     *
     * @param Action\Context $context
     * @param Filter $filter
     * @param IconRepositoryInterface $repository
     * @param CollectionFactory $iconCollectionFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        IconRepositoryInterface $repository,
        CollectionFactory $iconCollectionFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->repository = $repository;
        $this->iconCollectionFactory = $iconCollectionFactory;
        $this->logger = $logger;
    }

    /**
     * Execute action for icon
     *
     * @param IconInterface $icon
     */
    abstract protected function itemAction(IconInterface $icon);

    /**
     * Mass action execution
     */
    public function execute()
    {
        $this->filter->applySelectionOnTargetProvider(); // compatibility with Mass Actions on Magento 2.1.0
        /** @var \Mavenbird\ProductAttachment\Model\Icon\ResourceModel\Collection $collection */
        $collection = $this->filter->getCollection($this->iconCollectionFactory->create());

        $collectionSize = $collection->getSize();
        if ($collectionSize) {
            try {
                foreach ($collection->getItems() as $icon) {
                    $this->itemAction($icon);
                }
                $this->messageManager->addSuccessMessage($this->getSuccessMessage($collectionSize));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($this->getErrorMessage());
                $this->logger->critical($e);
            }
        }
        $this->_redirect('*/*/');
    }
    /**
     * GetErrorMessage
     *
     * @return void
     */
    protected function getErrorMessage()
    {
        return __('We can\'t change item right now. Please review the log and try again.');
    }
    /**
     * Getsuccessmessage
     *
     * @param integer $collectionSize
     * @return void
     */
    protected function getSuccessMessage($collectionSize = 0)
    {
        if ($collectionSize) {
            return __('A total of %1 record(s) have been changed.', $collectionSize);
        }
        return __('No records have been changed.');
    }
}
