<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Mavenbird\ProductAttachment\Api\FileRepositoryInterface;
use Mavenbird\ProductAttachment\Model\File\ResourceModel\CollectionFactory;
use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractFileMassAction extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Mavenbird_ProductAttachment::files_list';
    /** TODO acl File list/File actions/etc */

    /**
     * Filters
     *
     * @var [type]
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $fileCollectionFactory;

    /**
     * @var FileRepositoryInterface
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
     * @param FileRepositoryInterface $repository
     * @param CollectionFactory $fileCollectionFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        FileRepositoryInterface $repository,
        CollectionFactory $fileCollectionFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->repository = $repository;
        $this->fileCollectionFactory = $fileCollectionFactory;
        $this->logger = $logger;
    }
    /**
     * Execute action for file
     *
     * @param FileInterface $file
     */
    abstract protected function itemAction(FileInterface $file);
    /**
     * Mass action execution
     */
    public function execute()
    {
        $this->filter->applySelectionOnTargetProvider(); // compatibility with Mass Actions on Magento 2.1.0
        /** @var \Mavenbird\ProductAttachment\Model\File\ResourceModel\Collection $collection */
        $collection = $this->filter->getCollection($this->fileCollectionFactory->create());

        $collectionSize = $collection->getSize();
        if ($collectionSize) {
            try {
                foreach ($collection->getItems() as $item) {
                    $this->itemAction($item);
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
     * GetSuccessMessage
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
