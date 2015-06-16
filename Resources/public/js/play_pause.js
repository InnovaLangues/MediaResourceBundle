var domUtils;
var jUtils;
var baseAudioUrl = '';
var pauseTime = 2000;
var ended = false;
var regions = [];
var audioPlayer;
var playButton;

var serveMediaAction;
var wId;
var mrId;

// ======================================================================================================== //
// DOCUMENT READY
// ======================================================================================================== //
$(document).ready(function () {
    audioPlayer = document.getElementsByTagName("audio")[0];
    var progress = document.getElementById('seekbar');
    playButton = document.getElementById('play');
    audioPlayer.loop = false;

    serveMediaAction = $('input[name="serveMediaAction"]').val();
    wId = $('input[name="wId"]').val();
    mrId = $('input[name="mrId"]').val();

    /* JS HELPERS */
    domUtils = Object.create(DomUtils);
    jUtils = Object.create(JavascriptUtils);
    /* /JS HELPERS */

    // create regions JS objects
    createRegions();

    var data = {
        workspaceId: wId,
        id: mrId
    };
    // get media data
    $.get(serveMediaAction, data)
            .done(function (response) {
                var byteCharacters = atob(response);
                var byteNumbers = new Array(byteCharacters.length);
                for (var i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                var blob = new Blob([byteArray]);
                baseAudioUrl = URL.createObjectURL(blob);
                audioPlayer.src = baseAudioUrl;
            })
            .fail(function () {
                console.log('loading media resource file failed');
            });

    // draw progress bar while playing
    audioPlayer.addEventListener('timeupdate', function (e) {
        var percent = audioPlayer.currentTime * 100 / audioPlayer.duration;
        progress.style.width = percent + '%';
    });

    audioPlayer.addEventListener('pause', function (e) {
        nextRegion = getNextRegion(audioPlayer.currentTime);
        if (!ended && nextRegion) {
            
            offset = nextRegion.start;
            paramString = '#t=' + offset + ',' + nextRegion.end;
            audioPlayer.src = baseAudioUrl + paramString;

            window.setTimeout(function () {
                audioPlayer.play();
                if (nextRegion.last) {
                    ended = true;
                }
            }, pauseTime);
        }
        else { // pause event is sent when ended
            audioPlayer.currentTime = 0;
            playButton.disabled = false;
            ended = false;
        }
    });
});

function play() {
    playButton.disabled = true;
    ended = false;
    audioPlayer.currentTime = 0;
    var paramString = '';
    var nextRegion = getNextRegion(audioPlayer.currentTime);
    if (nextRegion) {
        var offset = nextRegion.end;
        paramString = '#t=0,' + offset;
    }
    audioPlayer.src = baseAudioUrl + paramString;
    audioPlayer.play();
}


// ======================================================================================================== //
// DOCUMENT READY END
// ======================================================================================================== //

function createRegions() {
    $(".region").each(function () {
        var start = $(this).find('input.hidden-start').val();
        var end = $(this).find('input.hidden-end').val();
        if (start && end) {
            var region = {
                start: start,
                end: end
            };
            regions.push(region);
        }
    });
}

function getNextRegion(time) {
    var length = Object.keys(regions).length;
    length = length - 2;
    for (var index in regions) {
        if (regions[index].start <= time && regions[index].end > time) {
            var isLast = index > length ? true : false;
            return {
                start: regions[index].start,
                end: regions[index].end,
                last: isLast
            };
        }
    }
}
