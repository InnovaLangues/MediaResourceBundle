// INNOVA JAVASCRIPT HELPERS /OBJECTS
var strUtils;
var wavesurferUtils;
var javascriptUtils;
var domUtils;

// VARS
var transitionType = 'fast';
var currentExerciseType = '';
var audioUrl = '';
var wavesurfer;
var playing = false;
var isEditing = false;
var isInRegionNoteRow = false; // for keyboard event listener, if we are editing a region note row, we don't want the enter keyboard to add a region marker

var isResizing = false;
var currentlyResizedRegion = null;
var currentlyResizedRegionRow = null;
var originalStart;
var originalEnd;
var previousResizedRegion = null;
var previousResizedRegionRow = null;

// current help options
var helpPlaybackBackward = false;
var helpIsPlaying = false;
var helpAudioPlayer;
var helpCurrentWRegion; // the wavesurfer region where we are when asking help
var helpPreviousWRegion; // the previous wavesurfer region relatively to helpCurrentWRegion
var currentHelpRelatedRegion; // the related help region; 
var currentHelpTextLevel = 0;
var hModal;

var utterance; // global SpeechSynthesisUtterance instance;

var wavesurferOptions = {
    container: '#waveform',
    waveColor: '#172B32',
    progressColor: '#00A1E5',
    height: 256,
    scrollParent: true,
    normalize: true,
    minimap: true
};



// ======================================================================================================== //
// ACTIONS BOUND WHEN DOM READY (PLAY / PAUSE, MOVE BACKWARD / FORWARD, ADD MARKER, CALL HELP, ANNOTATE)
// ======================================================================================================== //
var actions = {
    play: function () {
        if (!playing) {
            wavesurfer.play();
            playing = true;
        }
        else {
            wavesurfer.pause();
            playing = false;
        }
    },
    backward: function () {
        if (Object.keys(wavesurfer.regions.list).length > 1) {
            var current = wavesurferUtils.getCurrentRegion(wavesurfer, wavesurfer.getCurrentTime() - 0.1);
            goTo(current ? current.start : 0);
        }
        else {
            wavesurfer.seekAndCenter(0);
        }
        var region = wavesurferUtils.getCurrentRegion(wavesurfer, wavesurfer.getCurrentTime() - 0.1);
        if (region) {
            domUtils.highlightRegionRow(region);//highlightRegionRow(region);
        }
    },
    forward: function () {
        if (Object.keys(wavesurfer.regions.list).length > 1) {
            var current = wavesurferUtils.getCurrentRegion(wavesurfer, wavesurfer.getCurrentTime() + 0.1);
            goTo(current ? current.end : 1);
        }
        else {
            wavesurfer.seekAndCenter(1);
        }
        var region = wavesurferUtils.getCurrentRegion(wavesurfer, wavesurfer.getCurrentTime() + 0.1);
        if (region) {
            domUtils.highlightRegionRow(region);//highlightRegionRow(region);
        }
    },
    mark: function () {
        // BEWARE !!! We only show the begin region handler in order to avoid marker(s) overlaps
        // (endmarker time = next region start marker time)

        // if one or more region(s) (always true because a default region is created at startup)
        if (!jQuery.isEmptyObject(wavesurfer.regions.list)) {
            var time = wavesurfer.getCurrentTime();
            var current = wavesurferUtils.getCurrentRegion(wavesurfer, time);
            var savedEnd = current.end;
            var idToUpdate = current.id;
            // Update current wavesurfer region
            current.update({
                end: time
            });

            // update DOM
            // hidden input
            var hiddenInput = $('button.' + idToUpdate).closest(".row").find("input.hidden-end");
            hiddenInput.val(time);
            // visible end value
            var endTimeDisplay = $('button.' + idToUpdate).closest(".row").find("div.time-text.end");
            endTimeDisplay.text(wavesurferUtils.secondsToHms(time));
            // ADD new region to DOM in the right place
            var toAdd = addRegion(time, savedEnd, '', false);
            var guid = strUtils.createGuid();
            domUtils.addRegionToDom(wavesurfer, wavesurferUtils, toAdd, guid);
        }
    },
    help: function () {
        // get current wavesurfer region
        helpCurrentWRegion = wavesurferUtils.getCurrentRegion(wavesurfer, wavesurfer.getCurrentTime() + 0.1);
        // get previous
        helpPreviousWRegion = wavesurferUtils.getPrevRegion(wavesurfer, wavesurfer.getCurrentTime() + 0.1);

        // open modal
        hModal = domUtils.openRegionHelpModal(helpCurrentWRegion, helpPreviousWRegion, audioUrl);

        hModal.on('shown.bs.modal', function () {

            // by default the current region is selected so we append to modal help tab the current region help options
            var currentDomRow = domUtils.getRegionRow(helpCurrentWRegion.start + 0.1, helpCurrentWRegion.end - 0.1);
            var config = domUtils.getRegionRowHelpConfig(currentDomRow);
            domUtils.appendHelpModalConfig(hModal, config, helpCurrentWRegion);

            // listen to tab click event
            $('#help-tab-panel a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            })
        });

        hModal.on('hidden.bs.modal', function () {
            helpPlaybackLoop = false;
            helpPlaybackRate = 1;
            helpPlaybackBackward = false;
            helpIsPlaying = false;
            utterance = null;
            helpPreviousWRegion = null;
            helpCurrentWRegion = null;
            hModal = null;
        });

        hModal.modal("show");
    },
    annotate: function (elem) {
        var color = elem.data('color');
        var text = javascriptUtils.getSelectedText();
        if (text !== '') {
            manualTextAnnotation(text, 'accent-' + color);
        }
    }
};

// ======================================================================================================== //
// ACTIONS BOUND WHEN DOM READY END
// ======================================================================================================== //

// ======================================================================================================== //
// DOCUMENT READY
// ======================================================================================================== //
$(document).ready(function () {
    // get some hidden inputs usefull values
    currentExerciseType = 'audio';
    audioUrl = $('input[name="audio-url"]').val();
    isEditing = parseInt($('input[name="editing"]').val()) === 1 ? true : false;

    // color config region buttons if needed
    toggleConfigButtonColor();

    // bind data-action events
    $("button[data-action]").click(function () {
        var action = $(this).data('action');
        if (actions.hasOwnProperty(action)) {
            actions[action]($(this));
        }
    });

    /* SWITCHES INPUTS */

    var toggleAnnotationCheck = $("[name='toggle-annotation-checkbox']").bootstrapSwitch('state', true);
    $(toggleAnnotationCheck).on('switchChange.bootstrapSwitch', function (event, state) {
        $('.annotation-buttons-container').toggle(transitionType);
        $(this).trigger('blur'); // remove focus to avoid spacebar interraction
    });


    // CONTENT EDITABLE CHANGE EVENT MAPPING
    $('body').on('focus', '[contenteditable]', function () {
        var $this = $(this);
        $this.data('before', $this.html());
        // when focused skip to the start of the region
        var start = $(this).closest(".row.form-row.region").find('input.hidden-start').val();
        goTo(start);
        isInRegionNoteRow = true;
        return $this;
    }).on('blur keyup paste input', '[contenteditable]', function (e) {
        var $this = $(this);
        if ($this.data('before') !== $this.html()) {
            $this.data('before', $this.html());
            $this.trigger('change');
            domUtils.updateHiddenNoteOrTitleInput($this);
        }
        return $this;
    }).on('blur', '[contenteditable]', function (e) {
        isInRegionNoteRow = false;
    });

    // HELP MODAL EVENTS ATTACHED TO THE BODY
    $('body').on('change', 'input[name=segment]:radio', function (e) {
        if (e.target.value === 'previous') {
            var currentDomRow = domUtils.getRegionRow(helpPreviousWRegion.start + 0.1, helpPreviousWRegion.end - 0.1);
            var config = domUtils.getRegionRowHelpConfig(currentDomRow);
            domUtils.appendHelpModalConfig(hModal, config, helpPreviousWRegion);
        }
        else if (e.target.value === 'current') {
            var currentDomRow = domUtils.getRegionRow(helpCurrentWRegion.start + 0.1, helpCurrentWRegion.end - 0.1);
            var config = domUtils.getRegionRowHelpConfig(currentDomRow);
            domUtils.appendHelpModalConfig(hModal, config, helpCurrentWRegion);
        }
    });
    
    $('body').on('click', '#btn-show-help-text', function (e) {
        
    });




    /* JS HELPERS */
    strUtils = Object.create(StringUtils);
    wavesurferUtils = Object.create(WavesurferUtils);
    javascriptUtils = Object.create(JavascriptUtils);
    domUtils = Object.create(DomUtils);
    /* /JS HELPERS */

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

    wavesurfer.load(audioUrl);

    wavesurfer.on('ready', function () {
        var timeline = Object.create(WaveSurfer.Timeline);
        timeline.init({
            wavesurfer: wavesurfer,
            container: '#wave-timeline'
        });

        // check if there are regions defined
        if ($(".row.form-row.region").size() === 0) {
            // if no region : add one by default
            var region = addRegion(0.0, wavesurfer.getDuration(), '', false);
            var guid = strUtils.createGuid();
            domUtils.addRegionToDom(wavesurfer, wavesurferUtils, region, guid);
        } else {
            // for each existing PHP Region entity ( = region row) create a wavesurfer region
            $(".row.form-row.region").each(function () {
                var start = $(this).find('input.hidden-start').val();
                var end = $(this).find('input.hidden-end').val();
                var note = $(this).find('input.hidden-note').val() ? $(this).find('input.hidden-note').val() : '';
                if (start && end) {
                    addRegion(start, end, note, true);
                }
            });
        }
    });

    wavesurfer.on('seek', function () {

    });

    wavesurfer.on('region-click', function (region, e) {
        //highlightRegionRow(region);
        domUtils.highlightRegionRow(region);
    });

    wavesurfer.on('region-in', function (region) {
        //highlightRegionRow(region);
        domUtils.highlightRegionRow(region);
    });

    if (isEditing) {
        // catch region resize start to store some data
        wavesurfer.on('region-resize-start', function (region, e) {
            isResizing = true;
            currentlyResizedRegion = region;
            //currentlyResizedRegionRow = getRegionRow(region.start + 0.1, region.end - 0.1);
            currentlyResizedRegionRow = domUtils.getRegionRow(region.start + 0.1, region.end - 0.1);

            previousResizedRegion = wavesurferUtils.getPrevRegion(wavesurfer, region.start + 0.1);
            //previousResizedRegionRow = getRegionRow(previousResizedRegion.start, previousResizedRegion.end);
            previousResizedRegionRow = domUtils.getRegionRow(previousResizedRegion.start, previousResizedRegion.end);
            originalStart = region.start;
            originalEnd = region.end;
        });

        // when ending the region update check some data
        wavesurfer.on('region-update-end', function (region, e) {
            // if we are resizing, we need to check if data are OK
            if (isResizing) {

                // reinit  currently resized region if datas are wrong
                if (parseFloat(region.start) >= parseFloat(originalEnd)) {
                    region.update({
                        start: originalStart,
                        end: originalEnd
                    });
                    previousResizedRegion.update({end: originalStart});
                    // hidden input update
                    $(currentlyResizedRegionRow).find("input.hidden-start").val(originalStart);
                    //diplay div update
                    $(currentlyResizedRegionRow).find(".time-text.start").text(wavesurferUtils.secondsToHms(originalStart));
                    if (previousResizedRegionRow) {
                        $(previousResizedRegionRow).find("input.hidden-end").val(originalStart);
                        $(previousResizedRegionRow).find(".time-text.end").text(wavesurferUtils.secondsToHms(originalStart));
                    }
                }
                isResizing = false;
                currentlyResizedRegion = null;
                previousResizedRegion = null;
            }
        });

        // while dragging update values (don't forget we are changing the start of the current region and the end of the previous one)
        wavesurfer.on('region-resize', function (region, e) {
            if (isResizing) {
                var currentTime = region.start;
                $(currentlyResizedRegionRow).find("input.hidden-start").val(currentTime);
                $(currentlyResizedRegionRow).find(".time-text.start").text(wavesurferUtils.secondsToHms(currentTime));
                if (previousResizedRegionRow) {
                    $(previousResizedRegionRow).find("input.hidden-end").val(currentTime);
                    $(previousResizedRegionRow).find(".time-text.end").text(wavesurferUtils.secondsToHms(currentTime));
                    previousResizedRegion.update({end: currentTime});
                }
            }
        });
    }
    /* /WAVESURFER */

    /* FORM SUBMIT */
    $('#media_resource_form').on('submit', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var type = $(this).attr('method');
        var data = $(this).serialize();
        $.ajax({
            url: url,
            type: type,
            data: data,
            success: function (response) {
                bootbox.alert(response);
            }
        });
    });

});
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
    else{
        helpAudioPlayer.playbackRate = 1;
    }
    helpAudioPlayer.addEventListener('timeupdate', function () {
        if (helpAudioPlayer.currentTime >= end) {
            helpAudioPlayer.pause();
            helpAudioPlayer.currentTime = start;
            if (helpAudioPlayer.loop) {
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
/**
 * Allow the user to play the help related region
 * @param {float} start
 */
function playHelpRelatedRegion(start) {
    playRegionFrom(start + 0.1);
}

/**
 * Will only work with chrome browser !!
 * Called by HelpModal play backward button
 */
function playBackward() {
    // is playing for real audio (ie not for TTS)
    if (helpIsPlaying && helpAudioPlayer) {
        // stop audio playback before playing TTS
        helpAudioPlayer.pause();
        helpIsPlaying = false;
    }
    if (window.SpeechSynthesisUtterance === undefined) {
        console.log('not supported!');
    } else {
        // create an instance only if needed!
        if (!utterance) {
            utterance = new SpeechSynthesisUtterance();
        }
        var row = domUtils.getRegionRow(helpCurrentWRegion.start + 0.1, helpCurrentWRegion.end - 0.1);
        var text = strUtils.removeHtml($(row).find('input.hidden-note').val());
        var array = text.split(' ');
        var start = array.length - 1;
        // check if utterance is already speaking before playing (pultiple click on backward button)
        if (!window.speechSynthesis.speaking) {
            handleUtterancePlayback(start, utterance, array);
        }
    }
}

function sayIt(utterance, callback) {
    window.speechSynthesis.speak(utterance);
    utterance.onend = function (event) {
        return callback();
    };
}

function handleUtterancePlayback(index, utterance, textArray) {
    var toSay = '';
    var length = textArray.length;
    for (j = index; j < length; j++) {
        toSay += textArray[j] + ' ';
    }
    utterance.text = toSay;

    if (index >= 0) {
        sayIt(utterance, function () {
            index = index - 1;
            handleUtterancePlayback(index, utterance, textArray);
        });
    }
}

// ======================================================================================================== //
// HELP MODAL FUNCTIONS END
// ======================================================================================================== //


// ======================================================================================================== //
// CONFIG REGION MODAL FUNCTIONS
// ======================================================================================================== //
/**
 * Open config modal
 * @param the source of the event (button)
 */
function configRegion(elem) {
    var configModal = domUtils.openConfigRegionModal(elem, wavesurfer, wavesurferUtils);

    if (playing) {
        wavesurfer.pause();
        playing = false;
    }

    configModal.on('shown.bs.modal', function () {

    });

    configModal.on('hidden.bs.modal', function () {
        currentHelpRelatedRegion = null;
        if (playing) {
            wavesurfer.pause();
            playing = false;
        }
        
        // color the config button if any value in config parameters
        toggleConfigButtonColor();
    });

    configModal.modal("show");
}

function checkIfRowHasConfigValue(row) {
    var helpRegion = $(row).find('.hidden-config-help-region-uuid').val() !== '' ? true : false;
    var loop = $(row).find('.hidden-config-loop').val() === '1' ? true : false;
    var backward = $(row).find('.hidden-config-backward').val() === '1' ? true : false;
    var rate = $(row).find('.hidden-config-rate').val() === '1' ? true : false;
    var text = $(row).find('.hidden-config-text').val() !== '' ? true : false;
    return helpRegion || loop || backward || rate || text;
}
/**
 * Called by ConfigModal <select> element
 * @param {type} elem the source of the event
 */
function onSelectedRegionChange(elem) {
    var idx = elem.selectedIndex;
    var val = elem.options[idx].value;
    var wRegionId = $('#' + val).find('button.btn-danger').data('id');
    currentHelpRelatedRegion = wavesurfer.regions.list[wRegionId];
    if (playing) {
        wavesurfer.pause();
        playing = false;
    }
}

/**
 * Allow the user to listen to the selected help related region while configuring help
 */
function previewHelpRelatedRegion() {
    if (currentHelpRelatedRegion) {
        playRegionFrom(currentHelpRelatedRegion.start + 0.1);
    }
}

// ======================================================================================================== //
// CONFIG REGION MODAL FUNCTIONS END
// ======================================================================================================== //


// ======================================================================================================== //
// OTHER MIXED FUNCTIONS
// ======================================================================================================== //

/**
 * put the wavesurfer play cursor at the given time and pause playback
 * @param time in seconds
 */
function goTo(time) {
    var percent = (time) / wavesurfer.getDuration();
    wavesurfer.seekAndCenter(percent);
    if (playing) {
        wavesurfer.pause();
        playing = false;
    }
}

/**
 * Crate and add a wavesurfer region
 * dataset is true only when we are creating a wavesurfer region from existing DOM rows
 * @param start
 * @param end
 * @param note
 * @param dataset
 * @returns region the newly created wavesurfer region
 */
function addRegion(start, end, note, dataset) {

    note = note ? note : '';
    var region = {};
    region.start = start;
    region.end = end;
    // do not set different colors per region if we are not editing
    region.color = isEditing ? wavesurferUtils.randomColor(0.1): 'rgba(0, 0, 0, 0.0)';
    // do not allow region resize if we are not editing
    region.resize = isEditing;
    region.resizeHandlerColor = '#FF0000';
    region.resizeHandlerWidth = '2px';
    // do not show region handlers if we are not editing
    region.showStartHandler = isEditing ? true:false;
    region.drag = false;
    region.showEndHandler = false;
    region.data = {note: note};
    region = wavesurfer.addRegion(region);
    // set data-id to del button
    if (dataset) {
        var regionRow = domUtils.getRegionRow(start, end);
        var btn = $(regionRow).find('button.fa-trash-o');
        $(btn).addClass(region.id);
        $(btn).attr('data-id', region.id);
    }
    return region;
}

/**
 * Delete a region from the wavesurfer collection remove it from DOM and update times (start or end)
 * @param elem the source of the event
 */
function deleteRegion(elem) {

    // can not delete region if just one ( = the default one)
    if (!jQuery.isEmptyObject(wavesurfer.regions.list) && Object.keys(wavesurfer.regions.list).length === 1) {
        bootbox.alert(Translator.trans('alert_only_one_region_left', {}, 'media_resource'));
    }
    else {
        // remove from wavesurfer regions list
        var id = $(elem).data('id');

        if (id) {
            var toRemove = wavesurfer.regions.list[id];
            var currentRow = $('button.' + id).closest(".region");
            var regionUuid = $(currentRow).attr('id');
            var usedInHelp = domUtils.getRegionsUsedInHelp(regionUuid);
            var message = "<strong>" + Translator.trans('region_delete_confirm_base', {}, 'media_resource') + "</strong>";
            if (usedInHelp.length > 0) {
                message += '<hr/><div class="text-center"> ' + Translator.trans('region_delete_confirm_sub', {}, 'media_resource') + '</div>';
            }

            bootbox.confirm(message, function (result) {
                if (result) {
                    // remove help reference(s) if needed
                    if (usedInHelp.length > 0) {
                        for (var index = 0; index < usedInHelp.length; index++) {
                            var elem = usedInHelp[index];
                            // reset element value
                            $(elem).val('');
                        }
                    }

                    var start = toRemove.start;                   
                    var end = toRemove.end;
                    // if we are deleting the first region
                    if (start === 0) {
                        var next = wavesurferUtils.getNextRegion(wavesurfer, end - 0.1);
                        if (next) {
                            next.update({
                                start: 0
                            });
                            wavesurfer.regions.list[id].remove();

                            // update time (DOM)                           
                            var hiddenInputToUpdate = currentRow.next().find("input.hidden-start");
                            hiddenInputToUpdate.val(start);

                            var divToUpdate = currentRow.next().find(".time-text.start");
                            divToUpdate.text(wavesurferUtils.secondsToHms(start));
                            
                            // remove segment DOM row
                            $(currentRow).remove();
                            
                            // the "marker" is visible but not draggable... need to hide it
                            // sometimes it is not the first one...
                           
                            $('region.wavesurfer-region').each(function(){
                                var title = $(this).attr('title');
                                if(title.indexOf('0:00') !== -1){
                                    var $marker = $(this).find('handle').first();
                                    $marker.hide();
                                }
                                
                            });
                            
                        } else {
                            console.log('not found');
                        }
                    } else { // all other cases
                        // update previous wavesurfer region (will automatically update the dom ??)
                        var previous = wavesurferUtils.getPrevRegion(wavesurfer, start + 0.1);
                        if (previous) {
                            previous.update({
                                end: end
                            });
                            wavesurfer.regions.list[id].remove();

                            // update time (DOM)                           
                            var hiddenInputToUpdate = currentRow.prev().find("input.hidden-end");
                            hiddenInputToUpdate.val(end);
                            var divToUpdate = currentRow.prev().find(".time-text.end");
                            divToUpdate.text(wavesurferUtils.secondsToHms(end));

                            $(currentRow).remove();
                        } else {
                            console.log('not found');
                        }
                    }
                }
            });
        }
    }
}
/**
 * Called from play button on a region row
 * @param {type} elem
 * @returns {undefined}
 */
function playRegion(elem) {
    var start = parseFloat($(elem).closest('.region').find('.hidden-start').val());
    playRegionFrom(start + 0.1);
}

function playRegionFrom(start) {
    var region = wavesurferUtils.getCurrentRegion(wavesurfer, start);
    if (!playing) {
        wavesurfer.play(region.start, region.end);
        playing = true;
        region.once('out', function () {
            // force pause
            wavesurfer.pause();
            playing = false;
        });
    }
    else {
        wavesurfer.pause();
        playing = false;
    }
}


/**
 * Add a color to region config button if any config parameter found for the row
 * 
 */
function toggleConfigButtonColor() {
    $('.region').each(function () {
        if (checkIfRowHasConfigValue($(this))) {
            $(this).find('.fa.fa-cog').addClass('btn-warning').removeClass('btn-default');
        }
        else {
            $(this).find('.fa.fa-cog').removeClass('btn-warning').addClass('btn-default');
        }
    });
}

function manualTextAnnotation(text, css) {
    if (!css) {
        document.execCommand('insertHTML', false, css);
    } else {
        document.execCommand('insertHTML', false, '<span class="' + css + '">' + text + '</span>');
    }
}

// ======================================================================================================== //
//  OTHER MIXED FUNCTIONS END
// ======================================================================================================== //