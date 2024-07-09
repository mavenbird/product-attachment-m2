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
