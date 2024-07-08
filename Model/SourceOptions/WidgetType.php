<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
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
