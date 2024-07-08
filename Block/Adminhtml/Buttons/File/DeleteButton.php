<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Block\Adminhtml\Buttons\File;

use Mavenbird\ProductAttachment\Block\Adminhtml\Buttons\GenericButton;
use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * GetButtonData
     *
     * @return void
     */
    public function getButtonData()
    {
        if (!$this->getFileId()) {
            return [];
        }
        $alertMessage = __('Are you sure you want to do this?');
        $onClick = sprintf('deleteConfirm("%s", "%s")', $alertMessage, $this->getDeleteUrl());

        $data = [
            'label' => __('Delete File'),
            'class' => 'delete',
            'id' => 'file-edit-delete-button',
            'on_click' => $onClick,
            'sort_order' => 20,
        ];

        return $data;
    }
    
    /**
     * GetDeleteUrl
     *
     * @return void
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', [RegistryConstants::FORM_FILE_ID => $this->getFileId()]);
    }

    /**
     * GetFileId
     *
     * @return void
     */
    public function getFileId()
    {
        return (int)$this->request->getParam(RegistryConstants::FORM_FILE_ID);
    }
}
