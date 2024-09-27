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

namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Productattachment;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
use Magento\MediaStorage\Model\File\UploaderFactory;

/**
 * Save Attach Action
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;
    
    /**
     * @var string
     */
    protected $fileId = 'icon';
    
    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $_jsHelper;

    /**
     * @var \Mavenbird\ProductAttachment\Model\ProductAttachment
     */
    protected $_manageAttachment;
    
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resources;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $session;

       /**
     * @var \Magento\Backend\Model\Session
     */
    protected $directory_list;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $date;


    protected $request;

    /**
     *
     * @param Action\Context                                      $context
     * @param \Magento\Backend\Helper\Js                          $jsHelper
     * @param UploaderFactory                                     $uploaderFactory
     * @param \Magento\Framework\App\Filesystem\DirectoryList     $directory_list
     * @param \Mavenbird\ProductAttachment\Model\ProductAttachment $manageAttachment
     * @param \Magento\Framework\Stdlib\DateTime\DateTime         $date
     * @param \Magento\Framework\HTTP\PhpEnvironment\Request      $request
     * @param \Magento\Framework\App\ResourceConnection           $resources
     * @param \Magento\Backend\Model\Session                      $session
     */
    public function __construct(
        Action\Context $context,
        \Magento\Backend\Helper\Js $jsHelper,
        UploaderFactory $uploaderFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Mavenbird\ProductAttachment\Model\ProductAttachment $manageAttachment,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\HTTP\PhpEnvironment\Request $request,
        \Magento\Framework\App\ResourceConnection $resources,
        \Magento\Backend\Model\Session $session
    ) {
        $this->_jsHelper = $jsHelper;
        $this->uploaderFactory = $uploaderFactory;
        $this->directory_list = $directory_list;
        $this->_manageAttachment = $manageAttachment;
        $this->date = $date;
        $this->_resources = $resources;
        parent::__construct($context);
        $this->request = $request;
        $this->session = $session;
        $success = $this->checkPostSizeExceeded();
        if ($success == 'false') {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        }
    }

    /**
     * @inheritdoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mavenbird_ProductAttachment::save');
    }

    /**
     * Perform execute method for save Action
     *
     * @return $resultRedirect
     */
    public function execute()
    {
        $data =$this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        $files = $this->request->getFiles()->toArray();
        $saved = false;

        if ($data) {
            $model=$this->_manageAttachment;
           
            if (isset($data['icon']['delete'])) {
                
                $data['icon']="";
            } else {
               
                if (isset($files['icon']['name']) && $files['icon']['name'] != '') {
                    $imagename=$this->uploadImage();
                    if ($imagename != null) {
                        $data['icon']="Mavenbird".$imagename;
                    }
                } else {
                    if (isset($data['icon'])) {
                        $data['icon'] = $data['icon']['value'];
                    }
                }
            }
            
            if (isset($data['video']['delete'])) {
                $data['video']="";
            } else {
                if (isset($files['video']['name']) && $files['video']['name'] != '') {
                    $videoname=$this->uploadVideo();
                    
                    if ($videoname != null) {
                        $data['video']="Video".$videoname;
                    }
                } else {
                    if (isset($data['video'])) {
                        $data['video'] = $data['video']['value'];
                    }
                }
            }
            
            if (isset($data['file']['delete'])) {
                $data['file']="";
            } else {
                if (isset($files['file']['name']) && $files['file']['name'] != '') {
                    $filename=$this->uploadFile();
                    if ($filename != null) {
                        $data['file']="rockfile".$filename;
                    }
                } else {
                    if (isset($data['file'])) {
                        $data['file'] = $data['file']['value'];
                    }
                }
            }
            
            if ($this->getRequest()->getParam('customer_group')) {
                $getcustomergrp = $this->getRequest()->getParam('customer_group');
                $getgrp = implode(',', $getcustomergrp);
                $data['customer_group'] = $getgrp;
            }

            if (isset($data['products'])) {
                $productIds =str_replace("&", ",", $data['products']);
                $data['product_id']=$productIds;
            }
            
            $data['updated_at'] = $this->date->gmtTimestamp();

            $model->setData($data);

            if (isset($data['attach_id'])) {
                $model->setId($data['attach_id']);
            }
            
            try {
                
                if (isset($data['video'])) {
                    if ($data['video'] <= 5000000) {
                        $incrementID = $model->save()->getId();
                        $saved = true;
                    }
                }

                $incrementID = $model->save()->getId();
                $saved = true;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Something went wrong while saving the record.'.$e->getMessage()));
            }
          
            $this->saveProducts($model, $data);
            if ($saved) {
                if (!isset($data['attach_id'])) {
                    $stores = $data['store_id'];

                    $connection = $this->_resources->getConnection();

                    $table = $this->_resources->getTableName('attachment_store');
                    foreach ($stores as $storeId) {
                        $data_stores[] = [
                        'attach_id'=> $incrementID,
                        'store_id' => (int)$storeId,
                        ];
                    }
                    $connection->insertMultiple($table, $data_stores);
                }
                $this->messageManager->addSuccess(__('You saved this Record.'));
            }
            
            $this->session->setFormData(false);
            
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['attach_id' => $model->getId(), '_current' => true]);
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath(
                'productattachment/productattachment/index',
                ['attach_id' => $this->getRequest()->getParam('attach_id')]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Check Post Size Exceeded
     */
    public function checkPostSizeExceeded()
    {
        $maxPostSize = $this->iniGetBytes('post_max_size');
     
        if ($this->request->get('CONTENT_LENGTH') > $maxPostSize) {
            $this->messageManager->addError(
                __(
                    'Max post size exceeded! Please increase your max_post_size and'.
                    'upload_max_filesize in php configuration file.'
                )
            );
            return 'false';
        }
        return 'true';
    }
    
    /**
     * Ini Get Bytes
     *
     * @param  string $val
     */
    public function iniGetBytes($val)
    {
        $val = trim(ini_get($val));
        if ($val != '') {
            $last = strtolower($val[strlen($val) - 1]);
            $val = str_replace($val[strlen($val) - 1], '', $val);
        } else {
            $last = '';
        }
        switch ($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
                // no break

            case 'm':
                $val *= 1024;
                // no break

            case 'k':
                $val *= 1024;
                // no break
        }
        return $val;
    }
    
    /**
     * Upload imege file
     *
     * @return $void
     */
    public function uploadImage()
    {
        $destinationPath = $this->getDestinationPath();
       
        try {
            try {
                $uploader = $this->uploaderFactory->create(['fileId' => 'icon'])
                    ->setAllowCreateFolders(true);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png','icon']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
            } catch (\Exception $e) {
                    $this->messageManager->addError(
                        __('Max Filesize exceeded! Please increase your upload_max_filesize in php configuration file.')
                    );
                    return;
            }
            $result=$uploader->save($destinationPath);
            
            return $result['file'];

        } catch (\Exception $e) {
            $this->messageManager->addError(
                __($e->getMessage())
            );
        }
    }
    
    /**
     * Upload video file
     *
     * @return $void
     */
    public function uploadVideo()
    {
        $destinationPath = $this->getDestinationVideoPath();
        
        try {
            try {
                $uploader = $this->uploaderFactory->create(['fileId' => 'video'])
                    ->setAllowCreateFolders(true);
                    $uploader->setAllowedExtensions(['mp4', 'mkv', 'mpg', 'webm','mov']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
            } catch (\Exception $e) {
                    $this->messageManager->addError(
                        __('Max Filesize exceeded! Please increase your upload_max_filesize in php configuration file.')
                    );
                    return;
            }
            
            if ($this->uploaderFactory->create(['fileId' => 'video'])->getFileSize(true) >= 5000000) {
                $this->messageManager->addError("Video size is too big");
            } else {
                $result=$uploader->save($destinationPath);
                return $result['file'];
            }
            
            if (!$uploader->save($destinationPath)) {
                throw new LocalizedException(
                    __('File cannot be saved to path: $1', $destinationPath)
                );
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError(
                __($e->getMessage())
            );
        }
    }

/**
 * Upload file with allowed extensions
 *
 * @return string|null
 */
public function uploadFile()
{
    $destinationPath = $this->getDestinationFilePath();
    
    try {
        // Create an uploader instance
        $uploader = $this->uploaderFactory->create(['fileId' => 'file'])
            ->setAllowCreateFolders(true)
            ->setAllowedExtensions(['pdf', 'doc', 'txt', 'xml', 'docx', 'xlsx', 'xls', 'csv', 'html', 'htm', 'php'])
            ->setAllowRenameFiles(true)
            ->setFilesDispersion(true);
        
        // Bypass the protected file extension validator
        $uploader->skipDbProcessing(true);
        
        $result = $uploader->save($destinationPath);
        
        return $result['file'];
        
    } catch (\Magento\Framework\Exception\LocalizedException $e) {
        $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
    } catch (\Exception $e) {
        $this->messageManager->addErrorMessage(__('Error while uploading: ' . $e->getMessage()));
    }
    
    return null;
}


    /**
     * Get Destination Path
     *
     * @return $directory_list
     */
    public function getDestinationFilePath()
    {
        return $this->directory_list->getPath('media')."/rockfile/";
    }

    /**
     * Get Destination Path
     *
     * @return $directory_list
     */
    public function getDestinationPath()
    {
        return $this->directory_list->getPath('media')."/Mavenbird/";
    }
    
    /**
     * Get Destination Video Path
     */
    public function getDestinationVideoPath()
    {
        return $this->directory_list->getPath('media')."/Video/";
    }

    /**
     * Save product for Attachment
     *
     * @param  object $model
     * @param  string $post
     * @return $void
     */
    public function saveProducts($model, $post)
    {
        if (isset($post['products'])) {
                     
            $productIds = $this->_jsHelper->decodeGridSerializedInput($post['products']);
            try {
                $oldProducts = (array) $model->getProducts($model);
                $newProducts = (array) $productIds;
                
                $connection = $this->_resources->getConnection();

                $table = $this->_resources->getTableName(
                    \Mavenbird\ProductAttachment\Model\ResourceModel\ProductAttachment::TBL_ATT_PRODUCT
                );
                
                $insert = array_diff($newProducts, $oldProducts);
                $delete = array_diff($oldProducts, $newProducts);
                if (isset($post['attach_id']) || $model->getId()) {
                    if ($delete) {
                        $where = ['attach_id = ?' => (int)$model->getId(), 'product_id IN (?)' => $delete];
                        $connection->delete($table, $where);
                    }

                    if ($insert) {
                        $data = [];
                        foreach ($insert as $product_id) {
                            $data[] = ['attach_id' => (int)$model->getId(), 'product_id' => (int)$product_id];
                        }
                        $connection->insertMultiple($table, $data);
                    }
                }
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Store.'));
            }
        }
    }
}
