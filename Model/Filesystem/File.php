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

namespace Mavenbird\ProductAttachment\Model\Filesystem;

use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;

class File
{
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var UploadFileDataFactory
     */
    private $uploadFileDataFactory;

    /**
     * @var \Magento\Downloadable\Helper\Download
     */
    private $downloadHelper;

    /**
     * @var \Mavenbird\ProductAttachment\Model\Icon\ResourceModel\Icon
     */
    private $iconResource;

    /**
     * @var \Magento\Framework\Math\Random
     */
    private $random;

    /**
     * Construct
     *
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Mavenbird\ProductAttachment\Model\Filesystem\UploadFileDataFactory $uploadFileDataFactory
     * @param \Magento\Downloadable\Helper\Download $downloadHelper
     * @param \Mavenbird\ProductAttachment\Model\Icon\ResourceModel\Icon $iconResource
     * @param \Magento\Framework\Math\Random $random
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        \Mavenbird\ProductAttachment\Model\Filesystem\UploadFileDataFactory $uploadFileDataFactory,
        \Magento\Downloadable\Helper\Download $downloadHelper,
        \Mavenbird\ProductAttachment\Model\Icon\ResourceModel\Icon $iconResource,
        \Magento\Framework\Math\Random $random
    ) {
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploadFileDataFactory = $uploadFileDataFactory;
        $this->downloadHelper = $downloadHelper;
        $this->iconResource = $iconResource;
        $this->random = $random;
    }

    /**
     * GetFilePath
     *
     * @param [type] $filename
     * @param [type] $directoryCode
     * @return void
     */
    public function getFilePath($filename, $directoryCode)
    {
        $this->checkDirectoryCode($directoryCode);

        $file = Directory::DIRECTORY_CODES[$directoryCode] . DIRECTORY_SEPARATOR . $filename;
        if (!$this->mediaDirectory->isExist($file)) {

            return false;
        }

        return $file;
    }

    /**
     * Save
     *
     * @param UploadFileData $fileData
     * @param [type] $directoryCode
     * @param boolean $checkExtension
     * @param integer $importId
     * @return void
     */
    public function save(UploadFileData &$fileData, $directoryCode, $checkExtension = true, $importId = 0)
    {
        $this->checkDirectoryCode($directoryCode);
        //phpcs:ignore
        $ext = strtolower(pathinfo($fileData->getTmpFileName(), PATHINFO_EXTENSION));
        $newName = $this->random->getUniqueHash();
        $allowedExtensions = array_merge(
            $this->iconResource->getAllowedExtensions(),
            RegistryConstants::ICON_EXTENSIONS
        );

        if (!in_array($ext, $allowedExtensions) && $checkExtension) {
            throw new LocalizedException(__('Disallowed Extension'));
        }
        if ($directoryCode !== Directory::IMPORT) {
            $newPath = Directory::DIRECTORY_CODES[$directoryCode];
        } else {
            $newPath = Directory::DIRECTORY_CODES[$directoryCode] . DIRECTORY_SEPARATOR . (int)$importId;
        }

        $newNameWithPath = $newPath . DIRECTORY_SEPARATOR . $newName . '.' . $ext;

        try {
            if ($this->mediaDirectory->isFile($fileData->getTmpFileName())) {
                $oldPath = $fileData->getTmpFileName();
            } else {
                $oldPath = Directory::DIRECTORY_CODES[Directory::TMP_DIRECTORY]
                    . DIRECTORY_SEPARATOR . $fileData->getTmpFileName();
            }
            if (!$this->mediaDirectory->isDirectory($newPath)) {
                $this->mediaDirectory->create($newPath);
            }

            $this->mediaDirectory->copyFile(
                $oldPath,
                $newNameWithPath
            );
        } catch (\Magento\Framework\Exception\FileSystemException $e) {
            $this->mediaDirectory->delete($oldPath);

            return false;
        }
        $this->mediaDirectory->delete($oldPath);
        $fileStat = $this->mediaDirectory->stat($newNameWithPath);
        $fileData->setFileName($newName);
        $fileData->setExtension($ext);
        $fileData->setFileSize($fileStat['size']);
        $this->downloadHelper->setResource($newNameWithPath);
        $fileData->setMimeType($this->downloadHelper->getContentType());

        return $fileData;
    }
    
    /**
     * DeleteFile
     *
     * @param [type] $file
     * @param [type] $directoryCode
     * @return void
     */
    public function deleteFile($file, $directoryCode)
    {
        $this->checkDirectoryCode($directoryCode);
        if ($this->mediaDirectory->isFile(Directory::DIRECTORY_CODES[$directoryCode] . DIRECTORY_SEPARATOR . $file)
            && strpos($file, '..') === false) {
            $this->mediaDirectory->delete(
                Directory::DIRECTORY_CODES[$directoryCode] . DIRECTORY_SEPARATOR . $file
            );
        }
    }

    /**
     * CheckDirectoryCode
     *
     * @return void
     */
    private function checkDirectoryCode($directoryCode)
    {
        if (!array_key_exists($directoryCode, Directory::DIRECTORY_CODES)) {
            throw new LocalizedException(__('Directory Code doesn\'t exist.'));
        }
    }

    /**
     * GetUploadFileData
     *
     * @return void
     */
    public function getUploadFileData()
    {
        return $this->uploadFileDataFactory->create();
    }
}
