<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.Mavenbird.com)
 * @package Mavenbird_ProductAttachment
 */


namespace Mavenbird\ProductAttachment\Model\File\DataProvider\Modifiers;

class Category
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Categories
     */
    private $categoriesModifier;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $productFactory;

    /**
     * Construct
     *
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Categories $categoriesModifier
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Categories $categoriesModifier,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        $this->categoriesModifier = $categoriesModifier;
        $this->productFactory = $productFactory;
    }

    /**
     * AddCategoryField
     *
     * @param [type] $meta
     * @param [type] $store
     * @return void
     */
    public function addCategoryField(&$meta, $store)
    {
        $this->setCategoriesContainer($meta);
        /** Magento 2.3 support */
        $currentStore = $this->registry->registry('current_store');
        $this->registry->unregister('current_store');
        $this->registry->register('current_store', $this->storeManager->getStore($store));
        $this->registry->register('current_product', $this->productFactory->create());
        $meta = $this->categoriesModifier->modifyMeta($meta);
        $this->registry->unregister('current_store');
        $this->registry->register('current_store', $currentStore);
        unset($meta['create_category_modal']);
        unset($meta['additional']['children']['container_category_ids']['children']['create_category_button']);
    }

    /**
     * SetCategoriesContainer
     *
     * @param [type] $meta
     * @return void
     */
    public function setCategoriesContainer(&$meta)
    {
        $meta['additional']['children']['container_category_ids'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'breakLine' => false,
                        'label' => 'Categories',
                        'required' => '0',
                        'sortOrder' => 90,
                    ]
                ]
            ],
            'children' => [
                'category_ids' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'dataType' => 'text',
                                'formElement' => 'input',
                                'visible' => '1',
                                'required' => '0',
                                'label' => 'Categories',
                                'code' => 'category_ids',
                                'sortOrder' => 90,
                                'componentType' => 'field',
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
