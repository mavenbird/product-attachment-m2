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
 * @package    Mavenbird_ProductAttachment
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */

namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Import;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Model\Import\ImportFile;
use Mavenbird\ProductAttachment\Model\Import\Repository;
use Magento\Backend\App\Action;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File as FileDriver;

class Generate extends Action
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var FileDriver
     */
    private $fileDriver;

    /**
     * Construct
     *
     * @param Repository $repository
     * @param FileFactory $fileFactory
     * @param Action\Context $context
     * @param Filesystem $fileSystem
     * @param FileDriver $fileDriver
     */
    public function __construct(
        Repository $repository,
        FileFactory $fileFactory,
        Action\Context $context,
        Filesystem $fileSystem,
        FileDriver $fileDriver
    ) {
        parent::__construct($context);
        $this->repository = $repository;
        $this->fileFactory = $fileFactory;
        $this->fileSystem = $fileSystem;
        $this->fileDriver = $fileDriver;
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        if ($importId = $this->getRequest()->getParam('import_id')) {
            if ($import = $this->repository->getById((int)$importId)) {
                $importFiles = $this->repository->getImportFilesByImportId($import->getImportId());
                $storeIds = [];
                if (empty($import->getStoreIds())) {
                    $storeIds[] = 0;
                } else {
                    $storeIds = $import->getStoreIds();
                }

                if (!in_array(0, $storeIds)) {
                    $storeIds = array_merge([0], $storeIds);
                }
                $result = [
                    [
                        ImportFile::IMPORT_FILE_ID,
                        ImportFile::IMPORT_ID,
                        'store_id',
                        FileInterface::FILENAME,
                        FileInterface::LABEL,
                        FileInterface::CUSTOMER_GROUPS,
                        FileInterface::IS_VISIBLE,
                        FileInterface::INCLUDE_IN_ORDER,
                        FileInterface::PRODUCTS,
                        FileInterface::CATEGORIES,
                        'product_skus'
                    ]
                ];
                foreach ($importFiles as $importFile) {
                    foreach ($storeIds as $storeId) {
                        if ($storeId == 0) {
                            $result[] = [
                                ImportFile::IMPORT_FILE_ID      => $importFile->getImportFileId(),
                                ImportFile::IMPORT_ID           => $importFile->getImportId(),
                                'store_id'                      => $storeId,
                                FileInterface::FILENAME         => $importFile->getFileName(),
                                FileInterface::LABEL            => $importFile->getLabel(),
                                FileInterface::CUSTOMER_GROUPS  => $importFile->getData(FileInterface::CUSTOMER_GROUPS),
                                FileInterface::IS_VISIBLE       => (int)$importFile->isVisible(),
                                FileInterface::INCLUDE_IN_ORDER => (int)$importFile->isIncludeInOrder(),
                                FileInterface::PRODUCTS         => '',
                                FileInterface::CATEGORIES       => '',
                                'product_skus' => ''
                            ];
                        } else {
                            $result[] = [
                                ImportFile::IMPORT_FILE_ID      => $importFile->getImportFileId(),
                                ImportFile::IMPORT_ID           => $importFile->getImportId(),
                                'store_id'                      => $storeId,
                                FileInterface::FILENAME         => '',
                                FileInterface::LABEL            => '',
                                FileInterface::CUSTOMER_GROUPS  => '',
                                FileInterface::IS_VISIBLE       => '',
                                FileInterface::INCLUDE_IN_ORDER => '',
                                FileInterface::PRODUCTS         => '',
                                FileInterface::CATEGORIES       => '',
                                'product_skus' => ''
                            ];
                        }
                    }
                }
                $resource = fopen('php://memory', 'w+');
                foreach ($result as $row) {
                    fputcsv($resource, $row);
                }

                rewind($resource);
                $csvContent = stream_get_contents($resource);
                fclose($resource);

                $this->fileFactory->create(
                    'file_import_' . $importId . '.csv',
                    null,
                    \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
                    'application/octet-stream',
                    strlen($csvContent)
                );
                /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
                $resultRaw = $this->resultFactory->create(ResultFactory::TYPE_RAW);
                $resultRaw->setContents($csvContent);

                return $resultRaw;
            }
        }

        return $this->_redirect('*/*/');
    }
}
