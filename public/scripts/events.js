/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
(function($) {
    'use strict';

    var source = new EventSource('/attendances');

    var percentage = function(count, total) {
        return (count * 100.0 / total).toFixed(2);
    };

    source.addEventListener('message', function(event) {
        var attendance = JSON.parse(event.data);
        var totalStudents = $('#students-count-' + attendance.bootcamp_id).data('count');
        var $todayPercentage = $('#today-percentage-' + attendance.bootcamp_id);
        var $onTimeToday = $('#on-time-today-' + attendance.bootcamp_id);
        var $perfectAttendance = $('#perfect-attendance-' + attendance.bootcamp_id);

        var newTodayPercentage = percentage(attendance.students_count, totalStudents);

        $todayPercentage
            .children('strong')
            .html(newTodayPercentage + '%')
        ;
        $onTimeToday
            .children('strong')
            .html(percentage(attendance.on_time_students_count, totalStudents) + '%')
        ;
        if (newTodayPercentage >= 100) {
            var perfectAttendance = $perfectAttendance.data('count');
            $perfectAttendance.children('strong').html(perfectAttendance + 1);
        }
    });
    $('#myCarousel').carousel({
        interval: 5000
    });
})(jQuery);
