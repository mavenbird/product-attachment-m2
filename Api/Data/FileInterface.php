<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Api\Data;

/**
 * @method mixed getData($key = '', $index = null)
 * @method $this setData($key = '', $value = null)
 */
interface FileInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    public const FILE_ID = 'file_id';
    public const ATTACHMENT_TYPE = 'attachment_type';
    public const FILE_PATH = 'filepath';
    public const LINK = 'link';
    public const EXTENSION = 'extension';
    public const SIZE = 'size';
    public const MIME_TYPE = 'mime_type';
    public const FILENAME = 'filename';
    public const LABEL = 'label';
    public const IS_VISIBLE = 'is_visible';
    public const INCLUDE_IN_ORDER = 'include_in_order';
    public const CUSTOMER_GROUPS = 'customer_groups';
    public const CATEGORIES = 'category_ids';
    public const PRODUCTS = 'product_ids';
    public const ICON_URL = 'icon_url';
    public const FRONTEND_URL = 'frontend_url';
    public const URL_HASH = 'url_hash';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    /**#@-*/

    /**
     * GetFileId
     *
     * @return void
     */
    public function getFileId();

    /**
     * SetFileId
     *
     * @return void
     */
    public function setFileId($fileId);

    /**
     * GetAttachmentType
     *
     * @return void
     */
    public function getAttachmentType();

    /**
     * SetAttachmentType
     *
     * @return void
     */
    public function setAttachmentType($attachmentType);

    /**
     * GetFilePath
     *
     * @return void
     */
    public function getFilePath();

    /**
     * SetFilePath
     *
     * @return void
     */
    public function setFilePath($filePath);

    /**
     * GetLink
     *
     * @return void
     */
    public function getLink();

    /**
     * SetLink
     *
     * @return void
     */
    public function setLink($link);

    /**
     * GetFileExtension
     *
     * @return void
     */
    public function getFileExtension();

    /**
     * SetFileExtension
     *
     * @return void
     */
    public function setFileExtension($extension);

    /**
     * GetMimeType
     *
     * @return void
     */
    public function getMimeType();

    /**
     * SetMimeType
     *
     * @return void
     */
    public function setMimeType($mimeType);

    /**
     * GetFileSize
     *
     * @return void
     */
    public function getFileSize();

    /**
     * SetFileSize
     *
     * @return void
     */
    public function setFileSize($fileSize);

    /**
     * GetFileName
     *
     * @return void
     */
    public function getFileName();

    /**
     * SetFileName
     *
     * @param [type] $fileName
     * @return void
     */
    public function setFileName($fileName);

    /**
     * GetLabel
     *
     * @return void
     */
    public function getLabel();

    /**
     * SetLabel
     *
     * @param [type] $label
     * @return void
     */
    public function setLabel($label);

    /**
     * GetCustomerGroups
     *
     * @return void
     */
    public function getCustomerGroups();

    /**
     * SetCustomerGroups
     *
     * @param [type] $customerGroups
     * @return void
     */
    public function setCustomerGroups($customerGroups);

    /**
     * IsVisible
     *
     * @return boolean
     */
    public function isVisible();

    /**
     * SetIsVisible
     *
     * @param [type] $isVisible
     * @return void
     */
    public function setIsVisible($isVisible);

    /**
     * IsIncludeInOrder
     *
     * @return boolean
     */
    public function isIncludeInOrder();

    /**
     * SetIsIncludeInOrder
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function setIsIncludeInOrder($isIncludeInOrder);

    /**
     * GetIconUrl
     *
     * @return void
     */
    public function getIconUrl();

    /**
     * SetIconUrl
     *
     * @param [type] $iconUrl
     * @return void
     */
    public function setIconUrl($iconUrl);

    /**
     * GetFrontendUrl
     *
     * @return void
     */
    public function getFrontendUrl();

    /**
     * SetFrontendUrl
     *
     * @param [type] $frontendUrl
     * @return void
     */
    public function setFrontendUrl($frontendUrl);

    /**
     * GetUrlHash
     *
     * @return void
     */
    public function getUrlHash();

    /**
     * SetUrlHash
     *
     * @param [type] $urlHash
     * @return void
     */
    public function setUrlHash($urlHash);

    /**
     * GetCreatedAt
     *
     * @return void
     */
    public function getCreatedAt();

    /**
     * GetUpdatedAt
     *
     * @return void
     */
    public function getUpdatedAt();
}
