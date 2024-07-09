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

namespace Mavenbird\ProductAttachment\Block\Adminhtml\Widget;

class FileRows extends \Magento\Backend\Block\Template
{
    /**
     * Templates
     *
     * @var string
     */
    protected $_template = 'Mavenbird_ProductAttachment::files_rows.phtml';

    /**
     * File
     *
     * @var [type]
     */
    private $files;

    /**
     * SetFiles
     *
     * @param [type] $files
     * @return void
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }
    
    /**
     * GetFiles
     *
     * @return void
     */
    public function getFiles()
    {
        if (empty($this->files)) {
            return [];
        }

        return $this->files;
    }
}
