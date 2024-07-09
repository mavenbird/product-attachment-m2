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
use Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStoreCategory;
use Mavenbird\ProductAttachment\Model\Icon\GetIconForFile;

class Category implements FileScopeDataInterface
{
    /**
     * @var GetIconForFile
     */
    private $getIconForFile;

    /**
     * @var FileStoreCategory
     */
    private $fileStoreCategory;
    
    /**
     * Construct
     *
     * @param GetIconForFile $getIconForFile
     * @param FileStoreCategory $fileStoreCategory
     */
    public function __construct(
        GetIconForFile $getIconForFile,
        FileStoreCategory $fileStoreCategory
    ) {
        $this->getIconForFile = $getIconForFile;
        $this->fileStoreCategory = $fileStoreCategory;
    }

    /**
     * @inheritdoc
     */
    public function execute($params)
    {
        $categoryId = $params[RegistryConstants::CATEGORY];
        $store = $params[RegistryConstants::STORE];

        $fileStoreCategories = $this->fileStoreCategory->getFilesIdsByStoreId($categoryId, $store);

        $result = [];
        if (!empty($fileStoreCategories)) {
            foreach ($fileStoreCategories as $category) {
                if (!empty($result[$category[FileScopeInterface::FILE_ID]])) {
                    continue;
                }
                $row = [];
                $row[FileScopeInterface::FILE_ID] = $row['show_file_id'] = $category[FileScopeInterface::FILE_ID];
                $row['icon'] = $this->getIconForFile->byFileExtension($category[FileInterface::EXTENSION]);
                $row[FileInterface::EXTENSION] = $category[FileInterface::EXTENSION];
                $row[FileScopeInterface::POSITION] = $category[FileScopeInterface::POSITION];
                foreach (RegistryConstants::USE_DEFAULT_FIELDS as $field) {
                    if ($category[$field] === null || ($store && empty($category[FileScopeInterface::STORE_ID]))) {
                        $row[$field . '_use_defaults'] = 1;
                        if ($category['default_' . $field] !== null) {
                            $row[$field] = $category['default_' . $field];
                        } elseif (isset($category['cat0_default_' . $field])
                            && $category['cat0_default_' . $field] !== null
                        ) {
                            $row[$field] = $category['cat0_default_' . $field];
                        } else {
                            $row[$field] = $category['super_default_' . $field];
                        }
                    } else {
                        $row[$field] = $category[$field];
                        $row[$field . '_use_defaults'] = 0;
                    }
                }

                $result[$row[FileScopeInterface::FILE_ID]] = $row;
            }
        }

        return array_merge($result);
    }
}
