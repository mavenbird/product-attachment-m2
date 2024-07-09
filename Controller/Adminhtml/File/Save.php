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

namespace Mavenbird\ProductAttachment\Controller\Adminhtml\File;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\File;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Magento\Backend\App\Action\Context;
use Mavenbird\ProductAttachment\Model\File\FileFactory;
use Mavenbird\ProductAttachment\Api\FileRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends File
{
    /**
     * @var FileRepositoryInterface
     */
    private $repository;

    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Construt
     *
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param FileRepositoryInterface $repository
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        FileRepositoryInterface $repository,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->fileFactory = $fileFactory;
        $this->repository = $repository;
        $this->dataPersistor = $dataPersistor;
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
                /** @var \Mavenbird\ProductAttachment\Model\File\File $model */
                $model = $this->fileFactory->create();
                $data = $this->getRequest()->getPostValue();

                if ($fileId = (int)$this->getRequest()->getParam(RegistryConstants::FORM_FILE_ID)) {
                    $model = $this->repository->getById($fileId);
                    if ($fileId != $model->getFileId()) {
                        throw new LocalizedException(__('The wrong item is specified.'));
                    }
                }

                $this->filterData($data);
                $model->addData($data);
                $this->repository->saveAll($model);

                $this->messageManager->addSuccessMessage(__('You saved the item.'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect(
                        '*/*/edit',
                        [RegistryConstants::FORM_FILE_ID => $model->getId(), '_current' => true]
                    );
                    return;
                }
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->dataPersistor->set(RegistryConstants::FILE_DATA, $data);
                if ($fileId = (int)$this->getRequest()->getParam(RegistryConstants::FORM_FILE_ID)) {
                    $this->_redirect('*/*/edit', [RegistryConstants::FORM_FILE_ID => $fileId]);
                } else {
                    $this->_redirect('*/*/create');
                }
                return;
            }
        }
        $this->_redirect('*/*/');
    }
    
    /**
     * Filterdata
     *
     * @param [type] $data
     * @return void
     */
    private function filterData(&$data)
    {
        if (!empty($data['fileproducts']['products'])) {
            $productIds = [];
            foreach ($data['fileproducts']['products'] as $product) {
                $productIds[] = (int)$product['entity_id'];
            }
            $data[FileInterface::PRODUCTS] = array_unique($productIds);
        } else {
            $data[FileInterface::PRODUCTS] = [];
        }
    }
}
