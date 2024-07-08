<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Category;

use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\File\FileScope\SaveFileScopeInterface;

class Save implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var SaveFileScopeInterface
     */
    private $saveFileScope;
    /**
     * Construct
     *
     * @param SaveFileScopeInterface $saveFileScope
     */
    public function __construct(
        SaveFileScopeInterface $saveFileScope
    ) {
        $this->saveFileScope = $saveFileScope;
    }

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $files = $observer->getRequest()->getParam('attachments');
        $params = [
            RegistryConstants::FILES => !empty($files['files']) ? $files['files'] : false,
            RegistryConstants::CATEGORY => $observer->getCategory()->getId(),
            RegistryConstants::STORE => (int)$observer->getRequest()->getParam('store_id')
        ];
        if (!empty($files['delete'])) {
            $params[RegistryConstants::TO_DELETE] = $files['delete'];
        }
        $this->saveFileScope->execute(
            $params,
            'category'
        );
    }
}
