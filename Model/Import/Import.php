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

use Magento\Framework\Model\AbstractModel;

class Import extends AbstractModel
{
    public const IMPORT_ID = 'import_id';
    public const STORE_IDS = 'store_ids';
    public const CREATED_AT = 'created_at';

    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init(\Mavenbird\ProductAttachment\Model\Import\ResourceModel\Import::class);
        $this->setIdFieldName(self::IMPORT_ID);
    }

    /**
     * SetImportId
     *
     * @param [type] $importId
     * @return void
     */
    public function setImportId($importId)
    {
        return $this->setData(self::IMPORT_ID, (int)$importId);
    }

    /**
     * GetImportId
     *
     * @return void
     */
    public function getImportId()
    {
        return (int)$this->_getData(self::IMPORT_ID);
    }

    /**
     * SetStoreIds
     *
     * @param [type] $storeIds
     * @return void
     */
    public function setStoreIds($storeIds)
    {
        if (is_array($storeIds)) {
            return $this->setData(self::STORE_IDS, implode(',', $storeIds));
        }

        return $this->setData(self::STORE_IDS, $storeIds);
    }

    /**
     * GetStoreIds
     *
     * @return void
     */
    public function getStoreIds()
    {
        $storeIds = $this->_getData(self::STORE_IDS);
        
        // Check if $storeIds is null or empty string
        if ($storeIds === null || $storeIds === '') {
            return [];
        }
        
        if (!is_array($storeIds)) {
            $storeIds = explode(',', $storeIds);
        }

        return $storeIds;
    }

    /**
     * GetCreatedAt
     *
     * @return void
     */
    public function getCreatedAt()
    {
        return $this->_getData(self::CREATED_AT);
    }
}
