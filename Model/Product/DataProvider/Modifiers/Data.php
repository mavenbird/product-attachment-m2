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

namespace Mavenbird\ProductAttachment\Model\Product\DataProvider\Modifiers;

use Mavenbird\ProductAttachment\Api\Data\FileScopeInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\ConfigProvider;
use Mavenbird\ProductAttachment\Model\File\FileScope\FileScopeDataProviderInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;

class Data
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var FileScopeDataProviderInterface
     */
    private $fileScopeDataProvider;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * Construct
     *
     * @param LocatorInterface $locator
     * @param FileScopeDataProviderInterface $fileScopeDataProvider
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        LocatorInterface $locator,
        FileScopeDataProviderInterface $fileScopeDataProvider,
        ConfigProvider $configProvider
    ) {
        $this->locator = $locator;
        $this->fileScopeDataProvider = $fileScopeDataProvider;
        $this->configProvider = $configProvider;
    }

    /**
     * Execute
     *
     * @param array $data
     * @return void
     */
    public function execute(array $data)
    {
        $data[$this->locator->getProduct()->getId()]['attachments']['files'] = $this->fileScopeDataProvider->execute(
            [
                RegistryConstants::STORE => $this->locator->getStore()->getId(),
                RegistryConstants::PRODUCT => $this->locator->getProduct()->getId()
            ],
            'product'
        );
        if ($this->configProvider->addCategoriesFilesToProducts()) {
            $productFileIds = [];
            foreach ($data[$this->locator->getProduct()->getId()]['attachments']['files'] as $file) {
                $productFileIds[] = $file[FileScopeInterface::FILE_ID];
            }

            $data[$this->locator->getProduct()->getId()]['categories_attachments']['categories_files'] =
                $this->fileScopeDataProvider->execute(
                    [
                        RegistryConstants::STORE => $this->locator->getStore()->getId(),
                        RegistryConstants::PRODUCT => $this->locator->getProduct()->getId(),
                        RegistryConstants::PRODUCT_CATEGORIES => $this->locator->getProduct()->getCategoryIds(),
                        RegistryConstants::EXCLUDE_FILES => $productFileIds
                    ],
                    'productCategories'
                );
        }

        return $data;
    }
}
