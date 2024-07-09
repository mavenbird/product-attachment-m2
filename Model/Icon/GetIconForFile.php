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

namespace Mavenbird\ProductAttachment\Model\Icon;

use Mavenbird\ProductAttachment\Model\Filesystem\UrlResolver;

class GetIconForFile
{
    /**
     * @var ResourceModel\Icon
     */
    private $iconResource;

    /**
     * @var UrlResolver
     */
    private $urlResolver;

    /**
     * Construct
     *
     * @param ResourceModel\Icon $iconResource
     * @param UrlResolver $urlResolver
     */
    public function __construct(
        ResourceModel\Icon $iconResource,
        UrlResolver $urlResolver
    ) {
        $this->iconResource = $iconResource;
        $this->urlResolver = $urlResolver;
    }

    /**
     * ByFileName
     *
     * @param [type] $filename
     * @return void
     */
    public function byFileName($filename)
    {
        if (!empty($filename)) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            if (!empty($extension) && $iconImage = $this->iconResource->getExtensionIconImage($extension)) {
                return $this->urlResolver->getIconUrlByName($iconImage);
            }
        }

        return false;
    }

    /**
     * ByFileExtension
     *
     * @param [type] $ext
     * @return void
     */
    public function byFileExtension($ext)
    {
        if (!empty($ext)) {
            if ($iconImage = $this->iconResource->getExtensionIconImage($ext)) {
                return $this->urlResolver->getIconUrlByName($iconImage);
            }
        }

        return false;
    }
}
