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
    'Magento_Ui/js/dynamic-rows/dynamic-rows-grid',
    'underscore',
    'prototype'
], function (dynamicRowsGrid, _) {
    'use strict';

    return dynamicRowsGrid.extend({
        _updateData: function (data) {
            this._super();
            _.each(this.elems(), function (record) {
                _.each(record.elems(), function (elem) {
                   if (_.isFunction(elem.checkState)) {
                       elem.checkState();
                   }
                });
            });
        }
    });
});
