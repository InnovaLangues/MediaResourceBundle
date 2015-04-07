// VARS
var transitionType = 'fast';
var currentExerciseType = '';
var audioUrl = '';
var wavesurfer;
var playing = false;
var loop = false;
var rate = 1;
var helpAudioPlayer;
var currentRegion = null;
var helpButton;
var domUtils;
var helpIsPlaying = false;

var wavesurferOptions = {
    container: '#waveform',
    waveColor: '#172B32',
    progressColor: '#00A1E5',
    height: 256,
    interact: true,
    scrollParent: true,
    normalize: true,
    minimap: true
};


// ======================================================================================================== //
// DOCUMENT READY
// ======================================================================================================== //
$(document).ready(function () {
    // get some hidden inputs usefull values
    currentExerciseType = 'audio';
    
    domUtils = Object.create(DomUtils);
    audioUrl = $('input[name="audio-url"]').val();

    helpButton = document.getElementById('help-btn');
    /* WAVESURFER */
    wavesurfer = Object.create(WaveSurfer);

    // wavesurfer progress bar
    (function () {
        var progressDiv = document.querySelector('#progress-bar');
        var progressBar = progressDiv.querySelector('.progress-bar');
        var showProgress = function (percent) {
            progressDiv.style.display = 'block';
            progressBar.style.width = percent + '%';
        };
        var hideProgress = function () {
            progressDiv.style.display = 'none';
        };
        wavesurfer.on('loading', showProgress);
        wavesurfer.on('ready', hideProgress);
        wavesurfer.on('destroy', hideProgress);
        wavesurfer.on('error', hideProgress);
    }());

    wavesurfer.init(wavesurferOptions);

    /* Minimap plugin */
    wavesurfer.initMinimap({
        height: 30,
        waveColor: '#ddd',
        progressColor: '#999',
        cursorColor: '#999'
    });

    wavesurfer.enableDragSelection({
        color: 'rgba(200, 55, 99, 0.1)'
    });

    wavesurfer.load(audioUrl);

    wavesurfer.on('ready', function () {
        var timeline = Object.create(WaveSurfer.Timeline);
        timeline.init({
            wavesurfer: wavesurfer,
            container: '#wave-timeline'
        });
    });

    wavesurfer.on('region-created', function (current) {
        //console.log(current);
        currentRegion = current;
        helpButton.disabled = false;
        // delete all other existing regions
        for (var index in wavesurfer.regions.list) {
            var region = wavesurfer.regions.list[index];
            if (region.start !== current.start && region.end !== current.start) {
                wavesurfer.regions.list[index].remove();
            }
        }
        // 
    });



    /* /WAVESURFER */



});

function play() {
    if (!playing) {
        if (currentRegion) {
            currentRegion.loop = loop;
            wavesurfer.play(currentRegion.start);
            if (!loop) {
                currentRegion.once('out', function () {
                    wavesurfer.pause();
                    playing = false;
                });
            }
        }
        else {
            wavesurfer.play();
        }
        playing = true;
    }
    else {
        wavesurfer.pause();
        playing = false;
    }
}

function deleteRegions() {
    for (var index in wavesurfer.regions.list) {
        wavesurfer.regions.list[index].remove();
    }
    currentRegion = null;
    helpButton.disabled = true;
}

function toggleHelp(){
    console.log('yo');
    domUtils.openSimpleHelpModal(currentRegion, audioUrl);

}

function toggleRate(elem) {
    if (playing) {
        wavesurfer.pause();
    }

    if ($(elem).hasClass('active')) {
        $(elem).removeClass('active');
        wavesurfer.setPlaybackRate(1);
    }
    else {
        $(elem).addClass('active');
        wavesurfer.setPlaybackRate(0.8);
    }
}

function toggleLoop(elem) {

    if (playing) {
        wavesurfer.pause();
    }

    if ($(elem).hasClass('active')) {
        $(elem).removeClass('active');
        loop = false;
    }
    else {
        $(elem).addClass('active');
        loop = true;
    }
}
// ======================================================================================================== //
// DOCUMENT READY END
// ======================================================================================================== //


// ======================================================================================================== //
// HELP MODAL FUNCTIONS
// ======================================================================================================== //
/**
 * play the region (<audio> element) and loop if needed
 * Uses an <audio> element because we might need playback rate modification without changing the pitch of the sound
 * Wavesurfer can't do that for now
 * @param {float} start
 * @param {float} end
 */
function playHelp(start, end, loop, rate) {
    helpAudioPlayer = document.getElementsByTagName("audio")[0];   
    helpAudioPlayer.loop = loop;
    if (rate) {
        helpAudioPlayer.playbackRate = 0.8;
    }
    else {
        helpAudioPlayer.playbackRate = 1;
    }
    helpAudioPlayer.addEventListener('timeupdate', function () {
        // console.log(helpAudioPlayer.currentTime);
        if (helpAudioPlayer.currentTime >= end) {
            helpAudioPlayer.pause();
            helpAudioPlayer.currentTime = start;
            if (helpAudioPlayer.loop) {
                console.log('yes i want to loop!');
                helpAudioPlayer.play();
            }
            else {
                helpIsPlaying = false;
            }
        }

    });


    if (helpIsPlaying) {
        helpAudioPlayer.pause();
        helpIsPlaying = false;
    }
    else {
        helpAudioPlayer.currentTime = start;
        helpAudioPlayer.play();
        helpIsPlaying = true;
    }
}

// ======================================================================================================== //
// HELP MODAL FUNCTIONS END
// ======================================================================================================== //

