import {Controller} from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="admin_users" attribute will cause
 * this controller to be executed. The name "admin_users" comes from the filename:
 * admin_users_controller.js -> "admin_users"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect() {

        // Delay the user input
        function delay(callback, ms) {
            let timer = 0;
            return function () {
                let context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        // Search button listening
        $('#admin_users_search').keyup(delay(function (e) {
            $.ajax({
                url: $(this).data('search-target'),
                method: 'POST',
                data: {keyword: this.value}
            }).then(function (response) {
                $('#admin_users_container').replaceWith(response);
                window.page.initShuffle();
            });


        }, 999));
    }
}
