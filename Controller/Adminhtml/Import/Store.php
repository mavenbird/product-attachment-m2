<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Import;

use Mavenbird\ProductAttachment\Block\Adminhtml\Steps;
use Mavenbird\ProductAttachment\Controller\Adminhtml\Import;
use Magento\Framework\Controller\ResultFactory;

class Store extends Import
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
        $resultPage->getConfig()->getTitle()->prepend(__('Select Stores For Configuration'));
        /** @var Steps $steps */
        $steps = $resultPage->getLayout()->getBlock('import-steps');
        $steps->setCurrentStep(Steps::STEP2)
            ->setNextLink($this->getUrl('file/import/save'))
            ->setBackLink($this->getUrl(
                'file/import/file',
                ['import_id' => $this->getRequest()->getParam('import_id')]
            ));

        return $resultPage;
    }
}
