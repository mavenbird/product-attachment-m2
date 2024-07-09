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

define(
    [
        "Magento_Ui/js/form/element/single-checkbox",
        "uiRegistry"
    ],
    function (Checkbox, registry) {
        return Checkbox.extend({
            defaults: {
                serviceTemplate: 'ui/form/element/helper/service'
            },
            realHasService: true,

            initObservable: function () {
                this._super();

                this.observe({realHasService : this.realHasService});
                registry.async(this.provider)(function (provider) {
                    var useDefaults = provider.get(this.dataScope + '_use_defaults');
                    if (typeof useDefaults !== 'undefined' && useDefaults !== '') {
                        if (registry.get(this.provider).get(this.dataScope + '_use_defaults')) {
                            this.disabled(true);
                        }
                    } else {
                        this.realHasService(false);
                    }
                }.bind(this));

                return this;
            },
            checkState: function () {
                registry.async(this.provider)(function (provider) {
                    var useDefaults = provider.get(this.dataScope + '_use_defaults');
                    if (typeof useDefaults !== 'undefined' && useDefaults !== '') {
                        if (registry.get(this.provider).get(this.dataScope + '_use_defaults')) {
                            if (!this.disabled()) {
                                this.isUseDefault(true);
                            }
                        } else {
                            if (this.disabled()) {
                                this.isUseDefault(false);
                            }
                        }
                        this.realHasService(true);
                    } else {
                        this.realHasService(false);
                    }
                }.bind(this));
            },
            toggleUseDefault: function (state) {
                this._super();
                registry.async(this.provider)(function (provider) {
                    var useDefaults = provider.get(this.dataScope + '_use_defaults');
                    if (typeof useDefaults !== 'undefined' && useDefaults !== '') {
                        provider.set(this.dataScope + '_use_defaults', state);
                    }
                }.bind(this));
            }
        });
    }
);
