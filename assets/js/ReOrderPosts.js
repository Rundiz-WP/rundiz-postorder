

function ajaxReNumberAll() {
    $ = jQuery.noConflict();

    confirmed_val = confirm(confirm_reorder_all);

    if (confirmed_val === true) {
        rdpostorder_is_updating = true;
        disablePostSortable();
        $('.form-result-placeholder').html('');

        formData = {
            'action': 'RdPostOrderReNumberAll',
            'security': ajaxnonce,
            '_wp_http_referer': $('input[name="_wp_http_referer"]').val(),
            'paged': ($.query.get('paged') ? $.query.get('paged') : 1),
            'confirmed_renumber': true,
        }

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // displaying result to the page.
                displayNoticeElement(response, response, 'notice-error');

                if (typeof(response) !== 'undefined') {
                    if (typeof(response.save_result) !== 'undefined' && response.save_result === true) {
                        if (typeof(response.list_table_updated) !== 'undefined') {
                            list_table_html = $(response.list_table_updated).filter('.post-reorder-table')[0].outerHTML;
                            $('.post-reorder-table').replaceWith(list_table_html);
                            reActiveTableToggleRow();
                            delete list_table_html;
                            // must re-call all functions based on jQuery(function() {...}); below.
                            enablePostSortable();
                            listeningEscKeyPress();
                            listeningCurrentPageInput();
                            listeningButtonActionClicked();
                            listeningActionSelectorSubmitted();
                        }
                    }
                }

                // re-activate sortable
                rdpostorder_is_updating = false;
                enablePostSortable();
            },
            error: function(jqXHR, status, error) {
                errResponse = jqXHR.responseJSON;
                errResponseText = jqXHR.responseText;

                displayNoticeElement(errResponse, errResponseText, 'notice-error');

                // re-activate sortable
                rdpostorder_is_updating = false;
                enablePostSortable();
                // clear
                delete errResponse;
                delete errResponseText;
            }
        });

        delete formData;
    }

    return false;
}// ajaxReNumberAll


function ajaxReOrder(move_to, postID) {
    $ = jQuery.noConflict();

    if (typeof(move_to) == 'undefined') {
        move_to = 'up';
    }
    // the menu_order will be get it directly from list table. that is the most up to date (updated on sorted).

    rdpostorder_is_updating = true;
    disablePostSortable();
    $('.form-result-placeholder').html('');

    formData = {
        'action': 'RdPostOrderReOrderPost',
        'security': ajaxnonce,
        '_wp_http_referer': $('input[name="_wp_http_referer"]').val(),
        'move_to': move_to,
        'postID': postID,
        'menu_order': $('#menu_order_'+postID).val(),
        'paged': ($.query.get('paged') ? $.query.get('paged') : 1),
    }

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            // displaying result to the page.
            displayNoticeElement(response, response, 'notice-error');

            if (typeof(response) !== 'undefined') {
                if (typeof(response.save_result) !== 'undefined' && response.save_result === true) {
                    if (typeof(response.list_table_updated) !== 'undefined') {
                        list_table_html = $(response.list_table_updated).filter('.post-reorder-table')[0].outerHTML;
                        $('.post-reorder-table').replaceWith(list_table_html);
                        reActiveTableToggleRow();
                        delete list_table_html;
                        // must re-call all functions based on jQuery(function() {...}); below.
                        enablePostSortable();
                        listeningEscKeyPress();
                        listeningCurrentPageInput();
                        listeningButtonActionClicked();
                        listeningActionSelectorSubmitted();
                    }
                }
            }

            // re-activate sortable
            rdpostorder_is_updating = false;
            enablePostSortable();
        },
        error: function(jqXHR, status, error) {
            errResponse = jqXHR.responseJSON;
            errResponseText = jqXHR.responseText;

            displayNoticeElement(errResponse, errResponseText, 'notice-error');

            // re-activate sortable
            rdpostorder_is_updating = false;
            enablePostSortable();
            // clear
            delete errResponse;
            delete errResponseText;
        }
    });

    delete formData;

    return false;
}// ajaxReOrder


function ajaxResetAllPostsOrder() {
    $ = jQuery.noConflict();

    confirmed_val = confirm(confirm_reorder_all);

    if (confirmed_val === true) {
        rdpostorder_is_updating = true;
        disablePostSortable();
        $('.form-result-placeholder').html('');

        formData = {
            'action': 'RdPostOrderResetAllPostsOrder',
            'security': ajaxnonce,
            '_wp_http_referer': $('input[name="_wp_http_referer"]').val(),
            'paged': ($.query.get('paged') ? $.query.get('paged') : 1),
            'confirmed_reset': true,
        }

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // displaying result to the page.
                displayNoticeElement(response, response, 'notice-error');

                if (typeof(response) !== 'undefined') {
                    if (typeof(response.save_result) !== 'undefined' && response.save_result === true) {
                        if (typeof(response.list_table_updated) !== 'undefined') {
                            list_table_html = $(response.list_table_updated).filter('.post-reorder-table')[0].outerHTML;
                            $('.post-reorder-table').replaceWith(list_table_html);
                            reActiveTableToggleRow();
                            delete list_table_html;
                            // must re-call all functions based on jQuery(function() {...}); below.
                            enablePostSortable();
                            listeningEscKeyPress();
                            listeningCurrentPageInput();
                            listeningButtonActionClicked();
                            listeningActionSelectorSubmitted();
                        }
                    }
                }

                // re-activate sortable
                rdpostorder_is_updating = false;
                enablePostSortable();
            },
            error: function(jqXHR, status, error) {
                errResponse = jqXHR.responseJSON;
                errResponseText = jqXHR.responseText;

                displayNoticeElement(errResponse, errResponseText, 'notice-error');

                // re-activate sortable
                rdpostorder_is_updating = false;
                enablePostSortable();
                // clear
                delete errResponse;
                delete errResponseText;
            }
        });

        delete formData;
    }

    return false;
}// ajaxResetAllPostsOrder


function ajaxSaveAllNumbersChanged() {
    $ = jQuery.noConflict();

    confirmed_val = confirm(confirm_txt);

    if (confirmed_val === true) {
        rdpostorder_is_updating = true;
        disablePostSortable();
        $('.form-result-placeholder').html('');

        formData = $('.menu_order_value').serialize();
        additionalFormData = {
            'action': 'RdPostOrderSaveAllNumbersChanged',
            'security': ajaxnonce,
            '_wp_http_referer': $('input[name="_wp_http_referer"]').val(),
            'paged': ($.query.get('paged') ? $.query.get('paged') : 1),
        }
        formData += '&' + $.param(additionalFormData);
        delete additionalFormData;

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // displaying result to the page.
                displayNoticeElement(response, response, 'notice-error');

                if (typeof(response) !== 'undefined') {
                    if (typeof(response.save_result) !== 'undefined' && response.save_result === true) {
                        if (typeof(response.list_table_updated) !== 'undefined') {
                            list_table_html = $(response.list_table_updated).filter('.post-reorder-table')[0].outerHTML;
                            $('.post-reorder-table').replaceWith(list_table_html);
                            reActiveTableToggleRow();
                            delete list_table_html;
                            // must re-call all functions based on jQuery(function() {...}); below.
                            enablePostSortable();
                            listeningEscKeyPress();
                            listeningCurrentPageInput();
                            listeningButtonActionClicked();
                            listeningActionSelectorSubmitted();
                        }
                    }
                }

                // re-activate sortable
                rdpostorder_is_updating = false;
                enablePostSortable();
            },
            error: function(jqXHR, status, error) {
                errResponse = jqXHR.responseJSON;
                errResponseText = jqXHR.responseText;

                displayNoticeElement(errResponse, errResponseText, 'notice-error');

                // re-activate sortable
                rdpostorder_is_updating = false;
                enablePostSortable();
                // clear
                delete errResponse;
                delete errResponseText;
            }
        });
    }

    return false;
}// ajaxSaveAllNumbersChanged


function ajaxUpdateSortItems(sorted_items_serialize_values, max_menu_order) {
    $ = jQuery.noConflict();

    rdpostorder_is_updating = true;
    disablePostSortable();
    $('.form-result-placeholder').html('');

    formData = sorted_items_serialize_values + '&' + $('.menu_order_value').serialize();
    additionalFormData = {
        'action': 'RdPostOrderReOrderPosts',
        'security': ajaxnonce,
        '_wp_http_referer': $('input[name="_wp_http_referer"]').val(),
        'max_menu_order': max_menu_order,
    }
    formData += '&' + $.param(additionalFormData);
    delete additionalFormData;

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            // displaying result to the page.
            displayNoticeElement(response, response, 'notice-error');

            if (typeof(response) !== 'undefined') {
                if (typeof(response.save_result) !== 'undefined' && response.save_result === true) {
                    if (typeof(response.re_ordered_data) !== 'undefined') {
                        // loop get data from saved and set into html spans and inputs.
                        $.each(response.re_ordered_data, function() {
                            $('#menu_order_'+this.ID).val(this.menu_order);
                        });
                    }
                }
            }

            // re-activate sortable
            rdpostorder_is_updating = false;
            enablePostSortable();
        },
        error: function(jqXHR, status, error) {
            errResponse = jqXHR.responseJSON;
            errResponseText = jqXHR.responseText;

            displayNoticeElement(errResponse, errResponseText, 'notice-error');

            // re-activate sortable
            rdpostorder_is_updating = false;
            enablePostSortable();
            // clear
            delete errResponse;
            delete errResponseText;
        }
    });

    delete formData;
}// ajaxUpdateSortItems


function disablePostSortable() {
    $ = jQuery.noConflict();

    if (!$('.post-reorder-table tbody').hasClass('ui-sortable')) {
        console.log('This list table is currently not activate for sortable.');
        return false;
    }

    $('.post-reorder-table tbody').sortable('destroy');
}// disablePostSortable


function displayNoticeElement(responseJSON, responseText, default_notice_class) {
    $ = jQuery.noConflict();

    if (
        typeof(default_notice_class) === 'undefined' || 
        (typeof(default_notice_class) !== 'undefined' && (default_notice_class === '' || default_notice_class === null))
    ) {
        default_notice_class = 'notice-error';
    }

    if (typeof(responseJSON) !== 'undefined' && typeof(responseJSON) === 'object' && typeof(responseJSON.form_result_class) !== 'undefined' && typeof(responseJSON.form_result_msg) !== 'undefined') {
        if (typeof(responseJSON.form_result_class) === 'undefined') {
            form_result_class = default_notice_class;
        } else {
            form_result_class = responseJSON.form_result_class;
        }
        form_result_html = getNoticeElement(form_result_class, responseJSON.form_result_msg);
        $('.form-result-placeholder').html(form_result_html);
        delete form_result_class;
        delete form_result_html;
    } else if (typeof(responseText) !== 'undefined' && typeof(responseText) === 'string') {
        if (responseText === '-1') {
            form_result_html = getNoticeElement(default_notice_class, ajaxnonce_error_message);
            $('.form-result-placeholder').html(form_result_html);
            delete form_result_html;
        } else if (responseText !== '' && responseText !== null) {
            form_result_html = getNoticeElement(default_notice_class, responseText);
            $('.form-result-placeholder').html(form_result_html);
            delete form_result_html;
        }
    }

    delete default_notice_class;

    // move to top to see form result and re-activate alert dismissable.
    $('html, body').animate({scrollTop: 0}, 'fast');
    reActiveDismissable();
}// displayNoticeElement


function enablePostSortable() {
    $ = jQuery.noConflict();

    if ($('.post-reorder-table tbody').hasClass('ui-sortable')) {
        console.log('The list table sortable is already activate.');
        return true;
    }

    $('.post-reorder-table tbody').sortable({
        handle: '.reorder-handle',
        placeholder: 'ui-placeholder',
        revert: true,
        start: function(event, ui) {
            // fixed height for table row.
            ui.placeholder.height(ui.item.height());
            // colspan the table cells for placeholder. this is for nice rendering in mobile or small screen.
            ui.placeholder.html('<td class="check-column"></td><td class="column-primary" colspan="6"></td>');
        },
        update: function(event, ui) {
            // on stopped sorting and position has changed.
            // get sorted items serialize values.
            sorted_items_serialize_values = $('.post-reorder-table tbody').sortable('serialize');
            // get max value of menu_order
            max_menu_order = -Infinity;
            $('.menu_order_value').each(function () {
                max_menu_order = Math.max(max_menu_order, parseFloat(this.value));
            });

            ajaxUpdateSortItems(sorted_items_serialize_values, max_menu_order);

            delete max_menu_order;
            delete sorted_items_serialize_values;
        }
    });
}// enablePostSortable


function getNoticeElement(notice_class, notice_message) {
	return '<div class="'+notice_class+' notice is-dismissible">'
		+'<p><strong>'+notice_message+'</strong></p>'
		+'<button type="button" class="notice-dismiss"><span class="screen-reader-text">'+dismiss_notice_message+'</span></button>'
		+'</div>';
}// getNoticeElement


function listeningActionSelectorSubmitted() {
    $ = jQuery.noConflict();

    // remove on event listener
    $('#re-order-posts-form').off('submit');

    $('#re-order-posts-form').on('submit', function(event) {
        console.log('The form submitted');

        delete action_selector;
        action_selector_top = $('#bulk-action-selector-top').val();
        action_selector_bottom = $('#bulk-action-selector-bottom').val();

        if (action_selector_top != '-1') {
            action_selector = action_selector_top;
        } else if (action_selector_bottom != '-1') {
            action_selector = action_selector_bottom;
        }

        delete action_selector_bottom;
        delete action_selector_top;

        if (typeof(action_selector) !== 'undefined') {
            event.preventDefault();
            console.log('Prevented default event.');
            console.log('Action selected: '+action_selector);
            if (action_selector == 'renumber_all') {
                return ajaxReNumberAll();
            } else if (action_selector == 'reset_all') {
                return ajaxResetAllPostsOrder();
            } else if (action_selector == 'save_all_numbers_changed') {
                return ajaxSaveAllNumbersChanged();
            }
        }
    });
}// listeningActionSelectorSubmitted


/**
 * Listening on button action clicked and modify form method.
 * 
 * @returns {undefined}
 */
function listeningButtonActionClicked() {
    $ = jQuery.noConflict();

    // remove on event listener
    $('.button.action').off('click');

    $('.button.action').on('click', function(event) {
        console.log('Button action were clicked');

        delete action_selector;
        action_selector_top = $('#bulk-action-selector-top').val();
        action_selector_bottom = $('#bulk-action-selector-bottom').val();

        if (action_selector_top != '-1') {
            action_selector = action_selector_top;
        } else if (action_selector_bottom != '-1') {
            action_selector = action_selector_bottom;
        }

        delete action_selector_bottom;
        delete action_selector_top;

        // change form method to post.
        $('#re-order-posts-form').attr('method', 'post');

        if (typeof(action_selector) === 'undefined') {
            // user select nothing.
            $('#re-order-posts-form').attr('method', 'get');
        } else if (typeof(action_selector) !== 'undefined' && action_selector == '-1') {
            // user select nothing.
            $('#re-order-posts-form').attr('method', 'get');
        }
    });
}// listeningButtonActionClicked


/**
 * Listening key press on current page input.<br>
 * This will be reset all select box action to nothing because it is going to next/previous page, not submit action.
 * 
 * @returns {undefined}
 */
function listeningCurrentPageInput() {
    $ = jQuery.noConflict();

    // remove on event listener
    $('#current-page-selector').off('keyup keypress');

    $('#current-page-selector').on('keyup keypress', function(event) {
        if (event.keyCode === 13 || event.which === 13) {
            console.log('The current page input has entered key press. Reset all action select boxes to nothing because this is going to next page, not submit action.');
            $('#bulk-action-selector-top').val('-1');
            $('#bulk-action-selector-bottom').val('-1');
        }
    });
}// listeningCurrentPageInput


function listeningEscKeyPress() {
    jQuery(document).keyup(function(e) {
        if (e.which === 27 || e.keyCode === 27) {
            // esc key press
            // cancel sortable.
            jQuery('.post-reorder-table tbody')
                .find('.post-item-row')
                .css({
                    'display': '',
                    'height': '',
                    'left': '',
                    'position': '',
                    'right': '',
                    'top': '',
                    'width': '',
                    'z-index': ''
                });
            jQuery('.post-reorder-table tbody')
                .find('.ui-placeholder')
                .remove();
            jQuery('.post-reorder-table tbody')
                .find('.ui-sortable-helper')
                .removeClass('ui-sortable-helper');
            if (jQuery('.post-reorder-table tbody').hasClass('ui-sortable')) {
                jQuery('.post-reorder-table tbody')
                    .sortable('destroy')
                    .trigger('mouseup');
            }
            enablePostSortable();
            // unable to cancel with `.sortable('cancel')`. the item will be removed. see more at https://bugs.jqueryui.com/ticket/15076#ticket
        }
    });
}// listeningEscKeyPress


function reActiveDismissable() {
	jQuery('.notice.is-dismissible').on('click', '.notice-dismiss', function(event){
		jQuery(this).closest('.notice').remove();
	});
}// reActiveDismissable


function reActiveTableToggleRow() {
    $ = jQuery.noConflict();

    // copy from wp-admin/js/common.js
    $('tbody').on('click', '.toggle-row', function() {
        $(this).closest('tr').toggleClass('is-expanded');
    });
}// reActiveTableToggleRow


var rdpostorder_is_updating = false;


jQuery(function($) {
    // post sortable
    enablePostSortable();

    listeningEscKeyPress();

    listeningCurrentPageInput();
    listeningButtonActionClicked();
    listeningActionSelectorSubmitted();
});