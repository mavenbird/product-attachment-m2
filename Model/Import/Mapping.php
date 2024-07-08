<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
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
