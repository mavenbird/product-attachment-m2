<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
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
