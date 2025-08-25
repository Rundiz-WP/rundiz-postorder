/**
 * Rundiz PostOrder JS.
 * 
 * @package rundiz-postorder
 */


class RdPostOrderReOrder {


    /**
     * @since 1.0.9
     * @type { Boolean } Set to `true` to mark AJAX (XHR) is working, `false` to not currently working. Default is `false`.
     */
    #ajaxIsWorking = false;


    /**
     * Class constructor.
     */
    constructor() {
        this.#listenClickButtonModifyFormMethod();
        this.#listenClickDismissNotice();
        this.#listenClickExpandCollapseTableRow();
        this.#listenClickReOrderPerItem();

        this.#listenKeyEnterOnMenuOrder();
        this.#listenKeyEnterOnPageNumberInputResetBulkActions();
        this.#listenKeyEscCancelSortable();

        this.#listenFormSubmit();

        this.#enablePostSortable();
    }// constructor


    /**
     * @type { String } Sortable element selector.
     * @return { String } Sortable element selector.
     */
    get #sortableSelector() {
        return '.post-reorder-table tbody';
    }// #sortableSelector


    /**
     * AJAX re-number all posts.
     * 
     * This method was called from `#listenFormSubmit()`.
     * 
     * @since 1.0.9 Renamed from `ajaxReNumberAll()`.
     * @returns {false}
     */
    #ajaxReNumberAll() {
        const $ = jQuery.noConflict();

        if (this.#ajaxIsWorking === true) {
            alert(RdPostOrderObj.txtPreviousXhrWorking);
            return false;
        }

        const confirmed_val = confirm(RdPostOrderObj.txtConfirmReorderAll);

        if (confirmed_val === true) {
            this.#ajaxIsWorking = true;
            this.#disablePostSortable();
            document.querySelector('.form-result-placeholder').innerHTML = '';

            const formData = new FormData();
            formData.set('action', 'RdPostOrderReNumberAll');
            formData.set('security', RdPostOrderObj.ajaxnonce);
            formData.set('_wp_http_referer', document.querySelector('input[name="_wp_http_referer"]').value);
            formData.set('paged', this.#getCurrentPaged());
            formData.set('hookName', RdPostOrderObj.hookName);

            if (RdPostOrderObj.debug === 'true') {
                console.debug('[rundiz postorder]: Calling AJAX re-number all posts.');
            }

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: new URLSearchParams(formData).toString(),
                dataType: 'json'
            })
            .done((response, textStatus, jqXHR) => {
                // displaying result to the page.
                this.#displayNoticeElement(response, 'notice-error');

                if (typeof(response) !== 'undefined') {
                    this.#ajaxTaskReplaceTable(response);
                }
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                this.#displayNoticeElement(jqXHR, 'notice-error');
            })
            .always((jqXHR, textStatus, errorThrown) => {
                // mark XHR is not working.
                this.#ajaxIsWorking = false;
                // re-activate sortable
                this.#enablePostSortable();
            });
        }// endif; confirmed

        return false;
    }// #ajaxReNumberAll


    /**
     * Re-order per item (move up, move down).
     * 
     * This method was called from `#listenClickReOrderPerItem()`.
     * 
     * @since 1.0.9 Renamed from `ajaxReOrder()`.
     * @param {string} move_to Move to action. Accepted: 'up', 'down'.
     * @param {int|number} postID The post ID.
     * @param {int|number} currentMenuOrder Current menu order.
     * @returns {false}
     */
    #ajaxReOrderPerItem(move_to, postID, currentMenuOrder) {
        if (typeof(move_to) !== 'string') {
            throw new Error('The argument `move_to` must be string.');
        }
        if (typeof(postID) !== 'number') {
            throw new Error('The argument `postID` must be number.');
        }
        if (typeof(currentMenuOrder) !== 'number') {
            throw new Error('The argument `currentMenuOrder` must be number.');
        }

        const $ = jQuery.noConflict();

        if (this.#ajaxIsWorking === true) {
            alert(RdPostOrderObj.txtPreviousXhrWorking);
            return false;
        }

        if (typeof(move_to) === 'undefined') {
            move_to = 'up';
        }

        this.#ajaxIsWorking = true;
        this.#disablePostSortable();
        document.querySelector('.form-result-placeholder').innerHTML = '';

        const formData = new FormData();
        formData.set('action', 'RdPostOrderReOrderPost');
        formData.set('security', RdPostOrderObj.ajaxnonce);
        formData.set('_wp_http_referer', document.querySelector('input[name="_wp_http_referer"]').value);
        formData.set('move_to', move_to);
        formData.set('postID', postID);
        formData.set('menu_order', currentMenuOrder);
        formData.set('paged', this.#getCurrentPaged());
        formData.set('hookName', RdPostOrderObj.hookName);

        if (RdPostOrderObj.debug === 'true') {
            console.debug('[rundiz postorder]: Calling AJAX update post order (move ' + move_to + ').');
        }

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: new URLSearchParams(formData).toString(),
            dataType: 'json'
        })
        .done((response, textStatus, jqXHR) => {
            // displaying result to the page.
            this.#displayNoticeElement(response, 'notice-error');

            if (typeof(response) !== 'undefined') {
                this.#ajaxTaskReplaceTable(response);
            }
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            this.#displayNoticeElement(jqXHR, 'notice-error');
        })
        .always((jqXHR, textStatus, errorThrown) => {
            // mark XHR is not working.
            this.#ajaxIsWorking = false;
            // re-activate sortable
            this.#enablePostSortable();
        });

        return false;
    }// #ajaxReOrderPerItem


    /**
     * Re-order posts by drag & drop.
     * 
     * This method was called from `#enablePostSortable()`.
     * 
     * @since 1.0.9 Renamed from `ajaxUpdateSortItems()`.
     * @param {string} sorted_items_serialize_values Serialized sortable values created by jQuery UI.
     * @param {int} max_menu_order Maximum menu order number of that page.
     * @returns {undefined}
     */
    #ajaxReOrderPosts(sorted_items_serialize_values, max_menu_order) {
        let $ = jQuery.noConflict();

        if (this.#ajaxIsWorking === true) {
            alert(RdPostOrderObj.txtPreviousXhrWorking);
            return false;
        }

        this.#ajaxIsWorking = true;
        this.#disablePostSortable();
        document.querySelector('.form-result-placeholder').innerHTML = '';

        let formData = new FormData();
        formData.set('action', 'RdPostOrderReOrderPosts');
        formData.set('security', RdPostOrderObj.ajaxnonce);
        formData.set('_wp_http_referer', document.querySelector('input[name="_wp_http_referer"]').value);
        formData.set('max_menu_order', max_menu_order);
        formData.set('hookName', RdPostOrderObj.hookName);
        document.querySelectorAll('.menu_order_value')?.forEach((eachInput) => {
            formData.set(eachInput.name, eachInput.value);
        });
        formData = new URLSearchParams(formData).toString() + '&' + sorted_items_serialize_values;

        if (RdPostOrderObj.debug === 'true') {
            console.debug('[rundiz postorder]: Calling AJAX re-order posts (sortable items).');
        }

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            dataType: 'json'
        })
        .done((response, textStatus, jqXHR) => {
            // displaying result to the page.
            this.#displayNoticeElement(response, 'notice-error');

            if (typeof(response) !== 'undefined') {
                if (response?.save_result === true) {
                    if (typeof(response.re_ordered_data) === 'object') {
                        // if updated then the new `menu_order` data will be send to here.
                        // loop set new value of `menu_order` data to HTML elements.
                        for (const [key, item] of Object.entries(response.re_ordered_data)) {
                            document.getElementById('menu_order_' + item.ID).value = item.menu_order;
                            const tableTrPerPostId = document.getElementById('postID-' + item.ID);
                            if (tableTrPerPostId) {
                                tableTrPerPostId.dataset.rdPostorderMenuOrder = item.menu_order;
                            } else {
                                console.error('[rundiz postorder]: Unable to find table row ID `postID-' + itme.ID + '`.');
                            }
                        }
                    }
                }
            }
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            this.#displayNoticeElement(jqXHR, 'notice-error');
        })
        .always((jqXHR, textStatus, errorThrown) => {
            // mark XHR is not working.
            this.#ajaxIsWorking = false;
            // re-activate sortable
            this.#enablePostSortable();
        });
    }// #ajaxReOrderPosts


    /**
     * Reset all post order by use DB order by `post_date` ascending.
     * 
     * This method was called from `#listenFormSubmit()`.
     * 
     * @since 1.0.9 Renamed from `ajaxResetAllPostsOrder()`.
     * @returns {false}
     */
    #ajaxResetAllPostsOrder() {
        const $ = jQuery.noConflict();

        if (this.#ajaxIsWorking === true) {
            alert(RdPostOrderObj.txtPreviousXhrWorking);
            return false;
        }

        const confirmed_val = confirm(RdPostOrderObj.txtConfirmReorderAll);

        if (confirmed_val === true) {
            this.#ajaxIsWorking = true;
            this.#disablePostSortable();
            document.querySelector('.form-result-placeholder').innerHTML = '';

            const formData = new FormData();
            formData.set('action', 'RdPostOrderResetAllPostsOrder');
            formData.set('security', RdPostOrderObj.ajaxnonce);
            formData.set('_wp_http_referer', document.querySelector('input[name="_wp_http_referer"]').value);
            formData.set('paged', this.#getCurrentPaged());
            formData.set('hookName', RdPostOrderObj.hookName);

            if (RdPostOrderObj.debug === 'true') {
                console.debug('[rundiz postorder]: Calling AJAX reset all posts order.');
            }

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: new URLSearchParams(formData).toString(),
                dataType: 'json'
            })
            .done((response, textStatus, jqXHR) => {
                // displaying result to the page.
                this.#displayNoticeElement(response, 'notice-error');

                if (typeof(response) !== 'undefined') {
                    this.#ajaxTaskReplaceTable(response);
                }
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                this.#displayNoticeElement(jqXHR, 'notice-error');
            })
            .always((jqXHR, textStatus, errorThrown) => {
                // mark XHR is not working.
                this.#ajaxIsWorking = false;
                // re-activate sortable
                this.#enablePostSortable();
            });
        }// endif; confirmed

        return false;
    }// #ajaxResetAllPostsOrder


    /**
     * Save all numbers input that was made change.
     * 
     * This method was called from `#listenFormSubmit()`.
     * 
     * @since 1.0.9 Renamed from `ajaxSaveAllNumbersChanged()`.
     * @returns {false}
     */
    #ajaxSaveAllNumbersChanged() {
        const $ = jQuery.noConflict();

        if (this.#ajaxIsWorking === true) {
            alert(RdPostOrderObj.txtPreviousXhrWorking);
            return false;
        }

        const confirmed_val = confirm(RdPostOrderObj.txtConfirm);

        if (confirmed_val === true) {
            this.#ajaxIsWorking = true;
            this.#disablePostSortable();
            document.querySelector('.form-result-placeholder').innerHTML = '';

            const formData = new FormData();
            formData.set('action', 'RdPostOrderSaveAllNumbersChanged');
            formData.set('security', RdPostOrderObj.ajaxnonce);
            formData.set('_wp_http_referer', document.querySelector('input[name="_wp_http_referer"]').value);
            formData.set('paged', this.#getCurrentPaged());
            formData.set('hookName', RdPostOrderObj.hookName);
            document.querySelectorAll('.menu_order_value')?.forEach((eachInput) => {
                formData.set(eachInput.name, eachInput.value);
            });

            if (RdPostOrderObj.debug === 'true') {
                console.debug('[rundiz postorder]: Calling AJAX save all changes on order numbers.');
            }

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: new URLSearchParams(formData).toString(),
                dataType: 'json'
            })
            .done((response, textStatus, jqXHR) => {
                // displaying result to the page.
                this.#displayNoticeElement(response, 'notice-error');

                if (typeof(response) !== 'undefined') {
                    this.#ajaxTaskReplaceTable(response);
                }
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                this.#displayNoticeElement(jqXHR, 'notice-error');
            })
            .always((jqXHR, textStatus, errorThrown) => {
                // mark XHR is not working.
                this.#ajaxIsWorking = false;
                // re-activate sortable
                this.#enablePostSortable();
            });
        }// endif; confirmed

        return false;
    }// #ajaxSaveAllNumbersChanged


    /**
     * After ajax task: replace table contents.
     * 
     * This method was called from `#ajaxReNumberAll()`, `#ajaxReOrderPerItem()`, `#ajaxResetAllPostsOrder()`, `#ajaxSaveAllNumbersChanged()`.
     * 
     * @since 1.0.9 Renamed from `_ajaxReplaceTable()`.
     * @param {object} response The object must contain `save_result` and `list_table_updated` properties.
     * @returns {undefined}
     */
    #ajaxTaskReplaceTable(response) {
        if (response?.save_result === true) {
            if (typeof(response.list_table_updated) === 'string') {
                const tempHTML = document.createElement('div');
                tempHTML.innerHTML = response.list_table_updated;

                const tableOnPage = document.querySelector('.post-reorder-table');
                tableOnPage.replaceWith(tempHTML.querySelector('.post-reorder-table'));

                if (RdPostOrderObj.debug === 'true') {
                    console.debug('[rundiz postorder]:   Replaced table on the page with another one from AJAX.');
                }
            }
        }
    }// #ajaxTaskReplaceTable


    /**
     * Make notice popup auto hide.
     * 
     * This method was called from `displayNoticeElement()`.
     * 
     * @since 1.0.9 Renamed from `_autoHideNoticePopup()`.
     * @returns {undefined}
     */
    #autoHideNoticePopup() {
        const noticeElement = document.querySelector('.rd-postorder-notice-popup');
        const timeout = 7000;

        if (noticeElement) {
            if (RdPostOrderObj.debug === 'true') {
                console.debug('[rundiz postorder]: Notice popup will be remove in ' + timeout + ' milli seconds.');
            }

            setTimeout(() => {
                noticeElement.remove();
            }, timeout);
        }
    }// #autoHideNoticePopup


    /**
     * Disable posts sortable.
     * 
     * This method required jQuery UI sortable.
     * 
     * This method was called from `#ajaxReNumberAll()`, `#ajaxReOrderPerItem()`, `#ajaxReOrderPosts()`, `#ajaxResetAllPostsOrder()`, `#ajaxSaveAllNumbersChanged()`, `#listenKeyEscCancelSortable()`.
     * 
     * @since 1.0.9 Renamed from `disablePostSortable()`.
     * @returns { undefined }
     */
    #disablePostSortable() {
        if (RdPostOrderObj.debug === 'true') {
            console.debug('[rundiz postorder]: Disable table sortable.');
        }

        if (!this.#isSortableActivated()) {
            if (RdPostOrderObj.debug === 'true') {
                console.debug('[rundiz postorder]:   This list table is currently not activate for sortable. exit function.');
            }
            return false;
        }

        jQuery(this.#sortableSelector).sortable('destroy');
    }// #disablePostSortable


    /**
     * Display notice element based on AJAX response object.
     * 
     * This method was called from `#ajaxReNumberAll()`, `#ajaxReOrderPerItem()`, `#ajaxReOrderPosts()`, `#ajaxResetAllPostsOrder()`, `#ajaxSaveAllNumbersChanged()`.
     * 
     * @since 1.0.9 Renamed from `displayNoticeElement()`.
     * @param {object} response The response object from AJAX.
     * @param {string} default_notice_class Default notice class. Example: `notice-error`, `notice-success`.
     * @returns {undefined}
     */
    #displayNoticeElement(response, default_notice_class = 'notice-error') {
        if (typeof(default_notice_class) !== 'string' || '' === default_notice_class) {
            default_notice_class = 'notice-error';
        }

        if (response?.responseJSON) {
            response = response.responseJSON;
        }
        let responseText = '';
        if (typeof(response?.responseText) !== 'undefined') {
            responseText = response.responseText;
        }

        const formResultPlaceholder = document.querySelector('.form-result-placeholder');
        let formResultHTML;

        if (typeof(response?.form_result_msg) === 'string') {
            let formResultClass = default_notice_class;
            if (typeof(response.form_result_class) === 'string') {
                formResultClass = response.form_result_class;
            }
            formResultHTML = this.#getNoticeElement(formResultClass, response.form_result_msg);
            formResultPlaceholder.innerHTML = formResultHTML;
        } else if (typeof(responseText) === 'string') {
            // if the response page is showing text string. maybe error or something.
            if (responseText === '-1') {
                formResultHTML = this.#getNoticeElement(default_notice_class, RdPostOrderObj.txtReloadPageTryAgain);
                formResultPlaceholder.innerHTML = formResultHTML;
            } else if (responseText !== '') {
                formResultHTML = this.#getNoticeElement(default_notice_class, responseText);
                formResultPlaceholder.innerHTML = formResultHTML;
            }
        }

        // make notice popup auto hide.
        this.#autoHideNoticePopup();
    }// #displayNoticeElement


    /**
     * Enable posts sortable.
     * 
     * This method required jQuery UI sortable.
     * 
     * This method was called from `#ajaxReNumberAll()`, `#ajaxReOrderPerItem()`, `#ajaxReOrderPosts()`, `#ajaxResetAllPostsOrder()`, `#ajaxSaveAllNumbersChanged()`, `#listenKeyEscCancelSortable()`.
     * 
     * @link https://jsfiddle.net/o1Ldqhaj/1/ If `revert` option is enabled, when escape key pressed the dragging item will be removed with `.sortable('cancel')`.
     * @since 1.0.9 Renamed from `enablePostSortable()`.
     * @returns { undefined }
     */
    #enablePostSortable() {
        const thisClass = this;

        if (RdPostOrderObj.debug === 'true') {
            console.debug('[rundiz postorder]: Enable table sortable.');
        }

        if (this.#isSortableActivated()) {
            if (RdPostOrderObj.debug === 'true') {
                console.debug('[rundiz postorder]: The list table sortable is already activate. exit function.');
            }
            return ;
        }

        const tableSortableJQ = jQuery(this.#sortableSelector);
        tableSortableJQ.sortable({
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
                if (RdPostOrderObj.debug === 'true') {
                    console.debug('[rundiz postorder]: sortable is in `update` event.');
                }
                // on stopped sorting and position has changed.
                // get sorted items serialize values.
                let sorted_items_serialize_values = tableSortableJQ.sortable('serialize');
                // get max value of menu_order
                let max_menu_order = -Infinity;
                document.querySelectorAll('.post-item-row')?.forEach((item) => {
                    max_menu_order = Math.max(max_menu_order, parseFloat(item.dataset.rdPostorderMenuOrder));
                });

                thisClass.#ajaxReOrderPosts(sorted_items_serialize_values, max_menu_order);// required `thisClass`.
            }
        });
    }// #enablePostSortable


    /**
     * Get bulk actions select boxes value. Detect top box first then bottom.
     * 
     * This method was called from `#listenClickButtonModifyFormMethod()`.
     * 
     * @returns { Undefined|String } Return string value of selected box, or return `undefined` if non of any select boxes selected.
     */
    #getBulkActionsValue() {
        let selectValue;
        const selectTop = document.getElementById('bulk-action-selector-top');
        const selectBottom = document.getElementById('bulk-action-selector-bottom');

        if (selectTop.value !== '-1') {
            selectValue = selectTop.value;
        } else if (selectBottom.value !== '-1') {
            selectValue = selectBottom.value;
        }

        return selectValue;
    }// #getBulkActionsValue


    /**
     * Get current `paged` query string value. If not exists, use default `1`.
     * 
     * This method was called from `#ajaxReNumberAll()`, `#ajaxReOrderPerItem()`, `#ajaxResetAllPostsOrder()`, `#ajaxSaveAllNumbersChanged()`.
     * 
     * @returns {Number}
     */
    #getCurrentPaged() {
        const urlParams = new URLSearchParams(window.location.search);

        if (typeof(urlParams.get('paged')) === 'string' || typeof(urlParams.get('paged')) === 'number') {
            return parseFloat(urlParams.get('paged'));
        } else {
            return 1;
        }
    }// #getCurrentPaged


    /**
     * Get notice HTML element.
     * 
     * This method was called from `#displayNoticeElement()`.
     * 
     * @since 1.0.9 Renamed from `getNoticeElement()`.
     * @param {string} notice_class Example `notice-error`, `notice-success`.
     * @param {stirng} notice_message The notice message.
     * @returns { String }
     */
    #getNoticeElement(notice_class, notice_message) {
        return '<div class="' + notice_class + ' notice rd-postorder-notice-popup is-dismissible">'
            +'<p><strong>' + notice_message + '</strong></p>'
            +'<button type="button" class="notice-dismiss"><span class="screen-reader-text">' + RdPostOrderObj.txtDismissNotice + '</span></button>'
            +'</div>';
    }// #getNoticeElement


    /**
     * Check if there is at least one of sortable functional activated or not.
     * 
     * This method was called from `#disablePostSortable()`, `#enablePostSortable()`.
     * 
     * @returns { Boolean } Return `true` if activated at least one, `false` for otherwise.
     */
    #isSortableActivated() {
        let activated = false;

        document.querySelectorAll(this.#sortableSelector)?.forEach((item) => {
            if (item.classList.contains('ui-sortable')) {
                activated = true;
                return true;
            }
        });

        return activated;
    }// #isSortableActivated


    /**
     * Listen click on button and modify form method.
     * 
     * This method was called from `constructor()`.
     * 
     * @since 1.0.9 Renamed from `listenButtonActionClick()`.
     * @returns {undefined}
     */
    #listenClickButtonModifyFormMethod() {
        document.addEventListener('click', (event) => {
            let thisTarget = event.target;
            if (thisTarget.closest('.button.action')) {
                thisTarget = thisTarget.closest('.button.action');
            } else {
                return ;
            }

            if (RdPostOrderObj.debug === 'true') {
                console.debug('[rundiz postorder]: Button action was clicked');
            }

            const pageForm = document.getElementById('re-order-posts-form');
            const bulkActionsValue = this.#getBulkActionsValue();

            if (typeof(bulkActionsValue) === 'undefined') {
                // if not found any action selected.
                pageForm.setAttribute('method', 'get');

                if (RdPostOrderObj.debug === 'true') {
                    console.debug('[rundiz postorder]:   User select no action. Leave the process to WordPress core.');
                }
            } else if (typeof(bulkActionsValue) === 'string') {
                // if user selected any action.
                pageForm.setAttribute('method', 'post');
            } else {
                event.preventDefault();
                console.error('[rundiz postorder]: Something is wrong with bulk action.');
            }
        });
    }// #listenClickButtonModifyFormMethod


    /**
     * Listen click on dismiss notice and remove the notice element.
     * 
     * This method was called from `constructor()`.
     * 
     * @since 1.0.9 Previous was `reActiveDismissable()` but now use event delegation.
     * @returns {undefined}
     */
    #listenClickDismissNotice() {
        document.addEventListener('click', (event) => {
            const thisTarget = event.currentTarget.activeElement;
            if (thisTarget.closest('.notice-dismiss')) {
                event.preventDefault();
                
                const noticeE = thisTarget.closest('.notice');
                if (noticeE) {
                    noticeE.remove();
                }
            }
        });
    }// #listenClickDismissNotice


    /**
     * Listen click on expand/collapse table row button and do the task. Use event delegation for always work with AJAX.
     * 
     * The table expand/collapse function will be working on small screen such as smart phone.
     * 
     * This method was called from `constructor()`.
     * 
     * @since 1.0.9 Previous was `reActiveTableToggleRow()` but now use event delegation.
     * @returns {undefined}
     */
    #listenClickExpandCollapseTableRow() {
        // original source code is from wp-admin/js/common.js
        document.addEventListener('click', (event) => {
            let thisTarget = event.target;
            if (thisTarget.closest('.toggle-row')) {
                thisTarget = thisTarget.closest('.toggle-row');
            } else {
                return ;
            }

            const tableTr = thisTarget.closest('tr');
            if (!tableTr) {
                console.warn('There is no table `tr` element.');
            } else {
                if (tableTr.classList.contains('is-expanded')) {
                    tableTr.classList.remove('is-expanded');
                } else {
                    tableTr.classList.add('is-expanded');
                }
            }
        });
    }// #listenClickExpandCollapseTableRow


    /**
     * Listen click re-order per item (move up, move down) and then call to make AJAX update.
     * 
     * This method was called from `constructor()`.
     * 
     * @since 1.0.9
     * @returns {undefined}
     */
    #listenClickReOrderPerItem() {
        document.addEventListener('click', (event) => {
            let thisTarget = event.currentTarget.activeElement;
            if (thisTarget.closest('.rd-postorder-reorder-action-per-item')) {
                thisTarget = thisTarget.closest('.rd-postorder-reorder-action-per-item');
                event.preventDefault();
            } else {
                return ;
            }

            const tableTr = thisTarget.closest('.post-item-row');
            const moveTo = thisTarget.dataset.rdPostorderAction;
            let postID;
            let currentMenuOrder;

            if (tableTr) {
                postID = tableTr.dataset.rdPostorderPostId;
                if (typeof(postID) === 'string') {
                    postID = parseInt(postID);
                }

                currentMenuOrder = tableTr.dataset.rdPostorderMenuOrder;
                if (typeof(currentMenuOrder) !== 'undefined') {
                    currentMenuOrder = parseFloat(currentMenuOrder);
                }
            } else {
                console.error('[rundiz postorder]: Unable to find HTML class `.tableTr`.');
            }

            this.#ajaxReOrderPerItem(moveTo, postID, currentMenuOrder);
        });
    }// #listenClickReOrderPerItem


    /**
     * Listen on form submit and get action select box value at least one from top or bottom.
     * 
     * This method was called from `constructor()`.
     * 
     * @since 1.0.9 Renamed from `listenFormSubmit()`.
     * @returns {undefined}
     */
    #listenFormSubmit() {
        document.addEventListener('submit', (event) => {
            const thisTarget = event.target;
            if (thisTarget.getAttribute('id') !== 're-order-posts-form') {
                // if not this page's form.
                // not working here.
                return ;
            }

            if (RdPostOrderObj.debug === 'true') {
                console.debug('[rundiz postorder]: The form submitted');
            }

            const bulkActionsValue = this.#getBulkActionsValue();
            if (typeof(bulkActionsValue) === 'string') {
                console.debug('[rundiz postorder]:   Action selected: ' + bulkActionsValue);
            }

            if ('renumber_all' === bulkActionsValue) {
                event.preventDefault();
                return this.#ajaxReNumberAll();
            } else if ('reset_all' === bulkActionsValue) {
                event.preventDefault();
                return this.#ajaxResetAllPostsOrder();
            } else if ('save_all_numbers_changed' === bulkActionsValue) {
                event.preventDefault();
                return this.#ajaxSaveAllNumbersChanged();
            }
        });
    }// #listenFormSubmit


    /**
     * Listen enter key press on menu order input.
     * 
     * This method was called from `constructor()`.
     * 
     * @returns {undefined}
     */
    #listenKeyEnterOnMenuOrder() {
        document.addEventListener('keydown', (event) => {
            if (event.key.toLowerCase() === 'enter') {
                const thisTarget = event.currentTarget.activeElement;
                if (thisTarget.classList.contains('menu_order_value')) {
                    // if current enter element is menu order input.
                    event.preventDefault();

                    if (RdPostOrderObj.debug === 'true') {
                        console.debug('[rundiz postorder]: User press enter on menu order input field. Prevented default.');
                    }

                    alert(RdPostOrderObj.txtPleaseSelectSaveAllChanges);
                }
            }// endif; key down is enter.
        });
    }// #listenKeyEnterOnMenuOrder


    /**
     * Listen enter key press on current page input.<br>
     * This will be reset all select bulk actions to beginning because it is going to next/previous page, not submit action.
     * 
     * This method was called from `constructor()`.
     * 
     * @since 1.0.9 Renamed from `listenEnterKeyPressOnPageNumberInput()`.
     * @returns {undefined}
     */
    #listenKeyEnterOnPageNumberInputResetBulkActions() {
        document.addEventListener('keydown', (event) => {
            if (event.key.toLowerCase() === 'enter') {
                const thisTarget = event.currentTarget.activeElement;
                if (thisTarget.getAttribute('id') !== 'current-page-selector') {
                    return ;
                }

                if (RdPostOrderObj.debug === 'true') {
                    console.debug('[rundiz postorder]: The current page input has entered key press. Reset all action select boxes to beginning because this is going to next page, not submit action.');
                }

                document.getElementById('bulk-action-selector-top').value = '-1';
                document.getElementById('bulk-action-selector-bottom').value = '-1';
                document.getElementById('re-order-posts-form').setAttribute('method', 'get');
            }// endif; key down is enter.
        });
    }// #listenKeyEnterOnPageNumberInputResetBulkActions


    /**
     * Listen escape key press to cancel sortable.
     * 
     * This method was called from `constructor()`.
     * 
     * @since 1.0.9 Renamed from `listenEscKeyPress()`.
     * @see `#enablePostSortable()` for more details about why not to use `.sortable('cancel')`.
     * @returns { undefined }
     */
    #listenKeyEscCancelSortable() {
        document.addEventListener('keyup', (event) => {
            if (event.key.toLowerCase() === 'escape') {
                // if `esc` key pressed and up.
                if (!document.querySelector(this.#sortableSelector + ' .ui-sortable-helper')) {
                    if (RdPostOrderObj.debug === 'true') {
                        console.debug('[rundiz postorder]: Sortable is not dragging, do nothing here.');
                    }

                    return ;
                }

                if (RdPostOrderObj.debug === 'true') {
                    console.debug('[rundiz postorder]: Cancelling sortable.');
                }

                document.querySelectorAll(this.#sortableSelector + ' .post-item-row')?.forEach((eachSelector) => {
                    // remove in-line styles.
                    eachSelector.style.display = '';
                    eachSelector.style.height = '';
                    eachSelector.style.left = '';
                    eachSelector.style.position = '';
                    eachSelector.style.right = '';
                    eachSelector.style.top = '';
                    eachSelector.style.width = '';
                    eachSelector.style['z-index'] = '';
                    // remove class.
                    eachSelector.classList.remove('ui-sortable-helper');
                });
                document.querySelectorAll(this.#sortableSelector + ' .ui-placeholder')?.forEach((eachSelector) => {
                    eachSelector.remove();
                });

                const sortableTable = document.querySelector(this.#sortableSelector);
                if (!sortableTable) {
                    console.warn('[rundiz postorder]: The table is missing (' + this.#sortableSelector + ').');
                } else {
                    // destroy it to prevent `update` event work.
                    this.#disablePostSortable();

                    if (RdPostOrderObj.debug === 'true') {
                        console.debug('[rundiz postorder]: Destroyed table sortable functional to prevent `update` event.');
                    }

                    // re-enable sortable functional.
                    this.#enablePostSortable();
                }
            }// endif; key up matched.
        });
    }// #listenKeyEscCancelSortable


}// RdPostOrderReOrder


document.addEventListener('DOMContentLoaded', () => {
    let rdPostOrderClass = new RdPostOrderReOrder();
});