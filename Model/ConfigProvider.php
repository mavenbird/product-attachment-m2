<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model;

class ConfigProvider extends \Mavenbird\Core\Model\ConfigProviderAbstract
{
    /**
     * PathPrefixes
     *
     * @var string
     */
    protected $pathPrefix = 'file/';

    /**#@+
     * Constants defined for xpath of system configuration
     */
    public const XPATH_ENABLED = 'general/enabled';
    public const ADD_CATEGORIES_FILES_TO_PRODUCTS = 'general/add_categories_files';
    public const EXCLUDE_INCLUDE_IN_ORDER_FILES = 'general/exclude_include_in_order_files';
    public const URL_TYPE = 'general/url_type';
    public const DETECT_MIME_TYPE = 'additional/detect_mime';
    public const BLOCK_TITLE = 'product_tab/block_label';
    public const BLOCK_ENABLED = 'product_tab/block_enabled';
    public const BLOCK_SORT_ORDER = 'product_tab/block_sort_order';
    public const BLOCK_CUSTOMER_GROUPS = 'product_tab/customer_group';
    public const SHOW_ICON = 'product_tab/block_fileicon';
    public const SHOW_FILESIZE = 'product_tab/block_filesize';
    public const SHOW_IN_ORDER_VIEW = 'order_view/show_attachments';
    public const ORDER_VIEW_LABEL = 'order_view/label';
    public const ORDER_VIEW_ORDER_STATUS = 'order_view/order_status';
    public const ORDER_VIEW_SHOW_FILESIZE = 'order_view/filesize';
    public const ORDER_VIEW_SHOW_ICON = 'order_view/fileicon';
    public const ORDER_VIEW_ATTACHMENTS_FILTER = 'order_view/include_attachments_filter';
    public const SHOW_IN_ORDER_EMAIL = 'order_email/show_attachments';
    public const ORDER_EMAIL_LABEL = 'order_email/label';
    public const ORDER_EMAIL_ATTACHMENTS_FILTER = 'order_email/include_attachments_filter';
    public const ORDER_EMAIL_ORDER_STATUS = 'order_email/order_status';
    public const BLOCK_LOCATION = 'block/block_location';
    /**#@-*/

    /**
     * IsEnabled
     *
     * @return void
     */
    public function isEnabled()
    {
        return $this->isSetFlag(self::XPATH_ENABLED);
    }

    /**
     * GetBlockTitle
     *
     * @return void
     */
    public function getBlockTitle()
    {
        return $this->getValue(self::BLOCK_TITLE);
    }

    /**
     * GetUrlType
     *
     * @return void
     */
    public function getUrlType()
    {
        return (int)$this->getValue(self::URL_TYPE);
    }

    /**
     * GetBlockCustomerGroups
     *
     * @return void
     */
    public function getBlockCustomerGroups()
    {
        return $this->getValue(self::BLOCK_CUSTOMER_GROUPS);
    }

    /**
     * DetectMimeType
     *
     * @return void
     */
    public function detectMimeType()
    {
        return $this->isSetFlag(self::DETECT_MIME_TYPE);
    }

    /**
     * AddCategoriesFilesToProducts
     *
     * @return void
     */
    public function addCategoriesFilesToProducts()
    {
        return $this->isSetFlag(self::ADD_CATEGORIES_FILES_TO_PRODUCTS);
    }

    /**
     * IsBlockEnabled
     *
     * @return void
     */
    public function isBlockEnabled()
    {
        return $this->isSetFlag(self::BLOCK_ENABLED);
    }
    /**
     * GetBlockSortOrder
     *
     * @return void
     */
    public function getBlockSortOrder()
    {
        return (int)$this->getValue(self::BLOCK_SORT_ORDER);
    }

    /**
     * IsShowIcon
     *
     * @return void
     */
    public function isShowIcon()
    {
        return $this->isSetFlag(self::SHOW_ICON);
    }

    /**
     * IsShowFilesize
     *
     * @return void
     */
    public function isShowFilesize()
    {
        return $this->isSetFlag(self::SHOW_FILESIZE);
    }

    /**
     * IsShowInOrderView
     *
     * @return void
     */
    public function isShowInOrderView()
    {
        return $this->isSetFlag(self::SHOW_IN_ORDER_VIEW);
    }

    /**
     * GetLabelInOrderView
     *
     * @return void
     */
    public function getLabelInOrderView()
    {
        return $this->getValue(self::ORDER_VIEW_LABEL);
    }

    /**
     * GetViewOrderStatuses
     *
     * @return void
     */
    public function getViewOrderStatuses()
    {
        $orderStatuses = $this->getValue(self::ORDER_VIEW_ORDER_STATUS);
        if (empty($orderStatuses)) {
            return [];
        }

        return array_map('trim', explode(',', $orderStatuses));
    }

    /**
     * IsShowIconInOrderView
     *
     * @return void
     */
    public function isShowIconInOrderView()
    {
        return $this->isSetFlag(self::ORDER_VIEW_SHOW_ICON);
    }

    /**
     * IsShowFilesizeInOrderView
     *
     * @return void
     */
    public function isShowFilesizeInOrderView()
    {
        return $this->isSetFlag(self::ORDER_VIEW_SHOW_FILESIZE);
    }

    /**
     * GetViewAttachmentsFilter
     *
     * @return void
     */
    public function getViewAttachmentsFilter()
    {
        return (int)$this->getValue(self::ORDER_VIEW_ATTACHMENTS_FILTER);
    }

    /**
     * IsShowInOrderEmail
     *
     * @return void
     */
    public function isShowInOrderEmail()
    {
        return $this->isSetFlag(self::SHOW_IN_ORDER_EMAIL);
    }

    /**
     * GetLabelInOrderEmail
     *
     * @return void
     */
    public function getLabelInOrderEmail()
    {
        return $this->getValue(self::ORDER_EMAIL_LABEL);
    }

    /**
     * GetEmailAttachmentsFilter
     *
     * @return void
     */
    public function getEmailAttachmentsFilter()
    {
        return (int)$this->getValue(self::ORDER_EMAIL_ATTACHMENTS_FILTER);
    }

    /**
     * GetEmailOrderStatuses
     *
     * @return void
     */
    public function getEmailOrderStatuses()
    {
        $orderStatuses = $this->getValue(self::ORDER_EMAIL_ORDER_STATUS);
        if (empty($orderStatuses)) {
            return [];
        }

        return array_map('trim', explode(',', $orderStatuses));
    }

    /**
     * GetBlockLocation
     *
     * @return void
     */
    public function getBlockLocation()
    {
        return $this->getValue(self::BLOCK_LOCATION);
    }

    /**
     * ExcludeIncludeInOrderFiles
     *
     * @return void
     */
    public function excludeIncludeInOrderFiles()
    {
        return !$this->isSetFlag(self::EXCLUDE_INCLUDE_IN_ORDER_FILES);
    }
}
