<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Import\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class ImportCollection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(
            \Mavenbird\ProductAttachment\Model\Import\Import::class,
            \Mavenbird\ProductAttachment\Model\Import\ResourceModel\Import::class
        );
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }
}
