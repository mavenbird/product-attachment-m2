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

use Mavenbird\ProductAttachment\Api\Data\FileScopeInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\ConfigProvider;
use Mavenbird\ProductAttachment\Model\File\FileScope\DataProviders\Product as ProductDataProvider;
use Mavenbird\ProductAttachment\Model\File\FileScope\DataProviders\ProductCategories;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Mavenbird\ProductAttachment\Model\File\Repository;

class Product implements \Mavenbird\ProductAttachment\Model\File\FileScope\DataProviders\FileScopeDataInterface
{
    /**
     * ProductDataProviders
     *
     * @var [type]
     */
    private $productDataProvider;

    /**
     * File
     *
     * @var [type]
     */
    private $fileDataProvider;

    /**
     * ProductCategories
     *
     * @var [type]
     */
    private $productCategoriesDataProvider;

    /**
     * ConfigProviders
     *
     * @var [type]
     */
    private $configProvider;

    /**
     * ProductRepositoryInterface
     *
     * @var [type]
     */
    private $productRepository;

    /**
     * Repository
     *
     * @var [type]
     */
    private $fileRepository;

    /**
     * Construct
     *
     * @param ProductDataProvider $productDataProvider
     * @param ProductCategories $productCategoriesDataProvider
     * @param ProductRepositoryInterface $productRepository
     * @param File $fileDataProvider
     * @param Repository $fileRepository
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        ProductDataProvider $productDataProvider,
        ProductCategories $productCategoriesDataProvider,
        ProductRepositoryInterface $productRepository,
        File $fileDataProvider,
        Repository $fileRepository,
        ConfigProvider $configProvider
    ) {
        $this->productDataProvider = $productDataProvider;
        $this->fileDataProvider = $fileDataProvider;
        $this->productCategoriesDataProvider = $productCategoriesDataProvider;
        $this->configProvider = $configProvider;
        $this->productRepository = $productRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute($params)
    {
        $result = [];
        $fileIds = [];
        if ($productFiles = $this->productDataProvider->execute($params)) {
            foreach ($productFiles as $productFile) {
                /** @var \Mavenbird\ProductAttachment\Model\File\File $file */
                $file = $this->fileRepository->getById($productFile[FileScopeInterface::FILE_ID]);
                $file->addData($productFile);
                $fileIds[] = $file->getFileId();
                if ($file = $this->fileDataProvider->processFileParams($file, $params)) {
                    $result[] = $file;
                }
            }
        }

        if ($this->configProvider->addCategoriesFilesToProducts()) {
            $params[RegistryConstants::EXCLUDE_FILES] = $fileIds;
            if ($product = $this->productRepository->getById($params[RegistryConstants::PRODUCT])) {
                $params[RegistryConstants::PRODUCT_CATEGORIES] = $product->getCategoryIds();
                if ($productCategoriesFiles = $this->productCategoriesDataProvider->execute($params)) {
                    foreach ($productCategoriesFiles as $productCategoryFile) {
                        /** @var \Mavenbird\ProductAttachment\Model\File\File $file */
                        $file = $this->fileRepository->getById($productCategoryFile[FileScopeInterface::FILE_ID]);
                        $file->addData($productCategoryFile);
                        if ($file = $this->fileDataProvider->processFileParams($file, $params)) {
                            $result[] = $file;
                        }
                    }
                }
            }
        }

        return $result;
    }
}
