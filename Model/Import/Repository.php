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

namespace Mavenbird\ProductAttachment\Model\Import;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Model\Filesystem\Directory;
use Mavenbird\ProductAttachment\Model\Filesystem\UploadFileDataFactory;
use Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFileCollection;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Repository
{
    /**
     * @var ImportFactory
     */
    private $importFactory;

    /**
     * @var ResourceModel\Import
     */
    private $importResource;

    /**
     * @var array
     */
    private $imports = [];

    /**
     * @var ImportFileFactory
     */
    private $importFileFactory;

    /**
     * @var \Mavenbird\ProductAttachment\Model\Filesystem\File
     */
    private $moveFile;

    /**
     * @var ResourceModel\ImportFile
     */
    private $importFileResource;

    /**
     * @var ResourceModel\ImportFileCollectionFactory
     */
    private $importFileCollectionFactory;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;

    /**
     * Construct
     *
     * @param \Mavenbird\ProductAttachment\Model\Import\ImportFactory $importFactory
     * @param \Mavenbird\ProductAttachment\Model\Import\ImportFileFactory $importFileFactory
     * @param \Mavenbird\ProductAttachment\Model\Filesystem\File $moveFile
     * @param \Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFile $importFileResource
     * @param \Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFileCollectionFactory $importFileCollectionFactory
     * @param \Mavenbird\ProductAttachment\Model\Import\ResourceModel\Import $importResource
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Mavenbird\ProductAttachment\Model\Import\ImportFactory $importFactory,
        \Mavenbird\ProductAttachment\Model\Import\ImportFileFactory $importFileFactory,
        \Mavenbird\ProductAttachment\Model\Filesystem\File $moveFile,
        \Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFile $importFileResource,
        \Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFileCollectionFactory $importFileCollectionFactory,
        \Mavenbird\ProductAttachment\Model\Import\ResourceModel\Import $importResource,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->importFactory = $importFactory;
        $this->importResource = $importResource;
        $this->importFileFactory = $importFileFactory;
        $this->moveFile = $moveFile;
        $this->importFileResource = $importFileResource;
        $this->importFileCollectionFactory = $importFileCollectionFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * GetById
     *
     * @param [type] $importId
     * @return void
     */
    public function getById($importId)
    {
        if (!isset($this->imports[$importId])) {
            /** @var \Mavenbird\ProductAttachment\Model\Import\Import $import*/
            $import = $this->importFactory->create();
            $this->importResource->load($import, $importId);
            if (!$import->getImportId()) {
                throw new NoSuchEntityException(__('Import with specified ID "%1" not found.', $importId));
            }
            $this->imports[$importId] = $import;
        }

        return $this->imports[$importId];
    }

    /**
     * GetImportFilesByImportId
     *
     * @param [type] $importId
     * @return void
     */
    public function getImportFilesByImportId($importId)
    {
        /** @var \Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFileCollection $filesCollection */
        $filesCollection = $this->importFileCollectionFactory->create();
        $filesCollection->addFieldToFilter(ImportFile::IMPORT_ID, (int)$importId);

        return $filesCollection->getItems();
    }

    /**
     * Save
     *
     * @param \Mavenbird\ProductAttachment\Model\Import\Import $import
     * @return void
     */
    public function save(\Mavenbird\ProductAttachment\Model\Import\Import $import)
    {
        try {
            if ($import->getImportId()) {
                $import = $this->getById($import->getImportId())->addData($import->getData());
            }
            $import->setStoreIds($import->getStoreIds());
            $this->importResource->save($import);
            unset($this->imports[$import->getIconId()]);
        } catch (\Exception $e) {
            if ($import->getImportId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save import with ID %1. Error: %2',
                        [$import->getImportId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new import. Error: %1', $e->getMessage()));
        }

        return $import;
    }

    /**
     * SaveImportFiles
     *
     * @param [type] $importId
     * @param [type] $files
     * @return void
     */
    public function saveImportFiles($importId, $files)
    {
        /** @var \Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFileCollection $toDeleteCollection */
        $toDeleteCollection = $this->importFileCollectionFactory->create();
        $toDeleteCollection->addFieldToFilter(ImportFile::IMPORT_ID, (int)$importId);
        $toDeleteCollection->addFieldToSelect(ImportFile::IMPORT_FILE_ID);
        $toDelete = [];
        foreach ($toDeleteCollection->getData() as $item) {
            $toDelete[$item[ImportFile::IMPORT_FILE_ID]] = 1;
        }

        foreach ($files as $file) {
            if (!empty($file['file']) || !empty($file['link'])) {
                /** @var \Mavenbird\ProductAttachment\Model\Import\ImportFile $newFile */
                $newFile = $this->importFileFactory->create();
                $uploadFileData = $this->moveFile->getUploadFileData();
                $uploadFileData->setTmpFileName($file['file']);
                try {
                    $this->moveFile->save(
                        $uploadFileData,
                        Directory::IMPORT,
                        true,
                        $importId
                    );
                    if ($file[FileInterface::CUSTOMER_GROUPS] !== ''
                        && is_array($file[FileInterface::CUSTOMER_GROUPS])) {
                        $customerGroups = implode(',', $file[FileInterface::CUSTOMER_GROUPS]);
                    } else {
                        $customerGroups = null;
                    }

                    $newFile->setImportId((int)$importId)
                        ->setFilePath($uploadFileData->getFileName() . '.' . $uploadFileData->getExtension())
                        ->setLabel($file[FileInterface::LABEL])
                        ->setFileName($file[FileInterface::FILENAME])
                        ->setIsIncludeInOrder($file[FileInterface::INCLUDE_IN_ORDER])
                        ->setCustomerGroups($customerGroups)
                        ->setIsVisible($file[FileInterface::IS_VISIBLE]);

                    $this->importFileResource->save($newFile);
                } catch (\Exception $e) {
                    null;
                }
            } elseif (!empty($file['filepath'])) {
                /** @var \Mavenbird\ProductAttachment\Model\Import\ImportFile $newFile */
                $newFile = $this->importFileFactory->create();
                $uploadFileData = $this->moveFile->getUploadFileData();
                $uploadFileData->setTmpFileName(
                    Directory::DIRECTORY_CODES[Directory::IMPORT_FTP] . DIRECTORY_SEPARATOR . $file['filepath']
                );
                try {
                    $this->moveFile->save(
                        $uploadFileData,
                        Directory::IMPORT,
                        true,
                        $importId
                    );
                    if ($file[FileInterface::CUSTOMER_GROUPS] !== ''
                        && is_array($file[FileInterface::CUSTOMER_GROUPS])) {
                        $customerGroups = implode(',', $file[FileInterface::CUSTOMER_GROUPS]);
                    } else {
                        $customerGroups = null;
                    }

                    $newFile->setImportId((int)$importId)
                        ->setFilePath($uploadFileData->getFileName() . '.' . $uploadFileData->getExtension())
                        ->setLabel($file[FileInterface::LABEL])
                        ->setFileName($file[FileInterface::FILENAME])
                        ->setIsIncludeInOrder($file[FileInterface::INCLUDE_IN_ORDER])
                        ->setCustomerGroups($customerGroups)
                        ->setIsVisible($file[FileInterface::IS_VISIBLE]);

                    $this->importFileResource->save($newFile);
                } catch (\Exception $e) {
                    null;
                }
            } elseif (!empty($file[FileInterface::FILE_ID])) {
                unset($toDelete[$file[FileInterface::FILE_ID]]);
                /** @var \Mavenbird\ProductAttachment\Model\Import\ImportFile $newFile */
                $newFile = $this->importFileFactory->create();
                $this->importFileResource->load($newFile, (int)$file[FileInterface::FILE_ID]);
                if ($newFile->getImportFileId()) {
                    $newFile->setLabel($file[FileInterface::LABEL])
                        ->setFileName($file[FileInterface::FILENAME])
                        ->setIsIncludeInOrder($file[FileInterface::INCLUDE_IN_ORDER])
                        ->setCustomerGroups($file[FileInterface::CUSTOMER_GROUPS . '_output'])
                        ->setIsVisible($file[FileInterface::IS_VISIBLE]);
                    $this->importFileResource->save($newFile);
                }
            }
        }
        if (!empty($toDelete)) {
            $this->importFileResource->deleteFiles($importId, array_keys($toDelete));
        }
    }

    /**
     * DeleteById
     *
     * @param [type] $importId
     * @return void
     */
    public function deleteById($importId)
    {
        try {
            $import = $this->getById((int)$importId);
            $this->mediaDirectory->delete(
                Directory::DIRECTORY_CODES[Directory::IMPORT] . DIRECTORY_SEPARATOR . (int)$importId
            );
            $this->importResource->delete($import);
        } catch (\Exception $e) {
            null;
        }
    }
}
