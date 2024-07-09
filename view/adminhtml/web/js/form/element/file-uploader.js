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
    'Magento_Ui/js/form/element/file-uploader',
    'underscore',
    'uiRegistry',
    'prototype'
], function (fileUploader, _, registry) {
    return fileUploader.extend({
        getFilePreviewType: function (file) {
            if (!_.isUndefined(file.previewUrl)) {
                return 'image';
            }

            return this._super();
        },
        getFileLink: function(file) {
            return file.url;
        },
        getFilePreview: function (file) {
            if (!_.isUndefined(file.previewUrl)) {
                return file.previewUrl;
            }

            return file.url;
        },
        addFile: function (file) {
            var fileNameField = registry.get(this.parentName + '.filename_container.filename'),
                labelField = registry.get(this.parentName + '.label');

            if (typeof fileNameField !== 'undefined' && !fileNameField.value()) {
                fileNameField.value(file.filename);
            }
            if (typeof labelField !== 'undefined' && !labelField.value()) {
                labelField.value(file.filename);
            }
            registry.get(this.parentName + '.filename_container.extension').value(file.file_extension);

            this._super();
        }
    })
});
