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

namespace Mavenbird\ProductAttachment\Block\Adminhtml;

use Magento\Backend\Block\Template;

class Steps extends Template
{
    public const STEP1 = 'files';
    public const STEP2 = 'stores';
    public const STEP3 = 'import';

    public const STEPS = [
        self::STEP1 => 'Prepare Files For Import',
        self::STEP2 => 'Select Stores For Configuration',
        self::STEP3 => 'Import Your Files'
    ];

    /**
     * @var string
     */
    private $currentStep;

    /**
     * @var string
     */
    private $backLink;

    /**
     * @var string
     */
    private $nextLink;

    /**
     * SetCurrentStep
     *
     * @param [type] $currentStep
     * @return void
     */
    public function setCurrentStep($currentStep)
    {
        $this->currentStep = $currentStep;

        return $this;
    }

    /**
     * GetCurrentStep
     *
     * @return void
     */
    public function getCurrentStep()
    {
        if (!array_key_exists($this->currentStep, self::STEPS)) {
            return self::STEP1;
        }

        return $this->currentStep;
    }

    /**
     * GetSteps
     *
     * @return void
     */
    public function getSteps()
    {
        return self::STEPS;
    }

    /**
     * GetBackLink
     *
     * @return void
     */
    public function getBackLink()
    {
        return $this->backLink;
    }

    /**
     * SetBackLink
     *
     * @param [type] $backLink
     * @return void
     */
    public function setBackLink($backLink)
    {
        $this->backLink= $backLink;

        return $this;
    }

    /**
     * GetNextLink
     *
     * @return void
     */
    public function getNextLink()
    {
        return $this->nextLink;
    }

    /**
     * SetNextLink
     *
     * @param [type] $nextLink
     * @return void
     */
    public function setNextLink($nextLink)
    {
        $this->nextLink = $nextLink;

        return $this;
    }
    
    /**
     * GetImportListingUrl
     *
     * @return void
     */
    public function getImportListingUrl()
    {
        return $this->getUrl('file/import/index');
    }
}
