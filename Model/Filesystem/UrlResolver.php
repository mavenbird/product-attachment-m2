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
use Mavenbird\ProductAttachment\Model\Filesystem\File;
use Magento\Framework\UrlInterface;

class UrlResolver
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var File
     */
    private $file;
    
    /**
     * Construct
     *
     * @param UrlInterface $urlBuilder
     * @param File $file
     */
    public function __construct(
        UrlInterface $urlBuilder,
        File $file
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->file = $file;
    }

    /**
     * GetIconUrlByName
     *
     * @param [type] $name
     * @return void
     */
    public function getIconUrlByName($name)
    {
        if (!($icon = $this->file->getFilePath($name, Directory::ICON))) {
            return false;
        }

        $baseUrl = $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
        $baseUrl = trim(str_replace('index.php', '', $baseUrl), '/');

        return $baseUrl . '/' . $icon;
    }

    /**
     * GetAttachmentUrlByName
     *
     * @param [type] $name
     * @return void
     */
    public function getAttachmentUrlByName($name)
    {
        if (!($file = $this->file->getFilePath($name, Directory::ATTACHMENT))) {
            return false;
        }

        $baseUrl = $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
        $baseUrl = trim(str_replace('index.php', '', $baseUrl), '/');

        return $baseUrl . '/' . $file;
    }
}
