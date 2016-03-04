/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
(function() {
    var source = new EventSource('attendance.php');
    source.addEventListener('message', function(event) {
        console.log(event.data);
    });
})();
