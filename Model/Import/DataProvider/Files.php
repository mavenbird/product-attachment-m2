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

namespace Mavenbird\ProductAttachment\Model\Import\DataProvider;

use Mavenbird\ProductAttachment\Model\Filesystem\ImportFilesScanner;
use Mavenbird\ProductAttachment\Model\Icon\GetIconForFile;
use Mavenbird\ProductAttachment\Model\Icon\ResourceModel\Icon;
use Mavenbird\ProductAttachment\Model\Import\Import;
use Mavenbird\ProductAttachment\Model\Import\Repository;
use Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportCollectionFactory;

class Files extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var ImportFilesScanner
     */
    private $importFilesScanner;

    /**
     * @var Icon
     */
    private $iconResource;

    /**
     * @var GetIconForFile
     */
    private $iconForFile;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * Construct
     *
     * @param ImportCollectionFactory $importCollectionFactory
     * @param Repository $repository
     * @param ImportFilesScanner $importFilesScanner
     * @param GetIconForFile $iconForFile
     * @param Icon $iconResource
     * @param [type] $name
     * @param [type] $primaryFieldName
     * @param [type] $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        ImportCollectionFactory $importCollectionFactory,
        Repository $repository,
        ImportFilesScanner $importFilesScanner,
        GetIconForFile $iconForFile,
        Icon $iconResource,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $importCollectionFactory->create();
        $this->importFilesScanner = $importFilesScanner;
        $this->iconResource = $iconResource;
        $this->iconForFile = $iconForFile;
        $this->repository = $repository;
    }

    /**
     * GetData
     *
     * @return void
     */
    public function getData()
    {
        $data = parent::getData();
        if (empty($data['items'])) {
            $data = [];
            $key = null;
            $data[$key] = [Import::IMPORT_ID => $key];
        } else {
            $key = $data['items'][0][Import::IMPORT_ID];
            $data[$key] = $data['items'][0];
        }

        if ($uploadedFiles = $this->importFilesScanner->execute()) {
            $allowedExtensions = $this->iconResource->getAllowedExtensions();
            $fileId = 100000;
            foreach ($uploadedFiles as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($ext, $allowedExtensions)) {
                    $data[$key]['attachments']['files'][] = [
                        'show_file_id' => 'New File',
                        'file_id' => $fileId++,
                        'icon' => $this->iconForFile->byFileExtension($ext),
                        'extension' => $ext,
                        'label' => pathinfo($file, PATHINFO_FILENAME),
                        'filename' => pathinfo($file, PATHINFO_FILENAME),
                        'include_in_order' => '0',
                        'is_visible' => '1',
                        'customer_groups' => '',
                        'filepath' => basename($file)
                    ];
                }
            }
        }

        if ($key) {
            $importFiles = $this->repository->getImportFilesByImportId($key);
            foreach ($importFiles as $importFile) {
                $ext = pathinfo($importFile->getFilePath(), PATHINFO_EXTENSION);
                $data[$key]['attachments']['files'][] = [
                    'show_file_id' => $importFile->getImportFileId(),
                    'file_id' => $importFile->getImportFileId(),
                    'icon' => $this->iconForFile->byFileExtension($ext),
                    'extension' => $ext,
                    'label' => $importFile->getLabel(),
                    'filename' => $importFile->getFileName(),
                    'include_in_order' => $importFile->isIncludeInOrder() ? '1' : '0',
                    'is_visible' => $importFile->isVisible() ? '1' : '0',
                    'customer_groups' => $importFile->getCustomerGroups()
                ];
            }
        }

        return $data;
    }
}
