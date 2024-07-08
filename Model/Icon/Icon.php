<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Icon;

use Mavenbird\ProductAttachment\Api\Data\IconInterface;
use Magento\Framework\Model\AbstractModel;

class Icon extends AbstractModel implements IconInterface
{
    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init(\Mavenbird\ProductAttachment\Model\Icon\ResourceModel\Icon::class);
        $this->setIdFieldName(IconInterface::ICON_ID);
    }

    /**
     * GetIconId
     *
     * @return void
     */
    public function getIconId()
    {
        return (int)$this->_getData(IconInterface::ICON_ID);
    }

    /**
     * SetIconId
     *
     * @param [type] $iconId
     * @return void
     */
    public function setIconId($iconId)
    {
        return $this->setData(IconInterface::ICON_ID, (int)$iconId);
    }

    /**
     * GetFileType
     *
     * @return void
     */
    public function getFileType()
    {
        return $this->_getData(IconInterface::FILE_TYPE);
    }

    /**
     * SetFileType
     *
     * @param [type] $fileType
     * @return void
     */
    public function setFileType($fileType)
    {
        return $this->setData(IconInterface::FILE_TYPE, $fileType);
    }

    /**
     * GetImage
     *
     * @return void
     */
    public function getImage()
    {
        return $this->_getData(IconInterface::IMAGE);
    }

    /**
     * SetImage
     *
     * @param [type] $image
     * @return void
     */
    public function setImage($image)
    {
        return $this->setData(IconInterface::IMAGE, $image);
    }

    /**
     * IsActive
     *
     * @return boolean
     */
    public function isActive()
    {
        return (bool)$this->_getData(IconInterface::IS_ACTIVE);
    }

    /**
     * SetIsActive
     *
     * @param [type] $isActive
     * @return void
     */
    public function setIsActive($isActive)
    {
        return $this->setData(IconInterface::IS_ACTIVE, (bool)$isActive);
    }

    /**
     * GetExtension
     *
     * @return void
     */
    public function getExtension()
    {
        if (($extensions = $this->_getData(IconInterface::EXTENSION)) === null) {
            $extensions = $this->_getResource()->getIconExtensions($this->getIconId());
            $this->setExtension($extensions);
        }
        return $extensions;
    }

    /**
     * SetExtension
     *
     * @param [type] $extensions
     * @return void
     */
    public function setExtension($extensions)
    {
        return $this->setData(IconInterface::EXTENSION, $extensions);
    }
}
