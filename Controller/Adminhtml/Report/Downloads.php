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

class Downloads extends \Mavenbird\ProductAttachment\Controller\Adminhtml\Report
{
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Mavenbird_ProductAttachment::downloads');
        $resultPage->addBreadcrumb(__('Product Attachment Report'), __('Product Attachment Report'));
        $resultPage->addBreadcrumb(__('Downloads'), __('Downloads'));
        $resultPage->getConfig()->getTitle()->prepend(__('Product Attachment Report Downloads'));

        return $resultPage;
    }
}
