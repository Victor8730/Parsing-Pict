(function () {
    'use strict';
    window.addEventListener('load', function () {
        let forms = document.getElementsByClassName('needs-validation');
        let validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

$(function () {
    $('[data-toggle="tooltip"],.tooltip-show').tooltip({'placement': 'bottom'});
});

function showAlert(message, type) {
    $('.info-block').append('<div class="alert alert-' + type + '" role="alert">' + message + '</div>');
    setTimeout(function () {
        $(".alert").alert('close');
    }, 3000);
}

$(document).on('click', '.check', function (event) {
    event.preventDefault();
    let form = $('.get-file');
    let action = form.attr('action');
    let data = form.serialize();
    $.ajax({
        type: 'POST',
        url: action,
        data: data,
        dataType: 'json',
        success: function (result) {
            showAlert(result.message, result.success)
        },
        error: function (jqXHR, testStatus, error) {
            console.log(error);
            alert("Page " + href + " cannot open. Error:" + error);
            $('#loader').hide();
        },
        timeout: 8000
    })
});