<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
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
