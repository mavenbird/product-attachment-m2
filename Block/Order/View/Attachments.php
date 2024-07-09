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

namespace Mavenbird\ProductAttachment\Block\Order\View;

class Attachments extends \Mavenbird\ProductAttachment\Block\Order\AbstractAttachments
{
    /**
     * ToHtml
     *
     * @return void
     */
    public function toHtml()
    {
        if (!$this->configProvider->isShowInOrderView()) {
            return '';
        }

        return parent::toHtml();
    }
    
    /**
     * GetBlockTitle
     *
     * @return void
     */
    public function getBlockTitle()
    {
        return $this->configProvider->getLabelInOrderView();
    }

    /**
     * IsShowIcon
     *
     * @return boolean
     */
    public function isShowIcon()
    {
        return $this->configProvider->isShowIconInOrderView();
    }

    /**
     * IsShowFilesize
     *
     * @return boolean
     */
    public function isShowFilesize()
    {
        return $this->configProvider->isShowFilesizeInOrderView();
    }

    /**
     * @inheritdoc
     */
    public function getAttachmentsFilter()
    {
        return $this->configProvider->getViewAttachmentsFilter();
    }

    /**
     * @inheritdoc
     */
    public function getOrderStatuses()
    {
        return $this->configProvider->getViewOrderStatuses();
    }
}
