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
    'Magento_Ui/js/dynamic-rows/dynamic-rows',
    'uiRegistry',
    'underscore'
], function (Dynamicrows, registry, _) {
    return Dynamicrows.extend({
        saveLinks: function () {
            registry.async('index = files')(function (filesContainer) {
                var data = _.clone(filesContainer.cacheGridData);
                _.each(this.recordData(), function (record) {
                    if (!_.isUndefined(record.linkdata)) {
                        data[data.length] = record.linkdata;
                    }
                }.bind(this));
                this.recordData([]);
                this.reload();
                this.showSpinner(false);
                filesContainer.insertData(data);
            }.bind(this));
        }
    });
});