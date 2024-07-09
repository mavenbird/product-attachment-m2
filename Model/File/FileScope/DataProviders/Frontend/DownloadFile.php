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

namespace Mavenbird\ProductAttachment\Model\File\FileScope\DataProviders\Frontend;

use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;

class DownloadFile implements \Mavenbird\ProductAttachment\Model\File\FileScope\DataProviders\FileScopeDataInterface
{
    /**
     * @var File
     */
    private $fileDataProvider;

    /**
     * @var Category
     */
    private $categoryDataProvider;

    /**
     * @var Product
     */
    private $productDataProvider;

    /**
     * Construct
     *
     * @param File $fileDataProvider
     * @param Category\Proxy $categoryDataProvider
     * @param Product\Proxy $productDataProvider
     */
    public function __construct(
        File $fileDataProvider,
        Category\Proxy $categoryDataProvider,
        Product\Proxy $productDataProvider
    ) {
        $this->fileDataProvider = $fileDataProvider;
        $this->categoryDataProvider = $categoryDataProvider;
        $this->productDataProvider = $productDataProvider;
    }

    /**
     * Execute
     *
     * @param [type] $params
     * @return void
     */
    public function execute($params)
    {
        /** @var \Mavenbird\ProductAttachment\Api\Data\FileInterface $file */
        $file = $params[RegistryConstants::FILE];
        if (!empty($params[RegistryConstants::CATEGORY])) {
            $categoryFiles = $this->categoryDataProvider->execute($params);
            foreach ($categoryFiles as $categoryFile) {
                if ($categoryFile->getFileId() === $file->getFileId()) {
                    return $this->fileDataProvider->processFileParams($categoryFile, $params);
                }
            }
        } elseif (!empty($params[RegistryConstants::PRODUCT])) {
            $productFiles = $this->productDataProvider->execute($params);
            foreach ($productFiles as $productFile) {
                if ($productFile->getFileId() === $file->getFileId()) {
                    return $this->fileDataProvider->processFileParams($productFile, $params);
                }
            }
        }

        return $this->fileDataProvider->execute($params);
    }
}
