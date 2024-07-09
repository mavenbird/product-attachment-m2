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
 * @package    Mavenbird_ProductAttechment
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
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
