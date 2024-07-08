<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Block\Adminhtml;

class Import extends \Magento\Backend\Block\Template
{
    /**
     * GetGenerateUrl
     *
     * @return void
     */
    public function getGenerateUrl()
    {
        return $this->getUrl('file/import/generate', ['import_id' => $this->getRequest()->getParam('import_id')]);
    }
    /**
     * GetFinishLink
     *
     * @return void
     */
    public function getFinishLink()
    {
        return $this->getUrl('file/import/index');
    }
}
