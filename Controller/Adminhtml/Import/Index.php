<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */

namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Import;

use Mavenbird\ProductAttachment\Controller\Adminhtml\Import;
use Magento\Framework\Controller\ResultFactory;

class Index extends Import
{
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Mavenbird_ProductAttachment::import');
        $resultPage->addBreadcrumb(__('Product Attachments'), __('Product Attachments'));
        $resultPage->addBreadcrumb(__('Mass File Import'), __('Mass File Import'));
        $resultPage->getConfig()->getTitle()->prepend(__('Mass File Import'));

        return $resultPage;
    }
}
