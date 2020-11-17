/**
 * Add validation forms
 */
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

/**
 * Show tooltips for element with class or data-toggle
 */
$(function () {
    $('[data-toggle="tooltip"],.tooltip-show').tooltip({'placement': 'bottom'});
});

/**
 * Show message in block with text and with different style
 * @param message
 * @param type
 */
function showAlert(message, type) {
    $('.info-block').append('<div class="alert alert-' + type + '" role="alert">' + message + '</div>');
    setTimeout(function () {
        $(".alert").alert('close');
    }, 3000);
}


/**
 * Download pictures from url and response answer
 */
$(document).ready(function ($) {
    $(document).on('click', '.check, .download', function (event) {
        event.preventDefault();
        let check = $(this).data('check');
        let form = $('.get-file');
        let action = form.attr('action');
        let data = form.serialize() + '&check=' + check;
        $.ajax({
            type: 'POST',
            url: action,
            data: data,
            dataType: 'json',
            success: function (result) {
                showAlert(result.data[0], result.success)
            },
            error: function (jqXHR, testStatus, error) {
                console.log(error);
                showAlert("Page " + href + " cannot open. Error:" + error, 'danger')
                $('#loader').hide();
            }
        });
        setTimeout(getProgress, 3000);

        return false;
    });

    function getProgress() {
        $.ajax({
            url: '/url/listen',
            dataType: 'json',
            success: function (data) {
                if (data.data[1] <= data.data[0] && data.data[1] >= 1) {
                    $('.info-block').html('<div class="progress">' +
                        '<div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width:' +
                        (data.data[1] / data.data[0]) * 100 + '%"></div></div><h4><span class="badge badge-success">' +
                        data.data[1] + '/' + data.data[0] +
                        '</span></h4>');
                    setTimeout(getProgress, 1000);
                    console.log('Repeat');
                } else {
                    setTimeout(function () {
                        $('.info-block').empty()
                    }, 3000);
                    console.log('End');
                }
            }
        });
    }
});