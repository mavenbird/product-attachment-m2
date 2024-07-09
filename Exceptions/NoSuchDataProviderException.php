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


namespace Mavenbird\ProductAttachment\Exceptions;

class NoSuchDataProviderException extends \Magento\Framework\Exception\LocalizedException
{

    /**
     * Construct
     *
     * @param \Magento\Framework\Phrase|null $phrase
     * @param \Exception|null $cause
     * @param integer $code
     */
    public function __construct(\Magento\Framework\Phrase $phrase = null, \Exception $cause = null, $code = 0)
    {
        if (!$phrase) {
            $phrase = __('No such data provider');
        }
        parent::__construct($phrase, $cause, (int) $code);
    }
}
