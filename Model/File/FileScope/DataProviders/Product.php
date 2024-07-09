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

namespace Mavenbird\ProductAttachment\Model\File\FileScope\DataProviders;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Api\Data\FileScopeInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStoreProduct;
use Mavenbird\ProductAttachment\Model\Icon\GetIconForFile;

class Product implements FileScopeDataInterface
{
    /**
     * @var GetIconForFile
     */
    private $getIconForFile;

    /**
     * @var FileStoreProduct
     */
    private $fileStoreProduct;
    
    /**
     * Construct
     *
     * @param GetIconForFile $getIconForFile
     * @param FileStoreProduct $fileStoreProduct
     */
    public function __construct(
        GetIconForFile $getIconForFile,
        FileStoreProduct $fileStoreProduct
    ) {
        $this->getIconForFile = $getIconForFile;
        $this->fileStoreProduct = $fileStoreProduct;
    }

    /**
     * @inheritdoc
     */
    public function execute($params)
    {
        $productId = $params[RegistryConstants::PRODUCT];
        $store = $params[RegistryConstants::STORE];

        $fileStoreProducts = $this->fileStoreProduct->getFilesIdsByStoreId($productId, $store);

        $result = [];
        if (!empty($fileStoreProducts)) {
            foreach ($fileStoreProducts as $product) {
                if (!empty($result[$product[FileScopeInterface::FILE_ID]])) {
                    continue;
                }
                $row = [];
                $row[FileScopeInterface::FILE_ID] = $row['show_file_id'] = $product[FileScopeInterface::FILE_ID];
                $row['icon'] = $this->getIconForFile->byFileExtension($product[FileInterface::EXTENSION]);
                $row[FileInterface::EXTENSION] = $product[FileInterface::EXTENSION];
                foreach (RegistryConstants::USE_DEFAULT_FIELDS as $field) {
                    if ($product[$field] === null || ($store && empty($product[FileScopeInterface::STORE_ID]))) {
                        $row[$field . '_use_defaults'] = 1;
                        if ($product['default_' . $field] !== null) {
                            $row[$field] = $product['default_' . $field];
                        } elseif (isset($product['prod0_default_' . $field])
                            && $product['prod0_default_' . $field] !== null
                        ) {
                            $row[$field] = $product['prod0_default_' . $field];
                        } else {
                            $row[$field] = $product['super_default_' . $field];
                        }
                    } else {
                        $row[$field] = $product[$field];
                        $row[$field . '_use_defaults'] = 0;
                    }
                }

                $result[$row[FileScopeInterface::FILE_ID]] = $row;
            }
        }

        return array_merge($result);
    }
}
