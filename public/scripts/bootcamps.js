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

    configureDateTime('#start_date');
    configureDateTime('#stop_date');
    $('.time').timepicker({showMeridian: false});
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        $('#file-label').html(label);
    });
})(jQuery);
