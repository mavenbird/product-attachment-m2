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
 * @package    Mavenbird_ProductAttachment
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */ 

namespace Mavenbird\ProductAttachment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Model\AbstractModel;

/**
 * ProductAttachment resource Model
 */
class ProductAttachment extends AbstractDb
{
    public const TBL_ATT_PRODUCT = 'attach_product';

    /**
     * @var DateTime
     */
    public $_date;

    /**
     * @var  \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     *
     * @param Context                                     $context
     * @param DateTime                                    $date
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param string                                      $resourcePrefix
     */
    public function __construct(
        Context $context,
        DateTime $date,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
        $this->messageManager = $messageManager;
    }

    /**
     * Initialize construct
     */
    protected function _construct()
    {
        $this->_init('product_attachment', 'attach_id');
    }

    /**
     * Check before save model
     *
     * @param AbstractModel $object
     * @return parent::_beforeSave
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if ($object->getStoreId()) {
            $newStores = $object->getStoreId();
            $oldstores =  [$this->lookupStoreIds($object->getId())];
            $connection = $this->getConnection();
            $table = $this->getTable('attachment_store');
            $old = [];

            foreach ($oldstores as $key => $value) {
                $old = array_values($oldstores[$key]);
            }

            $delete = array_diff($old, $newStores);

            if ($delete) {
                $where = [
                'attach_id' . ' = ?' => $object->getId(),
                'store_id IN (?)' => $delete,
                ];
                try {
                    $connection->delete($table, $where);
                } catch (\Exception $ex) {

                    $this->messageManager->addError($ex);
                }
            }
       
            $insert = array_diff($newStores, $old);
            
            if ($insert) {
                $data = [];
                foreach ($insert as $storeId) {
                    $data[] = [
                    'attach_id'=> $object->getId(),
                    'store_id' => (int)$storeId,
                    ];
                }
                try {
                    $connection->insertMultiple($table, $data);
                } catch (\Exception $ex) {
                    $this->messageManager->addError($ex);
                }
            }
        }
        if ($object->isObjectNew() && !$object->hasCreationTime()) {
            $object->setCreationTime($this->_date->gmtDate());
        }
        $object->setUpdateTime($this->_date->gmtDate());
        return parent::_beforeSave($object);
    }

    /**
     * Get Load select
     *
     * @param string $field
     * @param string $value
     * @param string $object
     * @return $select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        
        $select->joinRight(
            ['cbs' => $this->getTable('attachment_store')],
            $this->getMainTable() . '.attach_id = cbs.attach_id',
            ['store_id']
        );

        return $select;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

        $linkField = 'attach_id';
      
        $select = $connection->select()
            ->from(['cbs' => $this->getTable('attachment_store')], 'store_id')
            ->join(
                ['cb' => $this->getMainTable()],
                'cbs.' . $linkField . ' = cb.' . $linkField,
                []
            )
            ->where('cb.' . $linkField . ' = :attach_id');

        return $connection->fetchCol($select, ['attach_id' => (int)$id]);
    }
}
