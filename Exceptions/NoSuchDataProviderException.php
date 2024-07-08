<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Exceptions;

class NoSuchDataProviderException extends \Magento\Framework\Exception\LocalizedException
{
    /**
     * @param \Magento\Framework\Phrase $phrase
     * @param \Exception $cause
     * @param int $code
     */
    public function __construct(\Magento\Framework\Phrase $phrase = null, \Exception $cause = null, $code = 0)
    {
        if (!$phrase) {
            $phrase = __('No such data provider');
        }
        parent::__construct($phrase, $cause, (int) $code);
    }
}
