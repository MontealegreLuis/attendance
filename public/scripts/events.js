/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
(function($) {
    var source = new EventSource('attendance.php');
    var rowTemplate = '<tr><td>{{content}}</td></tr>';
    source.addEventListener('message', function(event) {
        var attendance = JSON.parse(event.data);
        var row = rowTemplate.replace(
            '{{content}}',
            attendance.occurred_on + ' - ' + attendance.attendance_id.value
        );
        $('.js-students').append(row);
    });
})(jQuery);
