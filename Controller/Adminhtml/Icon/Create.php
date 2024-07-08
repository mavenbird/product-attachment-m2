<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Controller\Adminhtml\Icon;

use Mavenbird\ProductAttachment\Controller\Adminhtml\Icon;

class Create extends Icon
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
