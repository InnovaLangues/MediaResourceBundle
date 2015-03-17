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
var helpPlaybackLoop = false;
var helpPlaybackRate = 1;
var helpPlaybackBackward = false;
var helpIsPlaying = false;
var helpAudioPlayer;
var helpCurrentWRegion;

var wavesurferOptions = {
    container: '#waveform',
    waveColor: '#172B32',
    progressColor: '#00A1E5',
    height: 256,
    scrollParent: true,
    normalize: true,
    minimap: true
};

/* SOME ACTIONS PLAY / PAUSE, BACKWARD / FORWARD, MARK, VIDEO FULLSCREEN, HIDE / SHOW TEXT | MEDIAS | VIDEO | WAVEFORM */
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
        console.log('mark');

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
            //addRegionToDom(toAdd);
            domUtils.addRegionToDom(wavesurfer, wavesurferUtils, toAdd);
        }
    },
    help: function () {
        // get current wavesurfer region
        helpCurrentWRegion = wavesurferUtils.getCurrentRegion(wavesurfer, wavesurfer.getCurrentTime() + 0.1);
        var wNextRegion = wavesurferUtils.getNextRegion(wavesurfer, wavesurfer.getCurrentTime());
        var wPrevRegion = wavesurferUtils.getPrevRegion(wavesurfer, wavesurfer.getCurrentTime());

        // get current region row text


        // get region config (hidden inputs)

        // open modal
        var hModal = domUtils.openRegionHelpModal(helpCurrentWRegion, audioUrl);

        hModal.on('shown.bs.modal', function () {
            console.log('modal open');
        });

        hModal.on('hidden.bs.modal', function () {
            console.log('modal close');
            helpPlaybackLoop = false;
            helpPlaybackRate = 1;
            helpPlaybackBackward = false;
            helpIsPlaying = false;
        });

        hModal.modal("show");
    }
};

/* ON READY */
$(document).ready(function () {
    // get some hidden inputs usefull values
    currentExerciseType = 'audio';
    audioUrl = $('input[name="audio-url"]').val();
    isEditing = parseInt($('input[name="editing"]').val()) === 1 ? true : false;

    // bind data-action events
    $("button[data-action]").click(function () {
        var action = $(this).data('action');
        if (actions.hasOwnProperty(action)) {
            actions[action]($(this));
        }
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
            //updateHiddenNoteOrTitleInput($this);
            domUtils.updateHiddenNoteOrTitleInput($this);
        }
        return $this;
    }).on('blur', '[contenteditable]', function (e) {
        isInRegionNoteRow = false;
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
            console.log('no region creating a new one');
            // if no region : add one by default
            var region = addRegion(0.0, wavesurfer.getDuration(), '', false);
            // addRegionToDom(region);
            domUtils.addRegionToDom(wavesurfer, wavesurferUtils, region);
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
        /* if ('video' === currentExerciseType) {
         var currentTime = wavesurfer.getCurrentTime();
         videoPlayer.currentTime = currentTime;
         
         // highlight correspondig region ROW
         var wRegion = wavesurferUtils.getCurrentRegion(wavesurfer, currentTime + 0.1);
         highlightRegionRow(wRegion);
         }*/
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

            previousResizedRegion = wavesurferUtils.getPrevRegion(wavesurfer, region.start - 0.1);
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

    /* FORM */
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

// help modal function

function playHelp(start, end) {
    //$('#help-audio-player');
    console.log('start ' + start);
    console.log('end ' + end);
    helpAudioPlayer = document.getElementsByTagName("audio")[0];
    helpAudioPlayer.addEventListener('timeupdate', function () {
        // console.log(helpAudioPlayer.currentTime);
        if (helpAudioPlayer.currentTime >= end) {
            helpAudioPlayer.pause();
            helpAudioPlayer.currentTime = start;
            if (helpPlaybackLoop) {
                helpAudioPlayer.play();
            }
            else {
                helpIsPlaying = false;
            }
        }

    });
    helpAudioPlayer.loop = helpPlaybackLoop;
    helpAudioPlayer.playbackRate = helpPlaybackRate;

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

function setPlaybackRate(elem, value) {
    helpPlaybackRate = value;

    $('.modal-body').find('.btn-group > .btn').each(function () {
        $(this).removeClass('active');
    });
    $(elem).addClass('active');

    if (helpIsPlaying && helpAudioPlayer) {
        helpAudioPlayer.pause();
        helpIsPlaying = false;
    }
}

function toggleLoopPlayback(elem) {
    helpPlaybackLoop = !helpPlaybackLoop;
    console.log(helpPlaybackLoop);

    if (helpPlaybackLoop) {
        $(elem).addClass('active');
    }
    else {
        $(elem).removeClass('active');
    }
    if (helpIsPlaying && helpAudioPlayer) {
        helpAudioPlayer.pause();
        helpIsPlaying = false;
    }
}

// will only work with chrome browser
function playBackward() {
    console.log("plop");
    if (helpIsPlaying && helpAudioPlayer) {
        helpAudioPlayer.pause();
        helpIsPlaying = false;
    }
    if (window.SpeechSynthesisUtterance === undefined) {
        console.log('not supported!');
    } else {
        var utterance = new SpeechSynthesisUtterance();
        var text = strUtils.removeHtml(helpCurrentWRegion.data.note);
        var array = text.split(' ');
        var start = array.length - 1;
        handleUtterancePlayback(start, utterance, array);
    }
}

function sayIt(utterance, callback) {

    window.speechSynthesis.speak(utterance);
    // utterance.text = reversed;
    utterance.onend = function (event) {        
        return  callback();
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

// keyboard evnet media shortcuts
/*document.addEventListener("keydown", function (e) {
 if (!isInRegionNoteRow) {
 // Enter key pressed
 if (isEditing && e.keyCode === 13) {
 actions['mark']();
 }
 // spacebar
 else if (e.keyCode === 32) {
 // don't play if we are editing text
 actions['play']();
 }
 // left arrow
 else if (e.keyCode === 37) {
 actions['backward']();
 }
 // right arrow
 else if (e.keyCode === 39) {
 actions['forward']();
 }
 }
 
 }, false);*/

/**
 * Highlight a row 
 * @param region wavesurfer.region 
 */
/*
 function highlightRegionRow(region) {
 var row = getRegionRow(region.start + 0.1, region.end - 0.1);
 if (row) {
 $('.active-row').each(function () {
 $(this).removeClass('active-row');
 });
 $(row).find('div.text-left.note').addClass('active-row');
 }
 }*/

/**
 * Get the wavesurfer region associatied row (ie DOM object)
 * @param start
 * @param end
 * @returns the row
 */
/*
 function getRegionRow(start, end) {
 var row;
 $('.row.form-row.region').each(function () {
 var temp = $(this);
 var sinput = $(this).find("input.hidden-start");
 var einput = $(this).find("input.hidden-end");
 if (start && end && parseFloat(sinput.val()) <= parseFloat(start) && parseFloat(einput.val()) >= parseFloat(end)) {
 row = temp;
 }
 else if (!end && start && parseFloat(sinput.val()) === parseFloat(start)) {
 row = temp;
 }
 else if (!start && end && parseFloat(einput.val()) === parseFloat(end)) {
 row = temp;
 }
 });
 return row;
 }
 */

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
    region.color = wavesurferUtils.randomColor(0.1);
    region.resize = isEditing;
    region.resizeHandlerColor = '#FF0000';
    region.resizeHandlerWidth = '2px';
    region.drag = false;
    region.showEndHandler = false;
    region.data = {note: note};
    region = wavesurfer.addRegion(region);
    // set data-id to del button
    if (dataset) {
        console.log('dataset');
        //var regionRow = getRegionRow(start, end);

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
        bootbox.alert('just one default region can not delete');
    }
    else {
        // remove from wavesurfer regions list
        var id = $(elem).data('id');

        if (id) {
            var toRemove = wavesurfer.regions.list[id];
            bootbox.confirm("êtes vous sur de vouloir supprimer la région?", function (result) {
                if (result) {
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
                            var currentRow = $('button.' + id).closest(".row.form-row.region");
                            var hiddenInputToUpdate = currentRow.next().find("input.hidden-start");
                            hiddenInputToUpdate.val(start);

                            var divToUpdate = currentRow.next().find(".time-text.start");
                            divToUpdate.text(wavesurferUtils.secondsToHms(start));

                            $(currentRow).remove();
                        } else {
                            console.log('not found');
                        }
                    } else { // all other cases
                        // update previous wavesurfer region (will automatically update the dom ??)
                        var previous = wavesurferUtils.getPrevRegion(wavesurfer, start - 0.1);

                        if (previous) {
                            previous.update({
                                end: end
                            });
                            wavesurfer.regions.list[id].remove();

                            // update time (DOM)
                            var currentRow = $('button.' + id).closest(".row.form-row.region");
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


function configRegion(elem) {

    domUtils.openConfigRegionModal(elem);
    /*
     // find region config hidden inputs
     //help-region-id -> problem is that the id might not exist (for newly created regions)
     var helpRegionId = $(elem).closest('div.region').find('.hidden-config-help-region-id');
     //loop elem
     var loop = $(elem).closest('div.region').find('.hidden-config-loop');
     //backward
     var backward = $(elem).closest('div.region').find('.hidden-config-backward'); //$('input[name=backward]').is(':checked');
     //rate
     var rate = $(elem).closest('div.region').find('.hidden-config-rate');
     //text
     var text = $(elem).closest('div.region').find('.hidden-config-text');
     
     var html = '<div class="row">';
     html += '<div class="col-md-12">';
     html += '<div class="form-horizontal">';
     html += '<div class="form-group">';
     html += '<label class="col-md-4 control-label" for="has-loop">Autoriser la lecture en boucle</label>';
     if (loop.val() === '1')
     html += '<input type="checkbox" name="loop" class="checkbox" value="loop" checked>';
     else
     html += '<input type="checkbox" name="loop" class="checkbox" value="loop">';
     html += '</div>';
     html += '<div class="form-group">';
     html += '<label class="col-md-4 control-label" for="has-backward">Autoriser la lecture en backward building</label>';
     if (backward.val() === '1')
     html += '<input type="checkbox" name="backward" class="checkbox" value="backward" checked>';
     else
     html += '<input type="checkbox" name="backward" class="checkbox" value="backward">';
     html += '</div>';
     html += '<div class="form-group">';
     html += '<label class="col-md-4 control-label" for="has-rate">Autoriser le changement de la vitesse de lecture</label>';
     if (rate.val() === '1')
     html += '<input type="checkbox" name="rate" class="checkbox" value="rate" checked>';
     else
     html += '<input type="checkbox" name="rate" class="checkbox" value="rate">';
     html += '</div>';
     html += '<div class="form-group">';
     html += '<label class="col-md-4 control-label" for="has-rate">Texte d\'aide:</label>';
     html += '<input type="text" name="help-text" class="" value="' + text.val() + '">';
     html += '</div>';
     html += '</div>';
     html += '</div>';
     html += '</div>';
     
     bootbox.dialog({
     title: "Configurer la région:",
     message: html,
     buttons: {
     success: {
     label: "Sauvegarder",
     className: "btn-success",
     callback: function () {
     // get form values
     var helpText = $('input[name=help-text]').val();
     var hasLoop = $('input[name=loop]').is(':checked');
     var hasBackward = $('input[name=backward]').is(':checked');
     var hasRate = $('input[name=rate]').is(':checked');
     // set proper hidden inputs values
     text.val(helpText);
     rate.val(hasRate ? '1' : '0');
     backward.val(hasBackward ? '1' : '0');
     loop.val(hasLoop ? '1' : '0');
     }
     }
     }
     });
     */
}

/**
 * Add the region to the DOM at the right place
 * @param region wavesurfer.region 
 */
/*
 function addRegionToDom(region) {
 var container = $('.regions-container');
 // HTML to append
 var html = '<div class="row form-row region">';
 // start input
 html += '<div class="col-xs-1">';
 html += '<div class="time-text start">' + wavesurferUtils.secondsToHms(region.start) + '</div>';
 html += '</div>';
 // end input
 html += '<div class="col-xs-1">';
 html += '<div class="time-text end">' + wavesurferUtils.secondsToHms(region.end) + '</div>';
 html += '</div>';
 // text input
 if (isEditing) {
 html += '<div class="col-xs-8">';
 html += '<div onclick="goTo(' + region.start + ');" contenteditable="true" class="text-left note">' + region.data.note + '</div>';
 }
 else {
 html += '<div class="col-xs-10">';
 html += '<div onclick="goTo(' + region.start + ');"  class="text-left note">' + region.data.note + '</div>';
 }
 //html += '<input type="text" name="note" class="form-control" value="' + region.data.note + '">';
 html += '</div>';
 if (isEditing) {
 // delete button
 html += '<div class="col-xs-2">';
 html += '<div class="btn-group" role="group">';
 html += '<button role="button" type="button" class="btn btn-default fa fa-cog" title="configurer la region." onclick="configRegion(this);"> </button>';
 html += '<button type="button" name="del-region-btn" class="btn btn-danger fa fa-trash-o ' + region.id + '" data-id="' + region.id + '" title="supprimer la region." onclick="deleteRegion(this)"></button>';
 html += '</div>';
 html += '</div>';
 html += '<input type="hidden" class="hidden-start" name="start[]" value="' + region.start + '" required="required">';
 html += '<input type="hidden" class="hidden-end" name="end[]" value="' + region.end + '" required="required">';
 html += '<input type="hidden" class="hidden-note" name="note[]" value="' + region.data.note + '">';
 html += '<input type="hidden" class="hidden-region-id" name="region-id[]" value="" >';
 
 html += '<input type="hidden" class="hidden-config-help-region-id" name="help-region-id[]" value="" >';
 html += '<input type="hidden" class="hidden-config-loop" name="loop[]" value="0" >';
 html += '<input type="hidden" class="hidden-config-backward" name="backward[]" value="0" >';
 html += '<input type="hidden" class="hidden-config-rate" name="rate[]" value="0" >';
 html += '<input type="hidden" class="hidden-config-text" name="text[]" value="" >';
 //html += '</div>';
 }
 // find the previous row in order to happend the new one in the good place
 if (Object.keys(wavesurfer.regions.list).length > 1) {
 var previous = findPreviousRegionRow(region.start);
 if (previous) {
 $(html).insertAfter(previous);
 }
 else {
 console.log('previous not found');
 }
 }
 else {
 console.log('insert at end');
 $(container).append(html);
 }
 }
 */
/**
 * Find the row after which we have to insert the new one
 * @param start 
 * @returns DOM Object the row
 */
/*
 function findPreviousRegionRow(start) {
 var elem = null;
 $('.region').each(function () {
 if (parseFloat($(this).find('input.hidden-end').val()) === parseFloat(start)) {
 elem = $(this);
 }
 });
 return elem ? elem[0] : null;
 }*/