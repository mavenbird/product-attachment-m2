<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\File;

use Mavenbird\ProductAttachment\Controller\Adminhtml\File;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\File\Repository;
use Magento\Backend\App\Action;
use Psr\Log\LoggerInterface;

class Delete extends File
{
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
     * @param Repository $repository
     * @param LoggerInterface $logger
     * @param Action\Context $context
     */
    public function __construct(
        Repository $repository,
        LoggerInterface $logger,
        Action\Context $context
    ) {
        parent::__construct($context);
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
        if ($fileId = $this->getRequest()->getParam(RegistryConstants::FORM_FILE_ID)) {
            try {
                $this->repository->deleteById($fileId);
                $this->messageManager->addSuccessMessage(__('You deleted the file.'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->logger->critical($e);
            }
        }
        $this->_redirect('*/*/');
    }
}
