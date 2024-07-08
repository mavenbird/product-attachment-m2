<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\SourceOptions;

use Magento\Framework\Option\ArrayInterface;

class UrlType implements ArrayInterface
{
    public const ID = 0;
    public const HASH = 1;

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
            self::ID => __('Id'),
            self::HASH => __('Hash'),
        ];
    }
}
