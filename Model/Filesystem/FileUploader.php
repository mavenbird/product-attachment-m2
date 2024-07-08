<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Filesystem;

use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Magento\Framework\UrlInterface;

class FileUploader
{
    /**
     * @var \Mavenbird\ProductAttachment\Model\Icon\ResourceModel\Icon
     */
    private $iconResourceModel;

    /**
     * @var \Mavenbird\ProductAttachment\Model\Icon\GetIconForFile
     */
    private $getIconForFile;

    /**
     * @var \Magento\Backend\Model\Session
     */
    private $session;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * Construct
     *
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Mavenbird\ProductAttachment\Model\Icon\ResourceModel\Icon $iconResourceModel
     * @param \Mavenbird\ProductAttachment\Model\Icon\GetIconForFile $getIconForFile
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Backend\Model\Session $session
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Mavenbird\ProductAttachment\Model\Icon\ResourceModel\Icon $iconResourceModel,
        \Mavenbird\ProductAttachment\Model\Icon\GetIconForFile $getIconForFile,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Model\Session $session,
        \Magento\Framework\Registry $registry
    ) {
        $this->iconResourceModel = $iconResourceModel;
        $this->getIconForFile = $getIconForFile;
        $this->session = $session;
        $this->registry = $registry;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(
            \Magento\Framework\App\Filesystem\DirectoryList::MEDIA
        );
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * UploadFile
     *
     * @param [type] $fileKey
     * @return void
     */
    public function uploadFile($fileKey)
    {
        try {
            $uploader = $this->uploaderFactory->create(['fileId' => $fileKey]);
            if ($fileKey !== RegistryConstants::ICON_FILE_KEY) {
                $uploader->setAllowedExtensions($this->iconResourceModel->getAllowedExtensions());
            } else {
                $uploader->setAllowedExtensions(RegistryConstants::ICON_EXTENSIONS);
            }

            $uploader->setAllowRenameFiles(true);
            $result = $uploader->save(
                $this->mediaDirectory->getAbsolutePath(Directory::DIRECTORY_CODES[Directory::TMP_DIRECTORY])
            );
            unset($result['path']);

            if (!$result) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('File can not be saved to the destination folder.')
                );
            }

            $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
            $result['url'] = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                . $this->getFilePath(Directory::DIRECTORY_CODES[Directory::TMP_DIRECTORY], $result['file']);
            $result['name'] = $result['file'];

            /** @codingStandardsIgnoreStart */
            $result['filename'] = pathinfo($result['name'], PATHINFO_FILENAME);
            $result['file_extension'] = pathinfo($result['name'], PATHINFO_EXTENSION);
            /** @codingStandardsIgnoreEnd */
            $result['previewUrl'] = $this->getIconForFile->byFileExtension($result['file_extension']);
            $this->setResultCookie($result);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $result;
    }

    /**
     * SetResultCookie
     *
     * @param [type] $result
     * @return void
     */
    private function setResultCookie(&$result)
    {
        $result['cookie'] = [
            'name' => $this->session->getName(),
            'value' => $this->session->getSessionId(),
            'lifetime' => $this->session->getCookieLifetime(),
            'path' => $this->session->getCookiePath(),
            'domain' => $this->session->getCookieDomain(),
        ];
    }

    /**
     * GetFilePath
     *
     * @param [type] $path
     * @param [type] $fileName
     * @return void
     */
    public function getFilePath($path, $fileName)
    {
        return rtrim($path, '/') . '/' . ltrim($fileName, '/');
    }
}
