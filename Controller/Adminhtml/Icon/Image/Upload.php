<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Icon\Image;

use Mavenbird\ProductAttachment\Api\Data\IconInterface;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Magento\Framework\Controller\ResultFactory;

class Upload extends \Mavenbird\ProductAttachment\Controller\Adminhtml\Icon
{
    /**
     * @var \Mavenbird\ProductAttachment\Model\Filesystem\FileUploader
     */
    private $fileUploader;
    /**
     * Construct
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Mavenbird\ProductAttachment\Model\Filesystem\FileUploader $fileUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Mavenbird\ProductAttachment\Model\Filesystem\FileUploader $fileUploader
    ) {
        parent::__construct($context);
        $this->fileUploader = $fileUploader;
    }

    /**
     * Upload Icon controller action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        return $resultJson->setData($this->fileUploader->uploadFile(RegistryConstants::ICON_FILE_KEY));
    }
}
