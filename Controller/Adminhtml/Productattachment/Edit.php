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

use Magento\Backend\App\Action;

/**
 * store edit Action
 */
class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $_session;

    /**
     * @var \Mavenbird\ProductAttachment\Model\ProductAttachment
     */
    protected $_productAttachment;

    /**
     *
     * @param Action\Context                                      $context
     * @param \Magento\Framework\View\Result\PageFactory          $resultPageFactory
     * @param \Magento\Framework\Registry                         $registry
     * @param \Mavenbird\ProductAttachment\Model\ProductAttachment $productAttachment
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Mavenbird\ProductAttachment\Model\ProductAttachment $productAttachment
    ) {
        $this->_session=$context->getSession();
        $this->_productAttachment=$productAttachment;
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mavenbird_ProductAttachment::productattachment')
            ->addBreadcrumb(__('Lists'), __('Lists'))
            ->addBreadcrumb(__('Manage Info'), __('Manage Info'));
        return $resultPage;
    }

    /**
     * Edit Store
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('attach_id');
        $is_new = $this->getRequest()->getParam('is_new');
        $model = $this->_productAttachment;

        // 2. Initial checking
        if ($id) {
            $model->load($id);
          
            $storeTable = $model->getResourceCollection()->getTable('attachment_store');
            $connection = $model->getResourceCollection()->getConnection();

            $select = $connection->select()
                        ->from(
                            ['c' => $storeTable],
                            ['store_id']
                        )
                        ->where(
                            "c.attach_id = ?",
                            $id
                        );
            $result = $connection->fetchAll($select);

            $resStore = [];
            foreach ($result as $key => $store) {
                $resStore[$key] =  $store['store_id'];
            }
            $getstore = implode(',', $resStore);
            $model->setData('store_id', $getstore);
            
            if (!$model->getId()) {
                $this->messageManager->addError(__('This info no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        // 4. Register model to use later in blocks
        
        $this->_coreRegistry->register('productattachment_data', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Attachment') : __('New Attachment'),
            $id ? __('Edit Attachment') : __('New Attachment')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Attachment'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? __('Edit Attachment') : __('New Attachment'));
        return $resultPage;
    }
}
