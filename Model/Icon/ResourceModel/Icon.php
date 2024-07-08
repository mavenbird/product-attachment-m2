<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Icon\ResourceModel;

use Mavenbird\ProductAttachment\Api\Data\IconInterface;
use Mavenbird\ProductAttachment\Model\Icon\OptionSource\Status;
use Mavenbird\ProductAttachment\Setup\Operation\CreateIconExtensionTable;
use Mavenbird\ProductAttachment\Setup\Operation\CreateIconTable;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Icon extends AbstractDb
{
    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
         $this->_init(CreateIconTable::TABLE_NAME, IconInterface::ICON_ID);
    }

    /**
     * AfterSave
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->saveExtensions($object);
        parent::_afterSave($object);
    }

    /**
     * SaveExtensions
     *
     * @param [type] $icon
     * @return void
     */
    public function saveExtensions($icon)
    {
        if ($icon->getIconId()) {
            $this->getConnection()->delete(
                $this->getTable(CreateIconExtensionTable::TABLE_NAME),
                [IconInterface::ICON_ID . ' = ?' => $icon->getIconId()]
            );
        }

        if (($extensions = $icon->getExtension()) && is_array($extensions)) {
            foreach ($extensions as $extension) {
                $bind = [IconInterface::ICON_ID => $icon->getIconId(), IconInterface::EXTENSION => $extension];
                $this->getConnection()->insert(
                    $this->getTable(CreateIconExtensionTable::TABLE_NAME),
                    $bind
                );
            }
        }
    }

    /**
     * GetIconExtensions
     *
     * @param [type] $iconId
     * @return void
     */
    public function getIconExtensions($iconId)
    {
        $select = $this->getConnection()->select()
            ->from(['ie' => $this->getTable(CreateIconExtensionTable::TABLE_NAME)])
            ->where('ie.' . IconInterface::ICON_ID . ' = ?', (int)$iconId)
            ->reset(\Magento\Framework\DB\Select::COLUMNS)
            ->columns('ie.' . IconInterface::EXTENSION);

        if ($result = $this->getConnection()->fetchAll($select)) {
            array_walk($result, function (&$item) {
                $item = $item[IconInterface::EXTENSION];
            });
            return $result;
        }

        return [];
    }

    /**
     * GetAllowedExtensions
     *
     * @return void
     */
    public function getAllowedExtensions()
    {
        $select = $this->getConnection()->select()
            ->from(
                ['ie' => $this->getTable(CreateIconExtensionTable::TABLE_NAME)],
                [IconInterface::EXTENSION]
            )->joinInner(
                ['icon' => $this->getMainTable()],
                'ie.' . IconInterface::ICON_ID . ' = ' . 'icon.' . IconInterface::ICON_ID
            )->where('icon.' . IconInterface::IS_ACTIVE . ' = ?', Status::ENABLED);

        if ($result = $this->getConnection()->fetchAll($select)) {
            $allowExtensions = [];
            foreach ($result as $extension) {
                $allowExtensions[] = $extension[IconInterface::EXTENSION];
            }

            return $allowExtensions;
        }

        return [];
    }

    /**
     * GetExtensionIconImage
     *
     * @param [type] $extension
     * @return void
     */
    public function getExtensionIconImage($extension)
    {
        $select = $this->getConnection()->select()
            ->from(['ie' => $this->getTable(CreateIconExtensionTable::TABLE_NAME)])
            ->joinInner(
                ['icon' => $this->getTable(CreateIconTable::TABLE_NAME)],
                'ie.' . IconInterface::ICON_ID . ' = ' . 'icon.' . IconInterface::ICON_ID
            )->where('icon.' . IconInterface::IS_ACTIVE . ' = ?', Status::ENABLED)
            ->where('ie.' . IconInterface::EXTENSION . ' = ?', $extension)
            ->reset(\Magento\Framework\DB\Select::COLUMNS)
            ->columns('icon.' . IconInterface::IMAGE);

        if ($result = $this->getConnection()->fetchOne($select)) {
            return $result;
        }

        return false;
    }
}
