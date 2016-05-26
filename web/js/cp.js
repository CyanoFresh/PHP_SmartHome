var WS;
var WebSocketURL;
var connection_opened;

const $content = $('#content');
const $loader = $('#loader');
const $loaderStatus = $('.loader-status');
const $loaderStatusMore = $('.loader-status-more');
const $loaderSpinner = $('.loader-spinner');

function log(msg) {
    console.log(msg);
}

function connect() {
    WS = new WebSocket(WebSocketURL);

    WS.onopen = function () {
        connection_opened = true;

        $loader.fadeOut(function () {
            $content.fadeIn();
        });
    };

    WS.onclose = function () {
        if (connection_opened) {
            $loaderStatus.html('Ошибка');
            $loaderStatusMore.html('Соединение с сервером потеряно');

            $content.fadeOut(function () {
                $loader.fadeIn();
            });
        } else {
            $loaderStatus.html('Ошибка подключения');
            $loaderStatusMore.html('Сервер не отвечает');
        }
    };

    WS.onerror = function () {
        $loaderStatus.html('Ошибка');
        $loaderStatusMore.html('Произошла ошибка');

        if (connection_opened) {
            $content.fadeOut(function () {
                $loader.fadeIn();
            });
        }
    };

    WS.onmessage = onMessage;
}

function onMessage(response) {
    try {
        var data = JSON.parse(response.data);

        log(data);

        switch (data.type) {
            case 'welcome':
                setConnection(data.connection);

                var i = data.items.length;

                while (i--) {
                    var $itemToggleBtn = $('.items').find('[data-item="' + data.items[i].id + '"]');

                    initToggleBtn($itemToggleBtn, data.items[i].state);
                }

                break;
            case 'itemState':
                var $itemToggleBtn = $('.items').find('[data-item="' + data.itemID + '"]');

                initToggleBtn($itemToggleBtn, data.state);

                break;
        }
    } catch (e) {
        log(e);
    }
}

function send(msg) {
    if (typeof msg != "string") {
        msg = JSON.stringify(msg);
    }

    log('Sending:' + msg);

    if (WS && WS.readyState == 1) {
        WS.send(msg);
    }
}

function m(message, type) {
    return noty({
        text: message,
        type: type,
        theme: 'my',
        layout: 'bottomLeft',
        dismissQueue: true,
        timeout: 3000,
        animation: {
            open: 'animated fadeIn',
            close: 'animated fadeOut'
        }
    });
}

function setConnection(connection) {
    if (!connection) {
        $('#no-connection').show();
    }
}

function initToggleBtn($btn, state) {
    if (state) {
        if ($btn.hasClass('btn-success')) {
            $btn.removeClass('btn-success');
        }

        $btn.html('выключить').addClass('btn-danger');
    } else {
        if ($btn.hasClass('btn-danger')) {
            $btn.removeClass('btn-danger');
        }

        $btn.html('включить').addClass('btn-success');
    }
}

$(function () {
    connect();

    $('.btn-toggle').click(function (e) {
        e.preventDefault();

        send({
            'type': 'toggle',
            'itemID': $(this).data('item')
        });
    })
});