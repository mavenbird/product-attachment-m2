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
 * @package    Mavenbird_ProductAttachment
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */ 

namespace Mavenbird\ProductAttachment\Block\Adminhtml\Productattachment\Edit\Tabs;

/**
 * ProductAttachment Main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $_customerGroup;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     *
     * @param \Magento\Backend\Block\Template\Context                $context
     * @param \Magento\Framework\Registry                            $registry
     * @param \Magento\Framework\Data\FormFactory                    $formFactory
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup
     * @param \Magento\Store\Model\System\Store                      $systemStore
     * @param array                                                  $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_customerGroup = $customerGroup;
        $this->_systemStore = $systemStore;
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('productattachment_data');
        $isElementDisabled = false;
        $form = $this->_formFactory->create(
            ['data' => [
                            'id' => 'edit_form',
                            'enctype' => 'multipart/form-data',
                            'action' => $this->getData('action'),
                            'method' => 'POST'
                        ]
            ]
        );
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Attachment Information')]);

        if ($model->getId()) {
            $fieldset->addField('attach_id', 'hidden', ['name' => 'attach_id']);
        }

        $fieldset->addField('product_id', 'hidden', ['name' => 'product_id']);

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'id' => 'title',
                'label' => __('Description'),
                'title' => __('Title'),
                'required' => true,
                'after_element_html'=>'<small>Enter Attachment Short Description.</small>'
            ]
        );
        
        $fieldset->addField(
            'astatus',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'astatus',
                'id' => 'astatus',
                'required' => false,
                'options' => ['Enabled' => __('Enabled'), 'Disabled' => __('Disabled')],
            ]
        );
        
        $fieldset->addField(
            'icon',
            'image',
            [
                'title' => __('image'),
                'label' => __('Thumbnail'),
                'name' => 'icon',
                'note' => 'Allow Image type: jpg, jpeg, gif, png, icon.'
            ]
        );

        $fieldset->addField(
            'video',
            'file',
            [
                'title' => __('video'),
                'label' => __('Video'),
                'name' => 'video',
                'note' => 'Allow Video type: mp4, mkv, mpg, webm, mov.'.'<br/>'.'Video size must be less or equal 2Mb.',
                'after_element_html'=>'<small>'.$model->getData('video').'</small>'
            ]
        );

        $fieldset->addField(
            'file',
            'file',
            [
                'title' => __('File'),
                'label' => __('Choose File'),
                'name' => 'file',
                'note' => 'Allow File type: pdf, doc, txt, xml, docx, xlsx, xls, csv, html, htm, php.',
                'after_element_html'=>'<small>'.$model->getData('file').'</small>'
            ]
        );

        $fieldset->addField(
            'url',
            'text',
            [
                'name' => 'url',
                'id' => 'url',
                'label' => __('URL'),
                'title' => __('URL'),
                'note' => 'Enter URL'
            ]
        );

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'title' => __('Sort Order'),
                'label' => __('Sort Order'),
                'name' => 'sort_order'

            ]
        );

        $fieldset->addField('customer_group', 'multiselect', [
          'label'    => __('Customer Group'),
          'class'    => 'required-entry',
          'required' => true,
          'name'     => 'customer_group',
          'values'   => $this->getCustomerGroups()
        ]);

        $fieldset->addField(
            'store_id',
            'multiselect',
            [
             'name'     => 'store_id[]',
             'label'    => __('Store Views'),
             'title'    => __('Store Views'),
             'required' => true,
             'values'   => $this->_systemStore->getStoreValuesForForm(false, true),
            ]
        );
        
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Is File Required
     *
     * @return boolean
     */
    public function isFileRequired()
    {
        $model = $this->_coreRegistry->registry('productattachment_data');
        if (empty($model->getData('file'))) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Get tab Label
     *
     * @return $string
     */
    public function getTabLabel()
    {
        return __('Information');
    }

    /**
     * Prepare title for tab
     *
     * @return $string
     */
    public function getTabTitle()
    {
        return __('Information');
    }

    /**
     * @inheritdoc
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Get Customer Groups
     */
    protected function getCustomerGroups()
    {
        return $this->_customerGroup->toOptionArray();
    }
}
