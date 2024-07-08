<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
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
