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

namespace Mavenbird\ProductAttachment\Controller\Adminhtml;

use Mavenbird\ProductAttachment\Api\Data\IconInterface;
use Mavenbird\ProductAttachment\Api\Data\FileInterface;

class RegistryConstants
{
    /**#@+
     * Constants defined for dataPersistor
     */
    public const ICON_DATA = 'iconData';
    public const FILE_DATA = 'fileData';
    /**#@-*/

    /**#@+
     * Constants defined for custom fields
     */
    public const ICON_FILE_KEY = IconInterface::IMAGE . 'file';
    public const FILE_KEY = 'file';
    /**#@-*/

    /**#@+
     * Constants defined for form url ids
     */
    public const FORM_ICON_ID = 'icon_id';
    public const FORM_FILE_ID = 'file_id';
    /**#@-*/

    /**#@+
     * Constants defined for FileScopeDataProvider keys
     */
    public const FILE = 'file';
    public const FILES = 'files';
    public const FILE_IDS = 'file_ids';
    public const FILE_IDS_ORDER = 'file_ids_order';
    public const FILES_LIMIT = 'files_limit';
    public const STORE = 'store';
    public const CATEGORY = 'category';
    public const PRODUCT = 'product';
    public const PRODUCT_CATEGORIES = 'product_categories';
    public const EXTRA_URL_PARAMS = 'url_params';
    public const INCLUDE_FILTER = 'include_filter';
    public const CUSTOMER_GROUP = 'customer_group';
    public const TO_DELETE = 'to_delete';
    public const EXCLUDE_FILES = 'exclude_files';
    /**#@-*/

    public const USE_DEFAULT_FIELDS = [
        FileInterface::FILENAME,
        FileInterface::LABEL,
        FileInterface::IS_VISIBLE,
        FileInterface::INCLUDE_IN_ORDER,
        FileInterface::CUSTOMER_GROUPS,
    ];

    public const USE_DEFAULT_PREFIX = 'set_use_default_';

    public const ICON_EXTENSIONS = ['jpg', 'png', 'jpeg', 'bmp'];
}
