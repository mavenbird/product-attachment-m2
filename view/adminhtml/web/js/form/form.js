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

define([
    'underscore',
    'Magento_Ui/js/form/form',
    'uiRegistry'
], function (_, Form, registry) {
    return Form.extend({
        setAdditionalData: function () {
            this._super();
            var generalDataFieldSet = registry.get(this.name + '.general');
            if (typeof generalDataFieldSet !== 'undefined') {
                _.each(generalDataFieldSet.elems(), function (elem) {
                    if (_.isFunction(elem.disabled) && elem.disabled()) {
                        if (elem.dataType === 'multiselect') {
                            this.source.set(elem.dataScope + '_output', null);
                        } else {
                            this.source.set(elem.dataScope, null);
                        }
                    } else if (_.isFunction(elem.elems) && elem.elems()) {
                        _.each(elem.elems(), function (elem) {
                            if (_.isFunction(elem.disabled) && elem.disabled()) {
                                this.source.set(elem.dataScope, null);
                            }
                        }, this);
                    }
                }, this);
            }

            return this;
        }
    });
});
