<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Icon;

use Mavenbird\ProductAttachment\Controller\Adminhtml\Icon;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\Icon\Repository;
use Magento\Backend\App\Action;
use Psr\Log\LoggerInterface;

class Delete extends Icon
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
        if ($iconId = $this->getRequest()->getParam(RegistryConstants::FORM_ICON_ID)) {
            try {
                $this->repository->deleteById($iconId);
                $this->messageManager->addSuccessMessage(__('You deleted the icon.'));
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
