<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\File\ResourceModel;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Mavenbird\ProductAttachment\Setup\Operation\CreateFileTable;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class File extends AbstractDb
{
    /**
     * Construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(CreateFileTable::TABLE_NAME, FileInterface::FILE_ID);
    }
}
