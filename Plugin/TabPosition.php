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

namespace Mavenbird\ProductAttachment\Plugin;

use Mavenbird\ProductAttachment\Model\ConfigProvider;
use Magento\Catalog\Block\Product\View\Description;

class TabPosition
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * Construct
     *
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        ConfigProvider $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    /**
     * AfterGetGroupChildNames
     *
     * @param Description $block
     * @param [type] $result
     * @return void
     */
    public function afterGetGroupChildNames(Description $block, $result)
    {
        if (!$this->configProvider->isEnabled() || !$this->configProvider->isBlockEnabled()) {
            return $result;
        }

        $layout = $block->getLayout();
        $childNamesSortOrder = [];
        $defaultSortOrder = 0;

        foreach ($result as $childName) {
            $alias = $layout->getElementAlias($childName);
            $sortOrder = (int)$block->getChildData($alias, 'sort_order');

            if (!$sortOrder) {
                $defaultSortOrder += 10;
            }

            $nextTabPositionValue = $this->getNextTabPositionValue(
                $sortOrder ? : $defaultSortOrder,
                $childNamesSortOrder
            );
            $childNamesSortOrder[$nextTabPositionValue] = $childName;
        }

        ksort($childNamesSortOrder, SORT_NUMERIC);

        return $childNamesSortOrder;
    }

    /**
     * GetNextTabPositionValue
     *
     * @param [type] $value
     * @param [type] $childNamesSortOrder
     * @return void
     */
    private function getNextTabPositionValue($value, $childNamesSortOrder)
    {
        while (isset($childNamesSortOrder[$value])) {
            $value++;
        }

        return $value;
    }
}
