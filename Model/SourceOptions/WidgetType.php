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

namespace Mavenbird\ProductAttachment\Model\SourceOptions;

use Magento\Framework\Option\ArrayInterface;

class WidgetType implements ArrayInterface
{
    public const CURRENT_CATEGORY = 1;
    public const SPECIFIC_CATEGORY = 2;
    public const CURRENT_PRODUCT = 3;
    public const SPECIFIC_PRODUCT = 4;
    public const CUSTOM_FILES = 5;

    /**
     * ToOptionArray
     *
     * @return void
     */
    public function toOptionArray()
    {
        $optionArray = [];
        foreach ($this->toArray() as $widgetType => $label) {
            $optionArray[] = ['value' => $widgetType, 'label' => $label];
        }
        return $optionArray;
    }

    /**
     * ToArray
     *
     * @return void
     */
    public function toArray()
    {
        return [
            self::CURRENT_CATEGORY => __('Current Category'),
            self::SPECIFIC_CATEGORY => __('Specific Category'),
            self::CURRENT_PRODUCT => __('Current Product'),
            self::SPECIFIC_PRODUCT => __('Specific Product'),
            self::CUSTOM_FILES => __('Custom Files'),
        ];
    }
}
