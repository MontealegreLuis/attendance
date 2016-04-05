/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
(function($) {
    var source = new EventSource('attendance.php');
    var rowTemplate = '<tr><td>{{class}}</td><td>{{student}}</td><td>{{time}}</td></tr>';
    source.addEventListener('message', function(event) {
        var attendance = JSON.parse(event.data);
        console.log(attendance);
        /*var row = rowTemplate
            .replace('{{class}}', attendance.cohort_name)
            .replace('{{student}}', attendance.name)
            .replace('{{time}}', attendance.date.split(' ')[1])
        ;
        $('.js-students').append(row);*/
    });
    $('#myCarousel').carousel({
        interval: 5000
    });
})(jQuery);
