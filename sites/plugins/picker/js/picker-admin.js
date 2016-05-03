// Variables
var datetimepicker_currentText = 'Now';
var datetimepicker_closeText = 'Close';
var datetimepicker_timeText = 'Time';
var datetimepicker_hourText = 'Hour';
var datetimepicker_minuteText = 'Minute';
var datetimepicker_dateFormat = 'mm/dd/yy';
var picker_suggest_ajax_url = '/wp-admin/admin-ajax.php';

/**
 * Manage document ready to enable widget controls
 */
jQuery(document).ready(function() {
    // Attach events to picker widgets on page load (only for sidebars)
    picker_manage_locales('body.locale-it-it');
    picker_manage_field_published('.widgets-sortables .widget .published');
    picker_manage_field_number('.widgets-sortables .widget .number');
    picker_manage_field_url('.widgets-sortables .widget .url');
    picker_manage_field_datetime('.widgets-sortables .widget .time_to_publish');
    picker_manage_field_datetime('.widgets-sortables .widget .time_to_expire');
    picker_manage_field_textarea_resizable('.widgets-sortables .widget .textarea_resizable');
    picker_manage_field_id_search('.widgets-sortables .widget .id_search');

    // Check WordPress version
    var body = jQuery('body');
    if (body.is('[class*="version-4-"]')) {
        // Add "on" event for widgets add/update action (WordPress 4.0 or higher)
        jQuery(document).on('widget-added', picker_on_form_update);
        jQuery(document).on('widget-updated', picker_on_form_update);
    }
    else if (body.is('[class*="version-3-9"]')) {
        // Add "on" event for widgets add/update action (WordPress 3.9)
        jQuery(document).on('widget-added', picker_on_form_update);
        jQuery(document).on('widget-updated', picker_on_form_update);
    }
    else {
        // This will fire the widget-save ajax request, just after a widget-save request has completed
        // (if there was no response with the form html). Then re-attach javascript functions to the new DOM elements
        // added by the widget form function simply bind them to the "saved_widget" event (WordPress 3.8 or lower)
        jQuery(document).ajaxComplete(function(event, XMLHttpRequest, ajaxOptions) {
            if (ajaxOptions.data != undefined) {
                // Determine which ajax request is this (we're after "save-widget")
                var request = {}, pairs = ajaxOptions.data.split('&'), i, split, widget;

                for (i in pairs) {
                    split = pairs[i].split('=');
                    request[decodeURIComponent(split[0])] = decodeURIComponent(split[1]);
                }

                // Only proceed if this was a widget-save request
                if (request.action && (request.action === 'save-widget')) {
                    // Locate the widget block
                    widget = jQuery('input.widget-id[value="' + request['widget-id'] + '"]').parents('.widget');

                    // Trigger manual save, if this was the save request
                    // and if we didn't get the form html response (the wp bug)
                    if (!XMLHttpRequest.responseText) wpWidgets.save(widget, 0, 1, 0);

                    // We got an response, this could be either our request above,
                    // or a correct widget-save call, so fire an event on which we can hook our js
                    else jQuery(document).trigger('saved_widget', widget);
                }
            }
        });
    }

    /**
     * Bind save widget event
     */
    jQuery(document).bind('saved_widget', function(event, widget) {
        picker_on_form_update(event, jQuery(widget));
    });
});


/**
 * Manage widget form after an add/update action
 *
 * @param {jQuery.Event} event
 * @param {jQuery} widget
 */
function picker_on_form_update(event, widget) {
    // Re-attach events to picker widgets (only for sidebars) after an add/update action
    if ('widget-added' === event.type || 'widget-updated' === event.type || 'saved_widget' === event.type) {
        picker_manage_locales('body.locale-it-it');
        picker_manage_field_published(widget.find('input.published'));
        picker_manage_field_number(widget.find('input.number'));
        picker_manage_field_url(widget.find('input.url'));
        picker_manage_field_datetime("#" + widget.find('input.time_to_publish').attr('id'));
        picker_manage_field_datetime("#" + widget.find('input.time_to_expire').attr('id'));
        picker_manage_field_textarea_resizable(widget.find('textarea.textarea_resizable'));
        picker_manage_field_id_search("#" + widget.find('input.id_search').attr('id'));
    }
}

/**
 * Manage locales
 *
 * @param selector
 */
function picker_manage_locales(selector) {
    if (jQuery(selector).length != 0) {
        datetimepicker_currentText = 'Adesso';
        datetimepicker_closeText = 'Chiudi';
        datetimepicker_timeText = 'Orario';
        datetimepicker_hourText = 'Ore';
        datetimepicker_minuteText = 'Minuti';
        datetimepicker_dateFormat = 'dd/mm/yy';
    }
    else {
        // Use defaults
    }
}

/**
 * Check and highlight all "published" items, then add click event
 *
 * @param selector
 */
function picker_manage_field_published(selector) {
    jQuery(selector).each(function () {
        picker_check_published(jQuery(this));
    }).click(function() {
        picker_check_published(jQuery(this));
    });
}

/**
 * Check number format
 *
 * @param selector
 */
function picker_manage_field_number(selector) {
    jQuery(selector).blur(function () {
        picker_check_number(jQuery(this));
    });
}

/**
 * Check url format
 *
 * @param selector
 */
function picker_manage_field_url(selector) {
    jQuery(selector).blur(function () {
        picker_check_url(jQuery(this));
    });
}

/**
 * Enable on "times" fields datetime picker plugin
 *
 * @param selector
 * @param widget
 */
function picker_manage_field_datetime(selector, widget) {
    try {
        // Use datetimepicker
        jQuery(selector).datetimepicker({
            currentText: datetimepicker_currentText,
            closeText: datetimepicker_closeText,
            timeText: datetimepicker_timeText,
            hourText: datetimepicker_hourText,
            minuteText: datetimepicker_minuteText,
            dateFormat: datetimepicker_dateFormat,
            timeFormat: 'HH:mm',
            stepHour: 1,
            stepMinute: 5
        });
    }
    catch (e) {
        // If datetimepicker doesn't works, check datetime fields format
        jQuery(selector).blur(function () {
            picker_check_datetime(jQuery(this));
        });
    }
}

/**
 * Add auto resize function to resizable textarea
 *
 * @param selector
 */
function picker_manage_field_textarea_resizable(selector) {
    jQuery(selector).each(function () {
        jQuery(this).autoResize({
            animate: {
                enabled: true,
                duration: 'normal'
            },
            maxHeight: '150px'
        });
    });
}

/**
 * Add auto complete function to search field
 */
function picker_manage_field_id_search(selector) {
    jQuery(selector).each(function () {
        var search_field = "#" + (jQuery(this).attr('id'));
        var search_value = '';

        jQuery(this).autocomplete({
            delay: 0,
            minLength: 3,
            source: function (req, response) {
                jQuery.getJSON(picker_suggest_ajax_url + '?callback=?&action=picker_suggest_lookup&q=' + jQuery(search_field).val(), req, response);
            },
            close: function (event, ui) {
                // Change ID value with saved one
                jQuery(this).val(search_value);
            },
            select: function (event, ui) {
                // Save ID value with suggested one
                search_value = ui.item.id;
            }
        }).blur(function () {
            // Check number format
            picker_check_number(jQuery(this));
        });
    });
}

/**
 * Wrap widget container with a coloured class for checked items
 *
 * @param field
 */
function picker_check_published(field) {
    // Get parent widget container
    var _widget = field.parents('.widget');

    // Check field
    if (field.is(':checked')) {
        // Field checked
        _widget.addClass('published').removeClass('off').addClass('on');
    }
    else {
        // Field not checked
        _widget.addClass('published').removeClass('on').addClass('off').addClass('published');
    }
}

/**
 * Check number format
 *
 * @param field
 * @returns {boolean}
 */
function picker_check_number(field) {
    var _error_message = "";

    // Check number format
    if (field.val() != '') {
        if (!jQuery.isNumeric(field.val())) {
            _error_message = "Invalid number format";
        }
    }

    // Show errors
    if (_error_message != "") {
        alert(_error_message);
        field.focus();
        return false;
    }
    return true;
}

/**
 * Check url format
 *
 * @param field
 * @returns {boolean}
 */
function picker_check_url(field) {
    var _error_message = "";

    // Check url format
    if (field.val() != '') {
        if (!/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(field.val())){
            _error_message = "Invalid url format";
        }
    }

    // Show errors
    if (_error_message != "") {
        alert(_error_message);
        field.focus();
        return false;
    }
    return true;
}

/**
 * Check date format
 *
 * @param field
 * @returns {boolean}
 */
function picker_check_date(field) {
    var _min_year = 1902;
    var _max_year = (new Date()).getFullYear();
    var _error_message = "";

    // Regular expression to match required date format
    re = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/;
    if (field != '') {
        if (regs = field.match(re)) {
            if (regs[1] < 1 || regs[1] > 31) {
                _error_message = "Invalid value for day: " + regs[1];
            }
            else if (regs[2] < 1 || regs[2] > 12) {
                _error_message = "Invalid value for month: " + regs[2];
            }
            else if (regs[3] < _min_year || regs[3] > _max_year) {
                _error_message = "Invalid value for year: " + regs[3] + " - must be between " + _min_year + " and " + _max_year;
            }
        }
        else {
            _error_message = "Invalid date format ('gg/mm/aaaa HH:mi')";
        }
    }

    // Show errors
    if (_error_message != "") {
        alert(_error_message);
        return false;
    }
    return true;
}

/**
 * Check time format
 *
 * @param field
 * @returns {boolean}
 */
function picker_check_time(field) {
    var _error_message = "";

    // Regular expression to match required time format
    re = /^(\d{1,2}):(\d{2})(:00)?([ap]m)?$/;
    if (field != '') {
        if (regs = field.match(re)) {
            if (regs[4]) {
                // 12-hour time format with am/pm
                if (regs[1] < 1 || regs[1] > 12) {
                    _error_message = "Invalid value for hours: " + regs[1];
                }
            }
            else {
                // 24-hour time format
                if (regs[1] > 23) {
                    _error_message = "Invalid value for hours: " + regs[1];
                }
            }
            if (!_error_message && regs[2] > 59) {
                _error_message = "Invalid value for minutes: " + regs[2];
            }
        }
        else {
            _error_message = "Invalid time format ('gg/mm/aaaa HH:mi')";
        }
    }

    // Show errors
    if (_error_message != "") {
        alert(_error_message);
        return false;
    }
    return true;
}

/**
 *Check datetime format
 *
 * @param field
 */
function picker_check_datetime(field) {
    // Get field value
    var _value = field.attr('value');

    // Check datetime
    var _date = _value.split(' ')[0];
    var _time = _value.split(' ')[1];
    if (!picker_check_date(_date) || !picker_check_time(_time)) {
        // Empty field
        // field.attr('value', '')

        // Focus field
        field.focus();
    }
}