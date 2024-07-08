<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Block\Order\Email;

class Attachments extends \Mavenbird\ProductAttachment\Block\Order\AbstractAttachments
{
    /**
     * ToHtml
     *
     * @return void
     */
    public function toHtml()
    {
        if (!$this->configProvider->isShowInOrderEmail()) {
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
        return $this->configProvider->getLabelInOrderEmail();
    }

    /**
     * @inheritdoc
     */
    public function getAttachmentsFilter()
    {
        return $this->configProvider->getEmailAttachmentsFilter();
    }

    /**
     * @inheritdoc
     */
    public function getOrderStatuses()
    {
        return $this->configProvider->getEmailOrderStatuses();
    }
}
