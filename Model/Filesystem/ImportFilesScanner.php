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

use Mavenbird\ProductAttachment\Model\Filesystem\Directory;
use Magento\Framework\App\Filesystem\DirectoryList;

class ImportFilesScanner
{
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;

    /**
     * Construct
     *
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $result = [];

        $folders = $this->mediaDirectory->read(Directory::DIRECTORY_CODES[Directory::IMPORT_FTP]);
        foreach ($folders as $file) {
            if ($this->mediaDirectory->isFile($file)) {
                $result[] = basename($file);
            }
        }

        return $result;
    }
}
