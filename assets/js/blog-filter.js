$(document).ready(function () {
    let typingTimer;

    function loadBlogs() {
        $.ajax({
            url: 'fetch_blogs.php',
            type: 'GET',
            data: {
                search: $('#search').val(),
                category: $('#category').val(),
                date: $('#date').val()
            },
            beforeSend: function () {
                $('#blogResults').html('<div class="loader">Loading blogs...</div>');
            },
            success: function (response) {
                $('#blogResults').html(response);
            },
            error: function () {
                $('#blogResults').html('<div class="empty-state"><h3>Something went wrong</h3><p>Please try again after checking your server connection.</p></div>');
            }
        });
    }

    loadBlogs();

    $('#search').on('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(loadBlogs, 350);
    });

    $('#category, #date').on('change', loadBlogs);

    $('#clearFilters').on('click', function () {
        $('#search').val('');
        $('#category').val('');
        $('#date').val('');
        loadBlogs();
    });
});
