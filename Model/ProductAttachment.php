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

namespace Mavenbird\ProductAttachment\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Product Attachment Model
 */
class ProductAttachment extends AbstractModel
{

    /**
     * Init Attachment
     */
    protected function _construct()
    {
        $this->_init(\Mavenbird\ProductAttachment\Model\ResourceModel\ProductAttachment::class);
    }
    
    /**
     * Fetch product for Attachment
     *
     * @param \Mavenbird\ProductAttachment\Model\ProductAttachment  $object
     * @return $string
     */
    public function getProducts(\Mavenbird\ProductAttachment\Model\ProductAttachment $object)
    {
        $tbl = $this->getResource()->getTable(
            \Mavenbird\ProductAttachment\Model\ResourceModel\ProductAttachment::TBL_ATT_PRODUCT
        );
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['product_id']
        )
        ->where(
            'attach_id = ?',
            (int)$object->getId()
        );
        return $this->getResource()->getConnection()->fetchCol($select);
    }
}
