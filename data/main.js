$(document).ready(function() {
    $('#menuToggle').on('click', function() {
        $('#sidebar').toggleClass('active');
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#sidebar, #menuToggle').length) {
            $('#sidebar').removeClass('active');
        }
    });
});