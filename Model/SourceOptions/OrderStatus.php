<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\SourceOptions;

class OrderStatus extends \Magento\Sales\Model\Config\Source\Order\Status
{
    /**
     * ToOptionArray
     *
     * @return void
     */
    public function toOptionArray()
    {
        //remove Please Select option
        return array_slice(parent::toOptionArray(), 1);
    }
}
