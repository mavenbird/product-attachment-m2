<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\SourceOptions;

use Magento\Framework\Option\ArrayInterface;

class AttachmentType implements ArrayInterface
{
    public const FILE = 0;
    public const LINK = 1;

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
            self::FILE => __('File'),
            self::LINK => __('Link'),
        ];
    }
}
