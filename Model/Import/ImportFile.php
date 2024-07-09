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

namespace Mavenbird\ProductAttachment\Model\Import;

use Mavenbird\ProductAttachment\Api\Data\FileInterface;
use Magento\Framework\Model\AbstractModel;

class ImportFile extends AbstractModel
{
    public const IMPORT_FILE_ID = 'import_file_id';
    public const IMPORT_ID = 'import_id';

    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init(\Mavenbird\ProductAttachment\Model\Import\ResourceModel\ImportFile::class);
        $this->setIdFieldName(self::IMPORT_FILE_ID);
    }

    /**
     * SetImportFileId
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function setImportFileId($importFileId)
    {
        return $this->setData(self::IMPORT_FILE_ID, (int)$importFileId);
    }

    /**
     * GetImportFileId
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function getImportFileId()
    {
        return (int)$this->_getData(self::IMPORT_FILE_ID);
    }

    /**
     * SetImportId
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function setImportId($importId)
    {
        return $this->setData(self::IMPORT_ID, (int)$importId);
    }

    /**
     * GetImportId
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function getImportId()
    {
        return (int)$this->_getData(self::IMPORT_ID);
    }

    /**
     * GetFilePath
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function getFilePath()
    {
        return $this->_getData(FileInterface::FILE_PATH);
    }

    /**
     * SetFilePath
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function setFilePath($filePath)
    {
        return $this->setData(FileInterface::FILE_PATH, $filePath);
    }

    /**
     * GetFileName
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function getFileName()
    {
        return $this->_getData(FileInterface::FILENAME);
    }

    /**
     * SetFileName
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function setFileName($fileName)
    {
        return $this->setData(FileInterface::FILENAME, $fileName);
    }

    /**
     * GetLabel
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function getLabel()
    {
        return $this->_getData(FileInterface::LABEL);
    }

    /**
     * SetLabel
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function setLabel($label)
    {
        return $this->setData(FileInterface::LABEL, $label);
    }

    /**
     * GetCustomerGroups
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function getCustomerGroups()
    {
        $customerGroups = $this->_getData(FileInterface::CUSTOMER_GROUPS);
        if ($customerGroups !== null && $customerGroups !== "" && !is_array($customerGroups)) {
            $customerGroups = explode(',', $customerGroups);
        }
        return $customerGroups;
    }

    /**
     * SetCustomerGroups
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function setCustomerGroups($customerGroups)
    {
        return $this->setData(FileInterface::CUSTOMER_GROUPS, $customerGroups);
    }

    /**
     * IsVisible
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function isVisible()
    {
        return (bool)$this->_getData(FileInterface::IS_VISIBLE);
    }

    /**
     * SetIsVisible
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function setIsVisible($isVisible)
    {
        return $this->setData(FileInterface::IS_VISIBLE, (bool)$isVisible);
    }

    /**
     * IsIncludeInOrder
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function isIncludeInOrder()
    {
        return (bool)$this->_getData(FileInterface::INCLUDE_IN_ORDER);
    }

    /**
     * SetIsIncludeInOrder
     *
     * @param [type] $isIncludeInOrder
     * @return void
     */
    public function setIsIncludeInOrder($isIncludeInOrder)
    {
        return $this->setData(FileInterface::INCLUDE_IN_ORDER, (bool)$isIncludeInOrder);
    }
}
