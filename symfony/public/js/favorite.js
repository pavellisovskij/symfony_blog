function favorite (id) {
    axios.post('/' + id + '/favorite', {
        id: id
    })
    .then(function (response) {
        if (!response.data.result || response.data.result) {
            let numbered_id = '#favorite-' + id;

            if (response.data.result === true) {
                $(numbered_id).attr('class', 'bi bi-bookmark-star-fill').css('color', 'red');
            } else {
                $(numbered_id).attr('class', 'bi bi-bookmark-star').css('color', 'currentColor');
            }
        }
        else alert('something wrong with AJAX request.');
    })
    .catch(function (error) {
        console.log(error);
        alert('something wrong.');
    });
}