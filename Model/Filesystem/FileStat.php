<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
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
