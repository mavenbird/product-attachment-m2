<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Api\Data;

interface IconInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    public const ICON_ID = 'icon_id';

    public const FILE_TYPE = 'filetype';

    public const IMAGE = 'image';

    public const IS_ACTIVE = 'is_active';

    public const EXTENSION = 'extension';
    /**#@-*/

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
