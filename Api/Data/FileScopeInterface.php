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

namespace Mavenbird\ProductAttachment\Api\Data;

interface FileScopeInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    public const FILE_STORE_ID = 'file_store_id';
    public const FILE_STORE_CATEGORY_ID = 'file_store_category_id';
    public const FILE_STORE_PRODUCT_ID = 'file_store_product_id';
    public const FILE_STORE_CATEGORY_PRODUCT_ID = 'file_store_category_product_id';
    public const FILE_ID = 'file_id';
    public const STORE_ID = 'store_id';
    public const PRODUCT_ID = 'product_id';
    public const CATEGORY_ID = 'category_id';
    public const FILENAME = 'filename';
    public const LABEL = 'label';
    public const IS_VISIBLE = 'is_visible';
    public const INCLUDE_IN_ORDER = 'include_in_order';
    public const CUSTOMER_GROUPS = 'customer_groups';
    public const POSITION = 'position';
    /**#@-*/
}
