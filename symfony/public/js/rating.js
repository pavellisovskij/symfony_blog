$(document).ready(function () {
    $('.rating_star').each(function () {
        let id = $('.rating').attr('data-post-id');
        let grade = $(this).val();

        $(this).on('change', function () {
            axios.post('/' + id + '/evaluate/' + grade)
            .then(function (response) {
                if (response.data.result === 'success') {
                    alert('you rated this post with ' + grade + ' points.');
                } else if (response.data.result === 'deny') {
                    alert('access deny.');
                }
            })
            .catch(function (error) {
                console.log(error);
                alert('something wrong.');
            });
        })
    })
})