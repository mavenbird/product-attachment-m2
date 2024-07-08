<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Report;

class Downloads extends \Mavenbird\ProductAttachment\Controller\Adminhtml\Report
{
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Mavenbird_ProductAttachment::downloads');
        $resultPage->addBreadcrumb(__('Product Attachment Report'), __('Product Attachment Report'));
        $resultPage->addBreadcrumb(__('Downloads'), __('Downloads'));
        $resultPage->getConfig()->getTitle()->prepend(__('Product Attachment Report Downloads'));

        return $resultPage;
    }
}
