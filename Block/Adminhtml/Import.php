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
