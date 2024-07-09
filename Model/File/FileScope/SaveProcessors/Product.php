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

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Api\Data\FileScopeInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\SourceOptions\AttachmentType;

class Product implements FileScopeSaveProcessorInterface
{
    /**
     * @var \Mavenbird\ProductAttachment\Model\File\FileFactory
     */
    private $fileFactory;

    /**
     * @var \Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStoreProduct
     */
    private $fileStoreProduct;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \Mavenbird\ProductAttachment\Model\File\Repository
     */
    private $fileRepository;
    
    /**
     * Construct
     *
     * @param \Mavenbird\ProductAttachment\Model\File\FileFactory $fileFactory
     * @param \Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStoreProduct $fileStoreProduct
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Mavenbird\ProductAttachment\Model\File\Repository\Proxy $fileRepository
     */
    public function __construct(
        \Mavenbird\ProductAttachment\Model\File\FileFactory $fileFactory,
        \Mavenbird\ProductAttachment\Model\File\FileScope\ResourceModel\FileStoreProduct $fileStoreProduct,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\RequestInterface $request,
        \Mavenbird\ProductAttachment\Model\File\Repository\Proxy $fileRepository
    ) {
        $this->fileFactory = $fileFactory;
        $this->fileStoreProduct = $fileStoreProduct;
        $this->messageManager = $messageManager;
        $this->request = $request;
        $this->fileRepository = $fileRepository;
    }

    /**
     * Execute
     *
     * @param [type] $params
     * @return void
     */
    public function execute($params)
    {
        $storeId = isset($params[RegistryConstants::STORE]) ? (int)$params[RegistryConstants::STORE]
            : (int)$this->request->getParam('store');

        $toDelete = [];
        if (!empty($params[RegistryConstants::TO_DELETE])) {
            $toDelete = $params[RegistryConstants::TO_DELETE];
        }

        if ($files = $params[RegistryConstants::FILES]) {
            foreach ($files as $file) {
                if (!empty($file['file']) || !empty($file['link'])) {
                    /** @var \Mavenbird\ProductAttachment\Model\File\File $newFile */
                    $newFile = $this->fileFactory->create();

                    if (!empty($file['file'])) {
                        $tmpFile = [];
                        $tmpFile[0]['file'] = $file['file'];
                        $tmpFile[0]['tmp_name'] = $tmpFile[0]['name'] = true;
                        $file[RegistryConstants::FILE_KEY] = $tmpFile;
                        $file[FileInterface::ATTACHMENT_TYPE] = AttachmentType::FILE;
                    } else {
                        $file[FileInterface::ATTACHMENT_TYPE] = AttachmentType::LINK;
                    }

                    $file[FileInterface::PRODUCTS] = [$params[RegistryConstants::PRODUCT]];
                    $file[FileInterface::FILE_ID] = null;
                    $newFile->addData($file);
                    try {
                        $this->fileRepository->saveAll($newFile, [RegistryConstants::STORE => $storeId]);
                    } catch (\Magento\Framework\Exception\CouldNotSaveException $e) {
                        $this->messageManager->addErrorMessage(__('Couldn\'t save file'));
                    }
                } else {
                    unset($toDelete[$file[FileScopeInterface::FILE_ID]]);
                    $fileStoreProduct = $this->fileStoreProduct->getProductStoreFile(
                        $file[FileScopeInterface::FILE_ID],
                        $params[RegistryConstants::PRODUCT],
                        $storeId
                    );
                    if (!$fileStoreProduct) {
                        $fileStoreProduct = [];
                    }

                    foreach (RegistryConstants::USE_DEFAULT_FIELDS as $field) {
                        if ($file[$field . '_use_defaults'] === 'true' || $file[$field . '_use_defaults'] === '1') {
                            $fileStoreProduct[$field] = null;
                        } elseif ($field === 'customer_groups') {
                            $fileStoreProduct[$field] = $file[$field . '_output'];
                        } else {
                            $fileStoreProduct[$field] = $file[$field];
                        }
                    }
                    $fileStoreProduct[FileScopeInterface::POSITION] = isset($file[FileScopeInterface::POSITION]) ?
                        (int)$file[FileScopeInterface::POSITION]
                        : 0;
                    $fileStoreProduct[FileScopeInterface::PRODUCT_ID] = $params[RegistryConstants::PRODUCT];
                    $fileStoreProduct[FileScopeInterface::FILE_ID] = $file[FileScopeInterface::FILE_ID];
                    $fileStoreProduct[FileScopeInterface::STORE_ID] = $storeId;
                    if ($storeId
                        && $this->fileStoreProduct->isAllStoreViewFile($file[FileScopeInterface::FILE_ID], $storeId)
                    ) {
                        $fileProducts = $this->fileStoreProduct->getStoreProductIdsByStoreId(
                            $file[FileScopeInterface::FILE_ID],
                            0
                        );
                        unset($fileProducts[$params[RegistryConstants::PRODUCT]]);
                        foreach ($fileProducts as $fileProduct) {
                            $fileProduct[FileScopeInterface::STORE_ID] = $storeId;
                            $fileProduct[FileScopeInterface::FILE_ID] = $file[FileScopeInterface::FILE_ID];
                            $this->fileStoreProduct->insertFileStoreProductData($fileProduct);
                        }
                    }
                    $this->fileStoreProduct->saveFileStoreProduct($fileStoreProduct);
                }
            }
        }

        if (!empty($toDelete)) {
            foreach (array_keys($toDelete) as $fileId) {
                if (!$storeId) {
                    $this->fileStoreProduct->deleteFileByStoreProduct(
                        $fileId,
                        $params[RegistryConstants::PRODUCT],
                        $storeId
                    );
                } else {
                    $isAllStoreViewFile = $this->fileStoreProduct->isAllStoreViewFile($fileId, $storeId);
                    if ($isAllStoreViewFile) {
                        $fileProducts = $this->fileStoreProduct->getStoreProductIdsByStoreId(
                            $fileId,
                            0
                        );
                        unset($fileProducts[$params[RegistryConstants::PRODUCT]]);
                        if ($fileProducts) {
                            foreach ($fileProducts as $fileProduct) {
                                $fileProduct[FileScopeInterface::STORE_ID] = $storeId;
                                $fileProduct[FileScopeInterface::FILE_ID] = $fileId;
                                $this->fileStoreProduct->insertFileStoreProductData($fileProduct);
                            }
                        } else {
                            $this->fileStoreProduct->insertFileStoreProductData([
                                FileScopeInterface::STORE_ID => $storeId,
                                FileScopeInterface::FILE_ID => $fileId,
                                FileScopeInterface::PRODUCT_ID => 0
                            ]);
                        }
                    } else {
                        $this->fileStoreProduct->deleteFileByStoreProduct(
                            $fileId,
                            $params[RegistryConstants::PRODUCT],
                            $storeId
                        );
                    }
                }
            }
        }
    }
}
