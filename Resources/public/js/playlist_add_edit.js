
var prototypeHolder = $('.collection');
var collectionHolder = $('.collection tbody');
var $addButton = $('.add-collection');
var audio; // audio player dom object
var aUrl; // audio url
var serveMediaAction;
var wId;
var mrId;

jQuery(document).ready(function () {
    // attach add event
    $addButton.on('click', function (e) {
        e.preventDefault();
        addRegionPlaylistForm(collectionHolder);
    });
    audio = document.getElementById('aplayer');

    serveMediaAction = $('input[name="serveMediaAction"]').val();
    wId = $('input[name="wId"]').val();
    mrId = $('input[name="mrId"]').val();

    var data = {
        workspaceId: wId,
        id: mrId
    };
    // load media data
    $.get(serveMediaAction, data)
            .done(function (response) {
                var byteCharacters = atob(response);
                var byteNumbers = new Array(byteCharacters.length);
                for (var i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                var blob = new Blob([byteArray]);

                aUrl = URL.createObjectURL(blob);
                audio.src = aUrl;
            })
            .fail(function () {
                console.log('loading media resource file failed');
            });

    refreshOrdering();
    togglePlaylistPreListenButton();
});

function addRegionPlaylistForm(collectionHolder) {
    var prototype = prototypeHolder.attr('data-prototype');
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
    $(collectionHolder).append(newForm);
    // set value to the hidden order input and label
    refreshOrdering();
    togglePlaylistPreListenButton();
}

function removeRegionPlaylistRow(sender) {
    $(sender).closest('tr').remove();
    refreshOrdering();
    togglePlaylistPreListenButton();
}

function refreshOrdering() {
    $('.collection tbody tr').each(function (index) {
        var order = index + 1;
        $(this).find('td.playlist_region_widget_ordering input').val(order);
        $(this).find('td.playlist_region_widget_ordering label').text(order);
    });
}

function togglePlaylistPreListenButton() {
    var button = document.getElementById('play-button');
    button.disabled = !collectionHolder.children().length > 0;
}

function playlistPreListen() {
    var items = buildRegionsFromDom();
    play(items, 0);
}

function play(items, index) {
    if (index < items.length) {
        var start = items[index].start;
        var offset = items[index].end;
        var paramString = '#t=' + start + ',' + offset;
        audio.src = aUrl + paramString;
        audio.play();
        audio.onpause = function () {
            index = index + 1;
            play(items, index);
        };
    }
}

function buildRegionsFromDom() {
    var items = [];
    $('.collection tbody tr').each(function (index) {
        var textValue = $(this).find('td.playlist_region_widget_region option:selected').text();
        var item = toSeconds(textValue);
        items.push(item);
    });
    return items;
}

/*
 * recieve a string "m:s:ms - m:s:ms"
 * return {start : xx.xxxxx seconds in float , end : xx.xxxxx seconds in float} object
 */
function toSeconds(value) {
    var valueArr = value.split('-');
    var hStart = $.trim(valueArr[0]);
    var hEnd = $.trim(valueArr[1]);

    var hStartArray = hStart.split(':');
    var hEndArray = hEnd.split(':');

    var SStart = (parseInt(hStartArray[0]) * 60 + parseInt(hStartArray[1])).toString() + '.' + parseInt(hStartArray[2]);
    var SEnd = (parseInt(hEndArray[0]) * 60 + parseInt(hEndArray[1])).toString() + '.' + parseInt(hEndArray[2]);

    return {
        start: parseFloat(SStart),
        end: parseFloat(SEnd)
    };
}