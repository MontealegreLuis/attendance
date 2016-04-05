/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
(function($) {
    var source = new EventSource('attendance.php');

    source.addEventListener('message', function(event) {
        var attendance = JSON.parse(event.data);
        var $studentsCount = $('#students-count-' + attendance.bootcamp_id);
        var $todayPercentage = $('#today-percentage-' + attendance.bootcamp_id);
        var $onTimeToday = $('#on-time-today-' + attendance.bootcamp_id);
        var $perfectAttendance = $('#perfect-attendance-' + attendance.bootcamp_id);
        var studentsCount = $studentsCount.data('count');
        var todayPercentage = $todayPercentage.data('percentage');
        var onTimeToday = $onTimeToday.data('percentage');
        var perfectAttendance = $perfectAttendance.data('count');
        var newTodayPercentage = (Math.round(studentsCount * todayPercentage / 100) + 1) * 100 / studentsCount;
        var newOnTimeToday = (Math.round(studentsCount * onTimeToday / 100) + 1) * 100 / studentsCount;

        $todayPercentage.children('strong').html(newTodayPercentage.toFixed(2) + '%');
        $onTimeToday.children('strong').html(newOnTimeToday.toFixed(2) + '%');
        if (newTodayPercentage >= 100) {
            $perfectAttendance.children('strong').html(perfectAttendance + 1);
        }
    });
    $('#myCarousel').carousel({
        interval: 5000
    });
})(jQuery);
