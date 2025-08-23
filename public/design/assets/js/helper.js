function getCsrfToken() {
    return $('#csrf-input').val();
}

function getUrl() {
    return $('#url-input').val();
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': getCsrfToken()
    }
});

function makeAjax(ajax_url, ajax_method, data = {}, progress_data = {}) {
    return $.ajax({
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable && progress_data.status) {
                    var percentComplete = ((evt.loaded / evt.total) * 100);
                    $(progress_data.progress_bar_selector).width(percentComplete.toFixed() + '%');
                    $(progress_data.progress_bar_selector).html(percentComplete.toFixed() + '%');
                    if (percentComplete == 100) {
                        $(progress_data.status_msg_selector).html('Processing, Please Wait');
                        $(progress_data.status_msg_selector).removeClass('none');
                    }
                }
            }, false);
            return xhr;
        },
        type: ajax_method,
        url: ajax_url,
        dataType: "json",
        data: data,
        processData: data instanceof FormData ? false : true,
        contentType: false,
        beforeSend: function () {
            if (progress_data.status) {
                $(progress_data.progress_bar_selector).width('0%');
            }
        },
        success: function (response) {

        },
        error: function (response) {
            // alert('Error when make ajax');
        }
    });
}

function showError(msg, timeout = 5000) {
    $('.error-msg.js-message').html(msg);
    $('.error-msg.js-message').removeClass('none');
    setTimeout(function () {
        $('.error-msg.js-message').addClass('none');
    }, timeout);
}

function showSuccess(msg, timeout = 5000) {
    $('.success-msg.js-message').html(msg);
    $('.success-msg.js-message').removeClass('none');
    setTimeout(function () {
        $('.success-msg.js-message').addClass('none');
    }, timeout);
}

function confirmMsg() {
    return confirm('هل أنت متأكد ؟');
}

function cancelMsg() {
    return confirm('Are You Sure To Cancel This?');
}

$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {
    let title = $(this).data("title");
    let ajax_url = $(this).data('url');
    $("#common-modal .modal-title").html(title);
    getCommonModalContent(ajax_url);
});

async function getCommonModalContent(ajax_url) {
    try {
        let ajax_method = 'GET';
        var res = await makeAjax(ajax_url, ajax_method);
        $('#common-modal .body').html(res.view);
        $("#common-modal").modal('show');
    }
    catch (e) {
        if (e.message) showError(e.message);
        else if (e.responseJSON.message) showError(e.responseJSON.message);
    }
}

function appendParamToCurrentUrl(param = { key: '', val: '' }) {
    let current_url = location.href;
    let new_url = new URL(current_url);
    new_url.searchParams.set(param.key, param.val);
    new_url.toString();
    return new_url;
}
