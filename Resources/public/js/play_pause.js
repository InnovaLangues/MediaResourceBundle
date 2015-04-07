var domUtils;
var jUtils;
var baseAudioUrl = '';
var pauseTime = 2000;
var ended = false;
var regions = [];
var audioPlayer;
var playButton;

// ======================================================================================================== //
// DOCUMENT READY
// ======================================================================================================== //
$(document).ready(function () {
    audioPlayer = document.getElementsByTagName("audio")[0];
    var progress = document.getElementById('seekbar');
    playButton = document.getElementById('play');
    audioPlayer.loop = false;
    baseAudioUrl = audioPlayer.src;

    /* JS HELPERS */
    domUtils = Object.create(DomUtils);
    jUtils = Object.create(JavascriptUtils);
    /* /JS HELPERS */

    createRegions();

    audioPlayer.addEventListener('timeupdate', function (e) {
        var percent = audioPlayer.currentTime * 100 / audioPlayer.duration;
        progress.style.width = percent + '%';
    });

    audioPlayer.addEventListener('pause', function (e) {
        if (!ended) {
            nextRegion = getNextRegion(audioPlayer.currentTime);
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
        }
    });
});

function play() {
    playButton.disabled = true;
    ended = false;
    audioPlayer.currentTime = 0;
    var nextRegion = getNextRegion(audioPlayer.currentTime);
    var offset = nextRegion.end;
    var paramString = '#t=0,' + offset;
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
//            console.log('found @ ' + regions[index].end);
            var isLast = index > length ? true : false;
            return {
                start: regions[index].start,
                end: regions[index].end,
                last: isLast
            };
        }
    }
}

