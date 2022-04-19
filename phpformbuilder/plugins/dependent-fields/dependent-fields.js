/*=========================================================
            phpformbuilder dependent fields - vanilla JS plugin
            Author : Gilles Migliori
            Version : 2.0
=========================================================*/

(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define([], factory('DependentFields'));
    } else if (typeof exports === 'object') {
        module.exports = factory('DependentFields');
    } else {
        root['DependentFields'] = factory('DependentFields');
    }
}(this, function () {

    'use strict';

    /**
     * Plugin Object
     * @param {String} selector
     * @constructor
     */
    function DependentFields (selector) {
        this.selector = selector;
        this.options = {
            wrapperClass: 'hidden-wrapper',
            showClass: 'on',
            hideClass: 'off',
            parentData: 'data-parent',
            showValuesData: 'data-show-values',
            inverseData: 'data-inverse'
        };
        this.init();
    }

    /**
     * DependentFields prototype
     * @public
     * @constructor
     */
    DependentFields.prototype = {
        init: function () {
            // find all matching DOM elements.
            // makes `.selectors` object available to instance.
            this.elements = document.querySelectorAll(this.selector);

            for (let $el of this.elements) {
                const parentFieldClean = $el.getAttribute(this.options.parentData).replace('[]', '');

                let parentFieldFull = parentFieldClean,
                    showValues = $el.getAttribute(this.options.showValuesData),
                    inverseData = $el.getAttribute(this.options.inverseData);

                if (document.querySelector('*[name="' + parentFieldClean + '[]"]') !== null) {
                    parentFieldFull = parentFieldClean + '[]';
                }

                // don't split doubled-escaped commas
                // https://codepen.io/migli/pen/qPOvZM/
                showValues = this._getShowValues(showValues);

                // convert to boolean
                inverseData = inverseData == 'true' || inverseData > 0;

                // enable LCSwitch events
                this._enableLCSwitchEvents();

                // enable all others events
                this._enableEventsListeners($el, parentFieldFull, showValues, inverseData);

                const arr = [];
                let $current = document.querySelectorAll('*[name="' + parentFieldFull + '"]');
                $current.forEach(item => {
                    if (arr.indexOf(parentFieldClean) === -1) {
                        if (this._getNextSibling(item, '.iCheck-helper') !== null && item.checked === true) {
                            this._triggerEvent(item, 'ifChanged');
                            arr.push(parentFieldClean);
                        } else if (this._getNextSibling(item.parentNode.parentNode, '.bootstrap-select') !== null && item.value !== '') {
                            // if bootstrap-select
                            this._triggerEvent(item, 'changed.bs.select');
                            arr.push(parentFieldClean);
                        } else {
                            this._triggerEvent(item, 'change');
                            arr.push(parentFieldClean);
                        }
                    }
                });
            }

            if (document.querySelector('button[type="reset"]')) {
                document.querySelector('button[type="reset"]').addEventListener('click', () => {
                    this.init();
                });
            }
        },

        /**
         * Enable events on select, radio, checkboxes
         *
         * @param HTMLElement $targetHiddenWrapper
         * @param String parentFieldFull
         * @param Boolean inverseData
         */
        _enableEventsListeners: function ($targetHiddenWrapper, parentFieldFull, showValues, inverseData) {
            // ifChanged = iCheck event, changed.bs.select = bootstrap-select event
            ['change', 'ifChanged', 'changed.bs.select'].forEach(e => {
                document.body.addEventListener(e, event => {
                    if (event.target.name === parentFieldFull) {
                        const isRadio = event.target.type === 'radio',
                            isCheckbox = event.target.type === 'checkbox';

                        const value = this._getValue(parentFieldFull, event, showValues, isRadio, isCheckbox, inverseData);

                        // console.log(parentFieldFull + ' value = ' + value);

                        if (showValues.indexOf(value) > -1) {
                            // if value found in showValues
                            if (inverseData !== true) {
                                // Show
                                this._showElement($targetHiddenWrapper);
                            }
                        } else {
                            // if value NOT found in showValues
                            if (inverseData === true && value !== undefined) {
                                // Show
                                this._showElement($targetHiddenWrapper);
                            } else {
                                // Hide
                                this._hideElement($targetHiddenWrapper);
                            }
                        }
                    }
                });
            });
        },

        /**
         * enable & delegate lcswitch events
         *
         */
        _enableLCSwitchEvents: function () {
            if (document.querySelector('.lcswitch') !== null) {
                document.body.addEventListener('lcs-statuschange', event => {
                    if (event.target.className === 'lcswitch') {
                        this._triggerEvent(document.getElementById(event.target.id), 'change');
                    }
                })
            }
        },

        /**
         *
         * If inverseData: value is the first non-showValues found,
         * or undefined if none
         * else, value is the first showValues found, or undefined if none
         *
         * @param {String} parentFieldFull
         * @param {Event} event
         * @param {Array} showValues
         * @param {Boolean} isRadio
         * @param {Boolean} isCheckbox
         * @param {Boolean} inverseData
         *
         * @returns {String|Array} value
         */
        _getValue: function (parentFieldFull, event, showValues, isRadio, isCheckbox, inverseData) {
            let value = undefined;
            if (!inverseData) {
                if (isRadio) {
                    // undefined if none checked. Else, checked value.
                    let checkedEl = document.querySelector('input[name="' + parentFieldFull + '"]:checked');
                    if (checkedEl) {
                        value = document.querySelector('input[name="' + parentFieldFull + '"]:checked').value;
                    }
                } else if (isCheckbox) {
                    value = [];
                    let $checked = document.querySelectorAll('input[name="' + parentFieldFull + '"]:checked');
                    if ($checked.length > 0) {
                        $checked.forEach(item => {
                            value.push(item.value);
                        });
                    }
                } else {
                    value = event.target.value;
                }

                // if checkbox or multiple select, we loop into selected values to find one corresponding to showValues
                if ((typeof value == 'object') && (value !== null)) {
                    for (let i = value.length - 1; i >= 0; i--) {
                        if (showValues.indexOf(value[i]) > -1) {
                            value = value[i];
                            break;
                        }
                    }
                }
            } else {
                // register any value non-matching with showValues
                if (isRadio) {
                    // if checked value is not in showValues.
                    if (showValues.indexOf(document.querySelector('input[name="' + parentFieldFull + '"]:checked').value) < 0) {
                        value = document.querySelector('input[name="' + parentFieldFull + '"]:checked').value;
                    }
                } else if (isCheckbox) {
                    value = [];
                    let $checked = document.querySelectorAll('input[name="' + parentFieldFull + '"]:checked');
                    $checked.forEach(item => {
                        if (showValues.indexOf(item.value) < 0) {
                            value.push(item.value);
                        }
                    });
                    if (value.length < 1) {
                        value = undefined;
                    }
                } else {
                    if (showValues.indexOf(event.target.value) < 0) {
                        value = event.target.value;
                    }
                }

                // if checkbox or multiple select, keep only first value as string
                if (typeof value == 'object') {
                    value = value[0];
                }
            }

            return value;
        },


        /**
         * Parse values string
         *
         * @param {String} values
         * @returns {Array}
         */
        _getShowValues: function (values) {
            values = values.replace('\\,', '{comma}');
            values = values.split(/,\s*/);
            for (let i = 0; i < values.length; i++) {
                values[i] = values[i].replace('{comma}', ',');
            }

            return values;
        },


        /**
         * Get the next sibling that matches selector
         *
         * @param {Object} elem the HTMLElement
         * @param {String} selector the CSS selector
         * @returns {Array | null}
         */
        _getNextSibling: function (elem, selector) {

            // Get the next sibling element
            let sibling = elem.nextElementSibling;

            // If the sibling matches our selector, use it
            // If not, jump to the next sibling and continue the loop
            while (sibling) {
                if (sibling.matches(selector)) return sibling;
                sibling = sibling.nextElementSibling
            }

            return null;
        },

        _hideElement: function (elem) {
            elem.classList.remove(this.options.showClass);
            elem.classList.add(this.options.hideClass);
            for (const child of elem.children) {
                if (!child.classList.contains('hidden-wrapper')) {
                    child.disabled = true;
                }
            }
        },

        _showElement: function (elem) {
            elem.classList.remove(this.options.hideClass);
            elem.classList.add(this.options.showClass);
            for (const child of elem.children) {
                if (!child.classList.contains('hidden-wrapper')) {
                    child.disabled = false;
                }
            }
        },

        _triggerEvent: function ($target, event) {
            const evt = new Event(event, { bubbles: true });
            $target.dispatchEvent(evt);
        }
    };

    return DependentFields;
}));
