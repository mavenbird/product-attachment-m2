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

namespace Mavenbird\ProductAttachment\Model\Product;

use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\ConfigProvider;
use Mavenbird\ProductAttachment\Model\File\FileScope\SaveFileScopeInterface;

class Save implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var SaveFileScopeInterface
     */
    private $saveFileScope;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * Construct
     *
     * @param SaveFileScopeInterface $saveFileScope
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        SaveFileScopeInterface $saveFileScope,
        ConfigProvider $configProvider
    ) {
        $this->saveFileScope = $saveFileScope;
        $this->configProvider = $configProvider;
    }

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $files = $observer->getController()->getRequest()->getParam('attachments');
        $params = [
            RegistryConstants::FILES => !empty($files['files']) ? $files['files'] : false,
            RegistryConstants::PRODUCT => $observer->getProduct()->getId(),
            RegistryConstants::STORE => (int)$observer->getController()->getRequest()->getParam('store')
        ];
        if (!empty($files['delete'])) {
            $params[RegistryConstants::TO_DELETE] = $files['delete'];
        }
        $this->saveFileScope->execute($params, 'product');
        if ($this->configProvider->addCategoriesFilesToProducts()) {
            $files = $observer->getController()->getRequest()->getParam('categories_attachments');
            $params = [
                RegistryConstants::FILES => !empty($files['categories_files']) ? $files['categories_files'] : false,
                RegistryConstants::PRODUCT => $observer->getProduct()->getId(),
                RegistryConstants::STORE => (int)$observer->getController()->getRequest()->getParam('store')
            ];
            $this->saveFileScope->execute($params, 'productCategories');
        }
    }
}
