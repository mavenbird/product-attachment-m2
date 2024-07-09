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

class UploadFileData
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $tmpFileName;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var int
     */
    private $fileSize;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * GetFileName
     *
     * @param [type] $mimeType
     * @return void
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * SetFileName
     *
     * @param [type] $mimeType
     * @return void
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * GetTmpFileName
     *
     * @param [type] $mimeType
     * @return void
     */
    public function getTmpFileName()
    {
        return $this->tmpFileName;
    }

    /**
     * SetTmpFileName
     *
     * @param [type] $mimeType
     * @return void
     */
    public function setTmpFileName($tmpFileName)
    {
        $this->tmpFileName = $tmpFileName;
    }

    /**
     * GetExtension
     *
     * @param [type] $mimeType
     * @return void
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * SetExtension
     *
     * @param [type] $mimeType
     * @return void
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * GetFileSize
     *
     * @param [type] $mimeType
     * @return void
     */
    public function getFileSize()
    {
        return (int)$this->fileSize;
    }

    /**
     * SetFileSize
     *
     * @param [type] $mimeType
     * @return void
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = (int)$fileSize;
    }

    /**
     * GetMimeType
     *
     * @param [type] $mimeType
     * @return void
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * SetMimeType
     *
     * @param [type] $mimeType
     * @return void
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }
}
