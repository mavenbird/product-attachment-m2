<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\File;

use Mavenbird\ProductAttachment\Controller\Adminhtml\File;

class Create extends File
{
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
