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
 * @package    Mavenbird_ProductAttachment
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */ 

namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Productattachment;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Index Action for Attachment
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     *
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    
    /**
     * @inheritdoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mavenbird_ProductAttachment::ProductAttachment');
    }
    
    /**
     * Execute method for Attachment index action
     *
     * @return $resultPage
     */
    public function execute()
    {
        /**
         * render The admin grid page
         */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mavenbird_ProductAttachment::productattachment');
        $resultPage->addBreadcrumb(__('Attachment'), __('Attachment'));
        $resultPage->addBreadcrumb(__('Manage Attachment'), __('Manage Attachment'));
        $resultPage->getConfig()->getTitle()->prepend(__('Attachment'));
        return $resultPage;
    }
}
