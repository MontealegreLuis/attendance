/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
(function () {
    'use strict';

    var configureDateTime = function(id) {
        var $date;
        $date = $(id).datepicker({format: 'yyyy-mm-dd'}).on('changeDate', function() {
            $date.hide();
        }).data('datepicker');
    };

    $('.time').timepicker({showMeridian: false});
    configureDateTime('#start_date');
    configureDateTime('#stop_date');
})(jQuery);
