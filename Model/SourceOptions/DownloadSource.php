<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\SourceOptions;

use Magento\Framework\Option\ArrayInterface;

class DownloadSource implements ArrayInterface
{
    public const PRODUCT = 1;
    public const CATEGORY = 2;
    public const ORDER = 3;
    public const OTHER = 4;

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
            self::PRODUCT => __('Product'),
            self::CATEGORY => __('Category'),
            self::ORDER => __('Order'),
            self::OTHER => __('Other'),
        ];
    }
}
