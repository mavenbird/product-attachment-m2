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

namespace Mavenbird\ProductAttachment\Block\Adminhtml\Productattachment;

/**
 * Attachment Edit Form
 */
class EditForm extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry           $registry
     * @param array                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize store post edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'attach_id';
        $this->_blockGroup = 'Mavenbird_ProductAttachment';
        $this->_controller = 'adminhtml_productattachment';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save Attachment'));
        $this->buttonList->update('save', 'onclick', 'clickedEl()');
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ],
                'onclick' => 'clickedEl()'
            ],
            -100
        );
        $this->buttonList->update('delete', 'label', __('Delete'));
    }

    /**
     * Retrieve text for header element depending on loaded post
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $newsRegistry = $this->_coreRegistry->registry('productattachment_data');
        if ($newsRegistry->getId()) {
            $newsTitle = $this->escapeHtml($newsRegistry->getTitle());
            return __("Edit Attachment '%1'", $newsTitle);
        } else {
            return __('Add Attachment');
        }
    }

    /**
     * Getter of url for "Save and Continue" button tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }

    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
        function clickedEl() {
            var myForm = jQuery('#edit_form');
            var formAlreadySubmitted = false;
            console.log(myForm.validation('isValid'));
            jQuery('#edit_form').submit(function(e){
            if(myForm.validation('isValid')){
                if(formAlreadySubmitted){
                    e.preventDefault();
                        return false;
                    }
                    var disableSave = jQuery('#save');
                    disableSave.attr('disabled', 'disabled');
                    disableSave.addClass('disabled');
                    
                    var disablesaveandcontinue = jQuery('#saveandcontinue');
                    disablesaveandcontinue.attr('disabled', 'disabled');
                    disablesaveandcontinue.addClass('disabled');
                    formAlreadySubmitted = true;
                }
            });
        }
        function toggleEditor() {
            if (tinyMCE.getInstanceById('page_content') == null) {
                tinyMCE.execCommand('mceAddControl', false, 'content');
            } else {
                tinyMCE.execCommand('mceRemoveControl', false, 'content');
            }
        };

        ";
        return parent::_prepareLayout();
    }
}
