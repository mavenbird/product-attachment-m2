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

namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Import;

use Mavenbird\ProductAttachment\Controller\Adminhtml\Import;
use Mavenbird\ProductAttachment\Model\Import\Import as ImportModel;
use Mavenbird\ProductAttachment\Model\Import\ImportFactory;
use Mavenbird\ProductAttachment\Model\Import\Repository;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Save extends Import
{
    /**
     * @var ImportFactory
     */
    private $importFactory;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * Construct
     *
     * @param ImportFactory $importFactory
     * @param Repository $repository
     * @param Action\Context $context
     */
    public function __construct(
        ImportFactory $importFactory,
        Repository $repository,
        Action\Context $context
    ) {
        parent::__construct($context);
        $this->importFactory = $importFactory;
        $this->repository = $repository;
    }
    
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $data = $this->getRequest()->getPostValue();
                if (isset($data['filesData'])) {
                    $data = json_decode($data['filesData'], true);
                }
                if (isset($data['step'])) {
                    switch ($data['step']) {
                        case '1':
                            /** @var \Mavenbird\ProductAttachment\Model\Import\Import $model */
                            $model = $this->importFactory->create();
                            if ($importId = (int)$this->getRequest()->getParam(ImportModel::IMPORT_ID)) {
                                $model = $this->repository->getById($importId);
                                if ($importId != $model->getImportId()) {
                                    throw new LocalizedException(__('The wrong item is specified.'));
                                }
                            }

                            $model->addData($data);
                            $this->repository->save($model);

                            if (!empty($data['attachments'])) {
                                $this->repository->saveImportFiles($model->getImportId(), $data['attachments']['files']);
                            }

                            $this->_redirect('*/*/store', [ImportModel::IMPORT_ID => $model->getId()]);
                            return;
                        case '2':
                            if ($importId = (int)$this->getRequest()->getParam(ImportModel::IMPORT_ID)) {
                                $model = $this->repository->getById($importId);
                                if ($importId != $model->getImportId()) {
                                    throw new LocalizedException(__('The wrong item is specified.'));
                                }
                            } else {
                                throw new LocalizedException(__('The wrong item is specified.'));
                            }

                            $model->addData($data);
                            $this->repository->save($model);

                            $this->_redirect('*/*/fileimport', [ImportModel::IMPORT_ID => $model->getId()]);
                            return;
                    }
                }
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                //TODO datapersistor
                return;
            }
        }
        $this->_redirect('*/*/');
    }
}
