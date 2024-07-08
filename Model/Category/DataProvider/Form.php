<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
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
