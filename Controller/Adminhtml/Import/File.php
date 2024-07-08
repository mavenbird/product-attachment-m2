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

class File extends Import
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
        $resultPage->getConfig()->getTitle()->prepend(__('Upload Your Files'));
        /** @var Steps $steps */
        $steps = $resultPage->getLayout()->getBlock('import-steps');
        $steps->setCurrentStep(Steps::STEP1)
            ->setNextLink($this->getUrl('file/import/save'));

        return $resultPage;
    }
}
