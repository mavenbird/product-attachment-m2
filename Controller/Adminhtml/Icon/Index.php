<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Icon;

use Mavenbird\ProductAttachment\Controller\Adminhtml\Icon;
use Magento\Framework\Controller\ResultFactory;

class Index extends Icon
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
        $resultPage->setActiveMenu('Mavenbird_ProductAttachment::icon');
        $resultPage->addBreadcrumb(__('Icon'), __('Icon'));
        $resultPage->addBreadcrumb(__('Icon Management'), __('Icon Management'));
        $resultPage->getConfig()->getTitle()->prepend(__('Icon Management'));

        return $resultPage;
    }
}
