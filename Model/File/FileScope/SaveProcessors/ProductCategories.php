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

namespace Mavenbird\ProductAttachment\Model\File\FileScope\SaveProcessors;

use Mavenbird\ProductAttachment\Api\Data\FileScopeInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStoreCategoryProduct;

class ProductCategories implements FileScopeSaveProcessorInterface
{
    /**
     * @var FileStoreCategoryProduct
     */
    private $fileStoreCategoryProduct;
    
    /**
     * Construct
     *
     * @param FileStoreCategoryProduct $fileStoreCategoryProduct
     */
    public function __construct(
        FileStoreCategoryProduct $fileStoreCategoryProduct
    ) {
        $this->fileStoreCategoryProduct = $fileStoreCategoryProduct;
    }

    /**
     * @inheritdoc
     */
    public function execute($params)
    {
        $storeId = $params[RegistryConstants::STORE];

        if ($files = $params[RegistryConstants::FILES]) {
            foreach ($files as $file) {
                $fileStoreProductCategory = $this->fileStoreCategoryProduct->getProductCategoryStoreFile(
                    $file[FileScopeInterface::FILE_ID],
                    $params[RegistryConstants::PRODUCT],
                    $file[FileScopeInterface::CATEGORY_ID],
                    $storeId
                );
                if (!$fileStoreProductCategory) {
                    $fileStoreProductCategory = [];
                }

                foreach (RegistryConstants::USE_DEFAULT_FIELDS as $field) {
                    if ($file[$field . '_use_defaults'] === 'true' || $file[$field . '_use_defaults'] === '1') {
                        $fileStoreProductCategory[$field] = null;
                    } elseif ($field === 'customer_groups') {
                        $fileStoreProductCategory[$field] = $file[$field . '_output'];
                    } else {
                        $fileStoreProductCategory[$field] = $file[$field];
                    }
                }
                $fileStoreProductCategory[FileScopeInterface::FILE_ID] = (int)$file[FileScopeInterface::FILE_ID];
                $fileStoreProductCategory[FileScopeInterface::POSITION] = isset($file[FileScopeInterface::POSITION]) ?
                    (int)$file[FileScopeInterface::POSITION]
                    : 0;
                $fileStoreProductCategory[FileScopeInterface::PRODUCT_ID] = $params[RegistryConstants::PRODUCT];
                $fileStoreProductCategory[FileScopeInterface::PRODUCT_ID] = $params[RegistryConstants::PRODUCT];
                $fileStoreProductCategory[FileScopeInterface::CATEGORY_ID] = $file[FileScopeInterface::CATEGORY_ID];
                $fileStoreProductCategory[FileScopeInterface::STORE_ID] = $storeId;

                $this->fileStoreCategoryProduct->saveProductCategoryStoreFile($fileStoreProductCategory);
            }
        }
    }
}
