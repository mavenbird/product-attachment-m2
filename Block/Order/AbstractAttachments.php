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

namespace Mavenbird\ProductAttachment\Block\Order;

use Mavenbird\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Mavenbird\ProductAttachment\Model\ConfigProvider;
use Mavenbird\ProductAttachment\Model\File\FileScope\FileScopeDataProvider;
use Magento\Framework\View\Element\Template;

abstract class AbstractAttachments extends Template
{
    /**
     * @var int
     */
    protected $productId;

    /**
     * @var int
     */
    protected $orderId;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var ConfigProvider
     */
    protected $configProvider;

    /**
     * @var FileScopeDataProvider
     */
    protected $fileScopeDataProvider;
    
    /**
     * Construct
     *
     * @param ConfigProvider $configProvider
     * @param FileScopeDataProvider $fileScopeDataProvider
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        ConfigProvider $configProvider,
        FileScopeDataProvider $fileScopeDataProvider,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
        $this->fileScopeDataProvider = $fileScopeDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function toHtml()
    {
        $this->productId = $this->getParentBlock()->getItem()->getProductId();
        $this->orderId = $this->getParentBlock()->getItem()->getOrderId();
        $this->storeId = $this->getParentBlock()->getItem()->getOrder()->getStoreId();
        $statusPass = empty($this->getOrderStatuses()) || in_array(
            $this->getParentBlock()->getItem()->getOrder()->getStatus(),
            $this->getOrderStatuses()
        );

        if (!$this->configProvider->isEnabled() || !$this->productId || !$statusPass) {
            return '';
        }

        return parent::toHtml();
    }

    /**
     * GetAttachments
     *
     * @return void
     */
    public function getAttachments()
    {
        return $this->fileScopeDataProvider->execute(
            [
                RegistryConstants::PRODUCT => $this->productId,
                RegistryConstants::STORE => $this->storeId,
                RegistryConstants::EXTRA_URL_PARAMS => [
                    'order' => $this->orderId,
                    'product' => $this->productId
                ],
                RegistryConstants::INCLUDE_FILTER => $this->getAttachmentsFilter()
            ],
            'frontendProduct'
        );
    }

    /**
     * GetAttachmentsFilter
     *
     * @return void
     */
    abstract public function getAttachmentsFilter();

    /**
     * GetOrderStatuses
     *
     * @return void
     */
    abstract public function getOrderStatuses();
}
