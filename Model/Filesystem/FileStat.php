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

namespace Mavenbird\ProductAttachment\Model\Filesystem;

class FileStat
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;
    
    /**
     * Construct
     *
     * @param \Magento\Framework\Filesystem $filesystem
     * @param string $fileName
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        $fileName = ''
    ) {
        //TODO check if file exists
        $this->fileName = $fileName;
        $this->filesystem = $filesystem;
        $this->collectFileStat();
    }

    /**
     * CollectFileStat
     *
     * @return void
     */
    private function collectFileStat()
    {
    }
}
