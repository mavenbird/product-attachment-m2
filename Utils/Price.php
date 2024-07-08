<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Utils;

use Magento\Framework\Locale\CurrencyInterface;
use Magento\Store\Model\StoreManagerInterface;

class Price
{
    /**
     * @var CurrencyInterface
     */
    private $currency;

    /**
     * Price constructor.
     *
     * @param CurrencyInterface     $localeCurrency
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(CurrencyInterface $localeCurrency, StoreManagerInterface $storeManager)
    {
        $this->currency = $localeCurrency->getCurrency(
            $storeManager->getStore()->getBaseCurrencyCode()
        );
    }

    /**
     * ToDefaultCurrency
     *
     * @param integer $price
     * @return void
     */
    public function toDefaultCurrency($price = 0)
    {
        return $this->currency->toCurrency(sprintf("%f", $price));
    }
}
