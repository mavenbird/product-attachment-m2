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

namespace Mavenbird\ProductAttachment\Model\Import\Behaviors;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\Filesystem\Directory;
use Mavenbird\ProductAttachment\Model\Import\ImportFile;
use Mavenbird\ProductAttachment\Model\SourceOptions\AttachmentType;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

class Add implements \Mavenbird\Core\Model\Import\Behavior\BehaviorInterface
{
    /**
     * @var \Mavenbird\ProductAttachment\Api\Data\FileInterfaceFactory
     */
    private $fileFactory;

    /**
     * @var \Mavenbird\ProductAttachment\Api\FileRepositoryInterface
     */
    private $fileRepository;

    /**
     * @var \Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFileCollectionFactory
     */
    private $importFileCollectionFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * Construct
     *
     * @param \Mavenbird\ProductAttachment\Api\Data\FileInterfaceFactory $fileFactory
     * @param \Mavenbird\ProductAttachment\Api\FileRepositoryInterface $fileRepository
     * @param \Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFileCollectionFactory $importFileCollectionFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Mavenbird\ProductAttachment\Api\Data\FileInterfaceFactory $fileFactory,
        \Mavenbird\ProductAttachment\Api\FileRepositoryInterface $fileRepository,
        \Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFileCollectionFactory $importFileCollectionFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Registry $registry
    ) {
        $this->fileFactory = $fileFactory;
        $this->fileRepository = $fileRepository;
        $this->importFileCollectionFactory = $importFileCollectionFactory;
        $this->registry = $registry;
        $this->productRepository = $productRepository;
    }

    /**
     * @var array
     */
    private $mappingIds = [];

    /**
     * @var array
     */
    private $importFileIdsPath = [];

    /**
     * Execute
     *
     * @param array $importData
     * @return void
     */
    public function execute(array $importData)
    {
        if (empty($importData[0])) {
            return;
        }

        if (empty($this->importFileIdsPath)) {
            /** @var \Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFileCollection $importFileCollection */
            $importFileCollection = $this->importFileCollectionFactory->create();
            $importFileCollection->addFieldToFilter(ImportFile::IMPORT_ID, (int)$importData[0][ImportFile::IMPORT_ID]);
            $importFileCollection->addFieldToSelect(ImportFile::IMPORT_FILE_ID);
            $importFileCollection->addFieldToSelect(FileInterface::FILE_PATH);
            foreach ($importFileCollection->getData() as $row) {
                $this->importFileIdsPath[$row[ImportFile::IMPORT_FILE_ID]] =
                    Directory::DIRECTORY_CODES[Directory::IMPORT] . DIRECTORY_SEPARATOR
                    . (int)$importData[0][ImportFile::IMPORT_ID] . DIRECTORY_SEPARATOR
                    . $row[FileInterface::FILE_PATH];
            }
            if (!$this->registry->registry('file_import_id')) {
                $this->registry->register('file_import_id', (int)$importData[0][ImportFile::IMPORT_ID]);
            }
        }

        foreach ($importData as $row) {
            $file = $this->fileFactory->create();

            if (isset($this->mappingIds[$row[ImportFile::IMPORT_FILE_ID]])) {
                $file->setFileId($this->mappingIds[$row[ImportFile::IMPORT_FILE_ID]]);
            } else {
                if (!isset($this->importFileIdsPath[$row[ImportFile::IMPORT_FILE_ID]])) {
                    return;
                }

                $file->setData(
                    RegistryConstants::FILE_KEY,
                    [
                        [
                            'file' => $this->importFileIdsPath[$row[ImportFile::IMPORT_FILE_ID]],
                            'name' => basename($this->importFileIdsPath[$row[ImportFile::IMPORT_FILE_ID]]),
                            'tmp_name' => basename($this->importFileIdsPath[$row[ImportFile::IMPORT_FILE_ID]])
                        ]
                    ]
                );
            }
            $file->setAttachmentType(AttachmentType::FILE);

            if (!empty($row[FileInterface::LABEL])) {
                $file->setLabel($row[FileInterface::LABEL]);
            } else {
                $file->setLabel(null);
            }

            if (!empty($row[FileInterface::FILENAME])) {
                $file->setFileName($row[FileInterface::FILENAME]);
            } else {
                $file->setFileName(null);
            }

            if ($row[FileInterface::IS_VISIBLE] !== '' && $row[FileInterface::IS_VISIBLE] !== null) {
                $file->setIsVisible(!empty($row[FileInterface::IS_VISIBLE]));
            } else {
                $file->setData(FileInterface::IS_VISIBLE, null);
            }

            if ($row[FileInterface::INCLUDE_IN_ORDER] !== '' && $row[FileInterface::INCLUDE_IN_ORDER] !== null) {
                $file->setIsIncludeInOrder(!empty($row[FileInterface::INCLUDE_IN_ORDER]));
            } else {
                $file->setData(FileInterface::INCLUDE_IN_ORDER, null);
            }

            if ($row[FileInterface::CUSTOMER_GROUPS] !== '' && $row[FileInterface::CUSTOMER_GROUPS] !== null) {
                $customerGroups = explode(',', $row[FileInterface::CUSTOMER_GROUPS]);
                foreach ($customerGroups as &$customerGroup) {
                    $customerGroup = (int)$customerGroup;
                }
                $file->setData(FileInterface::CUSTOMER_GROUPS . '_output', implode(',', $customerGroups));
            } else {
                $file->setData(FileInterface::CUSTOMER_GROUPS . '_output', null);
            }

            if ($row[FileInterface::PRODUCTS] !== '' && $row[FileInterface::PRODUCTS] !== null) {
                $products = explode(',', $row[FileInterface::PRODUCTS]);
                foreach ($products as &$product) {
                    $product = (int)$product;
                }
                $file->setData(FileInterface::PRODUCTS, array_unique($products));
            } else {
                $file->setData(FileInterface::PRODUCTS, null);
                $file->setData('use_default_products', true);
            }

            if (isset($row['product_skus']) && $row['product_skus'] !== '' && $row['product_skus'] !== null) {
                $productSkus = explode(',', $row['product_skus']);
                $products = [];

                foreach ($productSkus as $sku) {
                    try {
                        $products[] = (int)$this->productRepository->get(trim($sku))->getId();
                    } catch (LocalizedException $e) {
                        null;
                    }
                }

                if (!empty($products)) {
                    if (is_array($file->getData(FileInterface::PRODUCTS))) {
                        $products = array_merge($file->getData(FileInterface::PRODUCTS), $products);
                    }

                    $file->setData(FileInterface::PRODUCTS, $products);
                    $file->setData('use_default_products', false);
                }
            }

            if ($row[FileInterface::CATEGORIES] !== '' && $row[FileInterface::CATEGORIES] !== null) {
                $categories = explode(',', $row[FileInterface::CATEGORIES]);
                foreach ($categories as &$category) {
                    $category = (int)$category;
                }
                $file->setData(FileInterface::CATEGORIES, array_unique($categories));
            } else {
                $file->setData(FileInterface::CATEGORIES, null);
                $file->setData('use_default_categories', true);
            }

            $file = $this->fileRepository->saveAll(
                $file,
                [RegistryConstants::STORE => (int)$row['store_id']]
            );
            if (!isset($this->mappingIds[$row[ImportFile::IMPORT_FILE_ID]])) {
                $this->mappingIds[$row[ImportFile::IMPORT_FILE_ID]] = $file->getFileId();
            }
        }
    }
}
