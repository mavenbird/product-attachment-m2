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

namespace Mavenbird\ProductAttachment\Block\Adminhtml\Widget;

class Button extends \Magento\Widget\Block\Adminhtml\Widget\Chooser
{
    /**
     * ToHtml
     *
     * @return void
     */
    public function _toHtml()
    {
        $element = $this->getElement();
        /** @var $fieldset \Magento\Framework\Data\Form\Element\Fieldset **/
        $fieldset = $element->getForm()->getElement($this->getFieldsetId());
        $chooserId = $this->getUniqId();
        $config = $this->getConfig();

        // add chooser element to fieldset
        $chooser = $fieldset->addField(
            'chooser' . $element->getId(),
            'note',
            ['label' => $config->getLabel() ? $config->getLabel() : '', 'value_class' => 'value2']
        );

        $hidden = $this->_elementFactory->create('hidden', ['data' => $element->getData()]);
        $hidden->setId("{$chooserId}value")->setForm($element->getForm());
        $hiddenHtml = $hidden->getElementHtml();
        $element->setValue('');

        $buttons = $config->getButtons();
        $chooseButton = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setType(
            'button'
        )->setId(
            $chooserId . 'control'
        )->setClass(
            'btn-chooser'
        )->setLabel(
            $buttons['open']
        )->setOnclick(
            $chooserId . '.choose()'
        )->setDisabled(
            $element->getReadonly()
        );
        $chooser->setData('after_element_html', $hiddenHtml . $chooseButton->toHtml());

        // render label and chooser scripts
        $configJson = $this->_jsonEncoder->encode($config->getData());
        return '<script>
            require(["prototype", "mage/adminhtml/wysiwyg/widget"], function(){
            //<![CDATA[
                (function() {
                    var instantiateChooser = function() {
                        window.' . $chooserId . ' = new WysiwygWidget.chooser(
                            "' . $chooserId . '",
                            "' . $this->getSourceUrl() . '",
                            ' . $configJson . '
                        );
                        window.' . $chooserId . '.choose = function () {
                            // Open dialog window with previously loaded dialog content
                            var responseContainerId;
                
                            // Show or hide chooser content if it was already loaded
                            responseContainerId = this.getResponseContainerId();
                
                            // Otherwise load content from server
                            new Ajax.Request(this.chooserUrl, {
                                parameters: {
                                    \'element_value\': this.getElementValue()
                                },
                                onSuccess: function (transport) {
                                    try {
                                        widgetTools.onAjaxSuccess(transport);
                                        this.dialogContent = widgetTools.getDivHtml(
                                            responseContainerId,
                                            transport.responseText
                                        );
                                        this.openDialogWindow(this.dialogContent);
                                    } catch (e) {
                                        alert({
                                            content: e.message
                                        });
                                    }
                                }.bind(this)
                            });
                        }
                    };
                    jQuery(instantiateChooser);
                })();
            //]]>
            });
            </script>
        ';
    }
}
