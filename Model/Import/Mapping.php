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

namespace Mavenbird\ProductAttachment\Model\Import;

use Mavenbird\Core\Model\Import\Mapping\Mapping as MappingBase;
use Mavenbird\ProductAttachment\Api\Data\FileInterface;

class Mapping extends MappingBase implements \Mavenbird\Core\Model\Import\Mapping\MappingInterface
{
    /**
     * @var array
     */
    protected $mappings = [
        ImportFile::IMPORT_FILE_ID,
        ImportFile::IMPORT_ID,
        'store_id',
        FileInterface::FILENAME,
        FileInterface::LABEL,
        FileInterface::CUSTOMER_GROUPS,
        FileInterface::IS_VISIBLE,
        FileInterface::INCLUDE_IN_ORDER,
        FileInterface::PRODUCTS,
        FileInterface::CATEGORIES,
        'product_skus'
    ];

    /**
     * @var string
     */
    protected $masterAttributeCode = ImportFile::IMPORT_FILE_ID;
}
