<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
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
