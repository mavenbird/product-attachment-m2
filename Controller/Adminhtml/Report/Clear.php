<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Report;

use Magento\Backend\App\Action;

class Clear extends \Mavenbird\ProductAttachment\Controller\Adminhtml\Report
{
    /**
     * @var \Mavenbird\ProductAttachment\Model\Report\ResourceModel\Item
     */
    private $item;
    /**
     * Construct
     *
     * @param \Mavenbird\ProductAttachment\Model\Report\ResourceModel\Item $item
     * @param Action\Context $context
     */
    public function __construct(
        \Mavenbird\ProductAttachment\Model\Report\ResourceModel\Item $item,
        Action\Context $context
    ) {
        parent::__construct($context);
        $this->item = $item;
    }
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        try {
            $this->item->clear();
            $this->messageManager->addSuccessMessage(__('Downloads report has been cleared.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->_redirect('*/*/downloads');
    }
}
