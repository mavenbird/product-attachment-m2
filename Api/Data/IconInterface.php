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

namespace Mavenbird\ProductAttachment\Api\Data;

interface IconInterface
{
    public const ICON_ID = 'icon_id';

    public const FILE_TYPE = 'filetype';

    public const IMAGE = 'image';

    public const IS_ACTIVE = 'is_active';

    public const EXTENSION = 'extension';

    /**
     * GetIconId
     *
     * @return void
     */
    public function getIconId();

    /**
     * SetIconId
     *
     * @param [type] $iconId
     * @return void
     */
    public function setIconId($iconId);

    /**
     * GetFileType
     *
     * @return void
     */
    public function getFileType();

    /**
     * SetFileType
     *
     * @param [type] $fileType
     * @return void
     */
    public function setFileType($fileType);

    /**
     * GetImage
     *
     * @return void
     */
    public function getImage();

    /**
     * SetImage
     *
     * @param [type] $image
     * @return void
     */
    public function setImage($image);

    /**
     * IsActive
     *
     * @return boolean
     */
    public function isActive();

    /**
     * SetIsActive
     *
     * @param [type] $isActive
     * @return void
     */
    public function setIsActive($isActive);

    /**
     * GetExtension
     *
     * @return void
     */
    public function getExtension();

    /**
     * SetExtension
     *
     * @param [type] $extensions
     * @return void
     */
    public function setExtension($extensions);
}
