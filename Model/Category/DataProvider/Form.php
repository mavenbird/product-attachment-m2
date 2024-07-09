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

namespace Mavenbird\ProductAttachment\Model\Category\DataProvider;

class Form
{
    /**
     * @var Modifiers\Meta
     */
    private $metaModifier;

    /**
     * @var Modifiers\Data
     */
    private $dataModifier;
    
    /**
     * Construct
     *
     * @param Modifiers\Meta $metaModifier
     * @param Modifiers\Data $dataModifier
     */
    public function __construct(
        Modifiers\Meta $metaModifier,
        Modifiers\Data $dataModifier
    ) {

        $this->metaModifier = $metaModifier;
        $this->dataModifier = $dataModifier;
    }

    /**
     * AfterGetMeta
     *
     * @param [type] $subject
     * @param [type] $meta
     * @return void
     */
    public function afterGetMeta($subject, $meta)
    {
        return $this->metaModifier->execute($meta);
    }

    /**
     * AfterGetData
     *
     * @param [type] $subject
     * @param [type] $data
     * @return void
     */
    public function afterGetData($subject, $data)
    {
        return $this->dataModifier->execute($data);
    }
}
