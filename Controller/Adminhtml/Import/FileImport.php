<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Import;

use Mavenbird\ProductAttachment\Block\Adminhtml\Steps;
use Mavenbird\ProductAttachment\Controller\Adminhtml\Import;

class FileImport extends Import
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
        $resultPage->setActiveMenu('Mavenbird_ProductAttachment::import');
        $resultPage->getConfig()->getTitle()->prepend(__('Import Attachments'));
        $resultPage->addBreadcrumb(__('Import Attachments'), __('Import Attachments'));

        /** @var Steps $steps */
        $steps = $resultPage->getLayout()->getBlock('import-steps');
        $steps->setCurrentStep(Steps::STEP3)
            ->setBackLink($this->getUrl(
                'file/import/store',
                ['import_id' => $this->getRequest()->getParam('import_id')]
            ));

        return $resultPage;
    }
}
