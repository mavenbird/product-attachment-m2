<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\File;

use Mavenbird\ProductAttachment\Controller\Adminhtml\File;
use Magento\Framework\Controller\ResultFactory;

class Index extends File
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
        $resultPage->setActiveMenu('Mavenbird_ProductAttachment::files_list');
        $resultPage->addBreadcrumb(__('File'), __('File'));
        $resultPage->addBreadcrumb(__('Attachments Management'), __('Attachments Management'));
        $resultPage->getConfig()->getTitle()->prepend(__('Attachments Management'));

        return $resultPage;
    }
}
