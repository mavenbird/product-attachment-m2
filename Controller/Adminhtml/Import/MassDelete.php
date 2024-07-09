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

namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Import;

use Mavenbird\ProductAttachment\Controller\Adminhtml\Import;
use Mavenbird\ProductAttachment\Model\Import\Repository;
use Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportCollectionFactory;
use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

class MassDelete extends Import
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var ImportCollectionFactory
     */
    private $importCollectionFactory;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Construct
     *
     * @param Action\Context $context
     * @param Filter $filter
     * @param ImportCollectionFactory $importCollectionFactory
     * @param LoggerInterface $logger
     * @param Repository $repository
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        ImportCollectionFactory $importCollectionFactory,
        LoggerInterface $logger,
        Repository $repository
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->importCollectionFactory = $importCollectionFactory;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $this->filter->applySelectionOnTargetProvider(); // compatibility with Mass Actions on Magento 2.1.0
        /** @var \Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportCollection $collection */
        $collection = $this->filter->getCollection($this->importCollectionFactory->create());

        $collectionSize = $collection->getSize();
        if ($collectionSize) {
            try {
                foreach ($collection->getItems() as $import) {
                    $this->repository->deleteById($import->getImportId());
                }

                $this->messageManager->addSuccessMessage($this->getSuccessMessage($collectionSize));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($this->getErrorMessage());
                $this->logger->critical($e);
            }
        }
        $this->_redirect($this->_redirect->getRefererUrl());
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
            return __('A total of %1 record(s) have been deleted.', $collectionSize);
        }

        return __('No records have been changed.');
    }
}
