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

class CustomerGroup implements ArrayInterface
{

    /**
     * @var \Magento\Customer\Model\Customer\Attribute\Source\Group
     */
    protected $groupSource;

    /**
     * Construct
     *
     * @param \Magento\Customer\Model\Customer\Attribute\Source\Group $groupSource
     */
    public function __construct(\Magento\Customer\Model\Customer\Attribute\Source\Group $groupSource)
    {
        $this->groupSource = $groupSource;
    }

    /**
     * ToOptionArray
     *
     * @return void
     */
    public function toOptionArray()
    {
        $optionArray = [];
        $options = $this->groupSource->getAllOptions();
        if (!empty($options[0]) && is_array($options[0]['value'])) {
            array_unshift($options[0]['value'], ['value' => '0', 'label' =>  __('NOT LOGGED IN')]);
            foreach ($options as &$optionGroup) {
                foreach ($optionGroup['value'] as &$option) {
                    $option['value'] = (string)$option['value'];
                }
            }
            $optionArray = $options;
        } else {
            foreach ($this->toArray() as $stepId => $label) {
                $optionArray[] = ['value' => $stepId, 'label' => $label];
            }
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
        $options = $this->groupSource->getAllOptions();
        $result = ['0'  => __('NOT LOGGED IN')];

        /**
         * B2B Fix
         */
        if (!empty($options[0]) && is_array($options[0]['value'])) {
            $options = $options[0]['value'];
        }

        foreach ($options as $option) {
            $result[$option['value']] = $option['label'];
        }

        return $result;
    }
}
