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
    'Magento_Ui/js/form/element/abstract',
    'jquery',
    'uiRegistry',
    'Magento_Ui/js/lib/spinner'
], function (Abstract, $, registry, loader) {
    'use strict';

    return Abstract.extend({
        validate: function () {
            var validateStatus = this._super();
            if (this.hasChanged() && this.value() !== '') {
                if (validateStatus.valid) {
                    var formLoader = loader.get(this.ns + '.' + this.ns),
                        self = this,
                        validateUrl = $.Deferred();
                    formLoader.show();

                    $.ajax({
                        url: this.validationUrl,
                        data: {'url': this.value()},
                        dataType: 'json',
                        success: function (result) {
                            formLoader.hide();
                            if (result.status === 'success') {
                                var fileNameField = registry.get(self.parentName + '.filename_container.filename'),
                                    labelField = registry.get(self.parentName + '.label');

                                if (typeof fileNameField !== 'undefined' && !fileNameField.value()) {
                                    fileNameField.value(result.file.filename);
                                }
                                if (typeof labelField !== 'undefined' && !labelField.value()) {
                                    labelField.value(result.file.filename);
                                }
                                registry.get(self.parentName + '.filename_container.extension').value(result.file.file_extension);
                                validateUrl.resolve({
                                    valid: true,
                                    target: self
                                });
                            } else {
                                self.error(result.message);
                                self.bubble('error', result.message);
                                self.source.set('params.invalid', true);
                                validateUrl.resolve({
                                    valid: false,
                                    target: self
                                });
                            }
                        },
                        complete: function () {
                            formLoader.hide();
                            validateUrl.resolve({
                                valid: false,
                                target: self
                            });
                        }
                    });

                    return validateUrl.promise();
                }
            }

            return validateStatus;
        }
    });
});
