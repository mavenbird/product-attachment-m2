<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\Product\DataProvider;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

class Form extends AbstractModifier
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
     * @inheritdoc
     */
    public function modifyMeta(array $meta)
    {
        return $this->metaModifier->execute($meta);
    }

    /**
     * @inheritdoc
     */
    public function modifyData(array $data)
    {
        return $this->dataModifier->execute($data);
    }
}
