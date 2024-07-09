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

namespace Mavenbird\ProductAttachment\Block\Product;

use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\ConfigProvider;
use Mavenbird\ProductAttachment\Model\File\FileScope\FileScopeDataProvider;
use Magento\Customer\Model\Session;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class AttachmentsTab extends Template
{
    /**
     * Templates
     *
     * @var string
     */
    protected $_template = 'Mavenbird_ProductAttachment::attachments.phtml';

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var FileScopeDataProvider
     */
    private $fileScopeDataProvider;

    /**
     * @var Session
     */
    private $customerSession;
    
    /**
     * Construct
     *
     * @param ConfigProvider $configProvider
     * @param FileScopeDataProvider $fileScopeDataProvider
     * @param Session $customerSession
     * @param Registry $registry
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        ConfigProvider $configProvider,
        FileScopeDataProvider $fileScopeDataProvider,
        Session $customerSession,
        Registry $registry,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
        $this->registry = $registry;
        $this->fileScopeDataProvider = $fileScopeDataProvider;
        $this->customerSession = $customerSession;
        $this->setData('sort_order', $this->configProvider->getBlockSortOrder());
    }

    /**
     * @inheritdoc
     */
    public function toHtml()
    {
        if (!$this->configProvider->isEnabled() || !$this->configProvider->isBlockEnabled()) {
            return '';
        }
        if ($this->configProvider->getBlockCustomerGroups() !== null) {
            if (!in_array(
                $this->customerSession->getCustomerGroupId(),
                explode(',', $this->configProvider->getBlockCustomerGroups())
            )) {
                return '';
            }
        }

        $this->setTitle($this->configProvider->getBlockTitle());

        return parent::toHtml();
    }

    /**
     * GetBlockTitle
     *
     * @return void
     */
    public function getBlockTitle()
    {
        return false;
    }

    /**
     * GetAttachments
     *
     * @return void
     */
    public function getAttachments()
    {
        if ($product = $this->registry->registry('current_product')) {
            return $this->fileScopeDataProvider->execute(
                [
                    RegistryConstants::PRODUCT => $product->getId(),
                    RegistryConstants::STORE => $this->_storeManager->getStore()->getId(),
                    RegistryConstants::EXTRA_URL_PARAMS => [
                        'product' => (int)$product->getId()
                    ]
                ],
                'frontendProduct'
            );
        }

        return false;
    }

    /**
     * IsShowIcon
     *
     * @return boolean
     */
    public function isShowIcon()
    {
        return $this->configProvider->isShowIcon();
    }

    /**
     * IsShowFilesize
     *
     * @return boolean
     */
    public function isShowFilesize()
    {
        return $this->configProvider->isShowFilesize();
    }

    /**
     * GetWidgetType
     *
     * @return void
     */
    public function getWidgetType()
    {
        return 'tab';
    }
}
