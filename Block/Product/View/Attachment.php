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

namespace Mavenbird\ProductAttachment\Block\Product\View;

use Magento\Catalog\Block\Product\AbstractProduct;

/**
 * Attachment class in product view
 */
class Attachment extends AbstractProduct
{
    /**
     * @var \Mavenbird\ProductAttachment\Model\AttachProduct
     */
    protected $_attachProduct;

    /**
     * @var \Mavenbird\ProductAttachment\Model\ProductAttachment
     */
    protected $_productAttachment;

    /**
     *
     * @param \Magento\Catalog\Block\Product\Context              $context
     * @param \Mavenbird\ProductAttachment\Model\AttachProduct     $attachProduct
     * @param \Mavenbird\ProductAttachment\Model\ProductAttachment $productAttachment
     * @param \Magento\Customer\Model\Session                     $session
     * @param array                                               $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Mavenbird\ProductAttachment\Model\AttachProduct $attachProduct,
        \Mavenbird\ProductAttachment\Model\ProductAttachment $productAttachment,
        \Magento\Customer\Model\Session $session,
        array $data = []
    ) {
        $this->_attachProduct=$attachProduct;
        $this->_productAttachment=$productAttachment;
        $this->storeManager = $context->getStoreManager();
        $this->session = $session;
        parent::__construct($context, $data);
    }

    /**
     * Check available attachment and return collection
     *
     * @param string $id
     */
    public function checkAttachAvailable($id)
    {
        $model = $this->_attachProduct->getCollection()->addFieldToFilter("product_id", $id);
       
        $attach_id=[];
        foreach ($model as $attach) {
            $attach_id[]=$attach['attach_id'];
        }
        $collection=$this->_productAttachment->getCollection()
                    ->addFieldToFilter("astatus", "Enabled")
                    ->addFieldToFilter('main_table.attach_id', ['in' => $attach_id]);

        $resource = $collection->getResource();
        $collection->getSelect()->join(
            ['atstore' => $resource->getTable('attachment_store')],
            'main_table.attach_id = atstore.attach_id',
            ['store_id']
        )->group('attach_id');
       
        $collection->setOrder('sort_order', 'ASC');
        $collection->addFieldToFilter('store_id', ['in' => [0, $this->storeManager->getStore()->getId()]]);
        $collection->addFieldToFilter('customer_group', ['finset' => $this->session->getCustomerGroupId()]);
        return $collection;
    }

    /**
     * Get Media Url
     *
     * @param  string $path
     * @return string
     */
    public function getMediaUrl($path)
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$path;
    }
}
