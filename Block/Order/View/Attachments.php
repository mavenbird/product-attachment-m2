<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
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
