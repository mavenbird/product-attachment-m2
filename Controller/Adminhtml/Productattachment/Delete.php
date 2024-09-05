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
use Mavenbird\ProductAttachment\Model\ProductAttachment;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var Contact $attachModel
     */
    protected $_attachModel;

    /**
     *
     * @param Context           $context
     * @param ProductAttachment $attachModel
     */
    public function __construct(Context $context, ProductAttachment $attachModel)
    {
        $this->_attachModel = $attachModel;
        parent::__construct($context);
    }
   
    /**
     * Delete Action
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('attach_id');
        try {
                $image = $this->_attachModel->load($id);
                $image->delete();
                $this->messageManager->addSuccess(
                    __('Delete successfully !')
                );
        } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}
