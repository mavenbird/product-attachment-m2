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

namespace Mavenbird\ProductAttachment\Controller\File;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Api\FileRepositoryInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\ConfigProvider;
use Mavenbird\ProductAttachment\Model\File\FileScope\FileScopeDataProvider;
use Mavenbird\ProductAttachment\Model\Filesystem\Directory;
use Mavenbird\ProductAttachment\Model\Report\ItemFactory;
use Mavenbird\ProductAttachment\Model\SourceOptions\DownloadSource;
use Mavenbird\ProductAttachment\Model\SourceOptions\OrderFilterType;
use Mavenbird\ProductAttachment\Model\SourceOptions\UrlType;
use Magento\Customer\Model\Session;
use Magento\Downloadable\Helper\Download as DownloadHelper;
use Magento\Framework\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;

class Download extends Action\Action
{
    /**
     * @var FileScopeDataProvider
     */
    private $fileScopeDataProvider;

    /**
     * @var FileRepositoryInterface
     */
    private $fileRepository;

    /**
     * @var DownloadHelper
     */
    private $downloadHelper;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var ItemFactory
     */
    private $reportItemFactory;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Construct
     *
     * @param FileRepositoryInterface $fileRepository
     * @param FileScopeDataProvider $fileScopeDataProvider
     * @param DownloadHelper $downloadHelper
     * @param ConfigProvider $configProvider
     * @param StoreManagerInterface $storeManager
     * @param ItemFactory $reportItemFactory
     * @param Session $customerSession
     * @param Action\Context $context
     */
    public function __construct(
        FileRepositoryInterface $fileRepository,
        FileScopeDataProvider $fileScopeDataProvider,
        DownloadHelper $downloadHelper,
        ConfigProvider $configProvider,
        StoreManagerInterface $storeManager,
        ItemFactory $reportItemFactory,
        Session $customerSession,
        Action\Context $context
    ) {
        parent::__construct($context);
        $this->fileScopeDataProvider = $fileScopeDataProvider;
        $this->fileRepository = $fileRepository;
        $this->downloadHelper = $downloadHelper;
        $this->configProvider = $configProvider;
        $this->reportItemFactory = $reportItemFactory;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
    }

    /**
     * ProcessFile
     *
     * @param [type] $file
     * @return void
     */
    public function processFile($file)
    {
        if ($file->getAttachmentType() == \Mavenbird\ProductAttachment\Model\SourceOptions\AttachmentType::FILE) {
            $this->downloadHelper->setResource(
                Directory::DIRECTORY_CODES[Directory::ATTACHMENT] . DIRECTORY_SEPARATOR . $file->getFilePath(),
                DownloadHelper::LINK_TYPE_FILE
            );
        } else {
            $this->downloadHelper->setResource(
                $file->getLink(),
                DownloadHelper::LINK_TYPE_URL
            );
        }
        if ($this->configProvider->detectMimeType() && !empty($file->getMimeType())) {
            $contentType = $file->getMimeType();
        } else {
            $contentType = 'application/octet-stream';
        }

        $this->getResponse()->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true);

        if ($file->getAttachmentType() == \Mavenbird\ProductAttachment\Model\SourceOptions\AttachmentType::FILE) {
            $this->getResponse()->setHeader('Content-Length', $file->getFileSize());
        } else {
            if ($fileSize = $this->downloadHelper->getFileSize()) {
                $this->getResponse()->setHeader('Content-Length', $fileSize);
            }
        }

        if ($contentDisposition = $this->downloadHelper->getContentDisposition()) {
            $this->getResponse()->setHeader(
                'Content-Disposition',
                $contentDisposition . '; filename=' . $file->getFileName() . '.' . $file->getFileExtension()
            );
        }

        $this->getResponse()->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
        $this->getResponse()->clearBody();
        $this->getResponse()->sendHeaders();
        $this->downloadHelper->output();       
        exit(0);
    }
    
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $fileId = $this->getRequest()->getParam('file', 0);
        if ($fileId) {
            try {
                if ($this->configProvider->getUrlType() === UrlType::ID) {
                    $file = $this->fileRepository->getById((int)$fileId);
                } else {
                    $file = $this->fileRepository->getByHash($fileId);
                }

                $params = [
                    RegistryConstants::STORE => $this->storeManager->getStore()->getId(),
                    RegistryConstants::FILE => $file,
                    RegistryConstants::INCLUDE_FILTER => OrderFilterType::ALL_ATTACHMENTS
                ];
                if ($categoryId = $this->getRequest()->getParam('category')) {
                    $params[RegistryConstants::CATEGORY] = (int)$categoryId;
                } elseif ($productId = $this->getRequest()->getParam('product')) {
                    $params[RegistryConstants::PRODUCT] = (int)$productId;
                }
                $file = $this->fileScopeDataProvider->execute($params, 'downloadFile');

                if ($file) {
                    $this->saveStat();
                    try {
                        $this->processFile($file);
                    } catch (\Magento\Framework\Exception\LocalizedException $e) {
                        null;
                    }
                }
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                null;
            }
        }

        return $this->resultFactory->create(ResultFactory::TYPE_FORWARD)->forward('noroute');
    }
    
    /**
     * SaveStat
     *
     * @return void
     */
    public function saveStat()
    {
        /** @var \Mavenbird\ProductAttachment\Model\Report\Item $reportItem */
        $reportItem = $this->reportItemFactory->create();
        $reportItem->setFileId($this->getRequest()->getParam('file'))
            ->setStoreId($this->storeManager->getStore()->getId());

        if ($this->getRequest()->getParam('category')) {
            $reportItem->setCategoryId($this->getRequest()->getParam('category'))
                ->setDownloadSource(DownloadSource::CATEGORY);
        } elseif ($this->getRequest()->getParam('product')) {
            $reportItem->setProductId($this->getRequest()->getParam('product'))
                ->setDownloadSource(DownloadSource::PRODUCT);
        } elseif ($this->getRequest()->getParam('order')) {
            $reportItem->setOrderId($this->getRequest()->getParam('order'))
                ->setDownloadSource(DownloadSource::ORDER);
        } else {
            $reportItem->setDownloadSource(DownloadSource::OTHER);
        }
        $reportItem->setCustomerId($this->customerSession->getCustomerId());

        $reportItem->save();
    }
}
