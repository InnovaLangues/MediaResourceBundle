'use strict';

var DomUtils = {
    /**
     * Open help for a region
     * @param object current : current wavesurfer region
     * @param string audioUrl : current media audio url
     * @param object wInstance wavesurfer instance
     * @param object wUtilsInstance wavesurfer Utils instance
     * @returns bootbox modal object
     */
    openRegionHelpModal: function (current, audioUrl, wInstance, wUtilsInstance) {
        var domRow = this.getRegionRow(current.start + 0.1, current.end - 0.1);
        // loop available ?
        var loop = $(domRow).find('.hidden-config-loop').val() === '1' ? true : false;
        // backward available ?
        var backward = $(domRow).find('.hidden-config-backward').val() === '1' ? true : false; //$('input[name=backward]').is(':checked');
        // rate available ?
        var rate = $(domRow).find('.hidden-config-rate').val() === '1' ? true : false;
        // text
        var text = $(domRow).find('.hidden-config-text').val();
        // related help region
        var relatedRegionUuid = $(domRow).find('.hidden-config-help-region-uuid').val();

        var html = '<div class="row">';
        html += '       <div class="col-md-12 text-center">';
        html += '           <audio id="help-audio-player" src="' + audioUrl + '">'; // will not show as no controls defined
        html += '           </audio>';
        html += '           <div class="row">';
        html += '               <div class="col-md-12">';
        html += '                   <label>' + Translator.trans('region_help_segment_playback_label', {}, 'media_resource') + ':</label>';
        html += '              </div>';
        html += '           </div>';
        html += '           <div class="row">';
        html += '               <div class="col-md-12">';
        html += '                   <button class="btn btn-default" title="' + Translator.trans('region_help_segment_playback', {}, 'media_resource') + '" onclick="playHelp(' + current.start + ', ' + current.end + ')" style="margin:5px;">';
        html += '                       <i class="fa fa-play"></i> ';
        html += '                       / ';
        html += '                       <i class="fa fa-pause"></i>';
        html += '                   </button>';
        if (backward) {
            html += '               <button class="btn btn-default" title="' + Translator.trans('region_help_segment_playback_backward', {}, 'media_resource') +'" onclick="playBackward();" style="margin:5px;">';
            html += '                   <i class="fa fa-exchange"></i> ';
            html += '               </button>';
        }
        if (loop) {
            html += '               <button class="btn btn-default" title="' + Translator.trans('region_help_segment_playback_loop', {}, 'media_resource') +'"  onclick="toggleLoopPlayback(this)" style="margin:5px;">';
            html += '                   <i class="fa fa-retweet"></i> ';
            html += '               </button>';
        }
        if (rate) {
            html += '               <div class="btn-group">';
            html += '                   <button class="btn btn-default active" title="' + Translator.trans('region_help_segment_playback_rate', {}, 'media_resource') +'"  onclick="setPlaybackRate(this, 1)">x1</button>';
            html += '                   <button class="btn btn-default" title="' + Translator.trans('region_help_segment_playback_rate', {}, 'media_resource') +'"  onclick="setPlaybackRate(this, 0.8)">x0.8</button>';
            html += '                   <button class="btn btn-default" title="' + Translator.trans('region_help_segment_playback_rate', {}, 'media_resource') +'"  onclick="setPlaybackRate(this, 0.5)">x0.5</button>';
            html += '               </div>';
        }
        
        html += '               </div>';
        html += '           </div>';
        if (text !== '') {
            html += '       <hr/>';
            html += '       <label>' + Translator.trans('region_help_help_text_label', {}, 'media_resource') + ':</label>';
            html += '       <label style="margin:5px;">' + text + '</label>';
        }
        if (relatedRegionUuid) {
            html += '       <hr/>';
            html += '       <label>' + Translator.trans('region_help_play_related_region_label', {}, 'media_resource') + ':</label>';
            // we have the dom row uuid so let's find the begin and end for this row
            var helpRegionStart = this.getHelpRelatedRegionStart(relatedRegionUuid);
            html += '       <button class="btn btn-default" title="' + Translator.trans('region_help_related_segment_playback', {}, 'media_resource') + '" onclick="playHelpRelatedRegion( ' + helpRegionStart + ');" style="margin:5px;">';
            html += '           <i class="fa fa-play"></i> ';
            html += '                / ';
            html += '           <i class="fa fa-pause"></i>';
            html += '       </button>';
        }
        html += '       </div>';
        html += '</div>';

        var modal = bootbox.dialog({
            title: Translator.trans('region_help', {}, 'media_resource'), //"Aide sur la région:",
            message: html,
            show: false,
            buttons: {
                success: {
                    label: Translator.trans('close', {}, 'media_resource'),
                    className: "btn-default",
                    callback: function () {

                    }
                }
            }
        });
        return modal;
    },
    /**
     * Add the region to the DOM at the right place
     * @param region wavesurfer.region 
     */
    addRegionToDom: function (wavesurfer, wavesurferUtils, region, uuid) {
        var my = this;
        //console.log(uuid);
        var container = $('.regions-container');
        // HTML to append
        var html = '';
        html += '<div class="row form-row region" id="' + uuid + '" data-uuid="' + uuid + '">';
        // start input
        html += '       <div class="col-xs-1">';
        html += '           <div class="time-text start">' + wavesurferUtils.secondsToHms(region.start) + '</div>';
        html += '       </div>';
        // end input
        html += '       <div class="col-xs-1">';
        html += '           <div class="time-text end">' + wavesurferUtils.secondsToHms(region.end) + '</div>';
        html += '       </div>';
        // text input

        html += '       <div class="col-xs-8">';
        html += '           <div contenteditable="true" class="text-left note">' + region.data.note + '</div>';
        html += '       </div>';

        // delete button
        html += '       <div class="col-xs-2">';
        html += '           <div class="btn-group" role="group">';
        html += '               <button role="button" type="button" class="btn btn-default fa fa-cog" title="' + Translator.trans('region_config', {}, 'media_resource') + '" onclick="configRegion(this);"> </button>';
        html += '               <button type="button" name="del-region-btn" class="btn btn-danger fa fa-trash-o ' + region.id + '" data-id="' + region.id + '" title="' + Translator.trans('region_delete', {}, 'media_resource') + '" onclick="deleteRegion(this)"></button>';
        html += '           </div>';
        html += '       </div>';
        html += '       <input type="hidden" class="hidden-start" name="start[]" value="' + region.start + '" required="required">';
        html += '       <input type="hidden" class="hidden-end" name="end[]" value="' + region.end + '" required="required">';
        html += '       <input type="hidden" class="hidden-note" name="note[]" value="' + region.data.note + '">';
        html += '       <input type="hidden" class="hidden-region-id" name="region-id[]" value="" >';
        html += '       <input type="hidden" class="hidden-region-uuid" name="region-uuid[]" value="' + uuid + '" >';

        html += '       <input type="hidden" class="hidden-config-help-region-uuid" name="help-region-uuid[]" value="" >';
        html += '       <input type="hidden" class="hidden-config-loop" name="loop[]" value="0" >';
        html += '       <input type="hidden" class="hidden-config-backward" name="backward[]" value="0" >';
        html += '       <input type="hidden" class="hidden-config-rate" name="rate[]" value="0" >';
        html += '       <input type="hidden" class="hidden-config-text" name="text[]" value="" >';
        html += '</div>';

        // find the previous row in order to happend the new one in the good place
        if (Object.keys(wavesurfer.regions.list).length > 1) {
            var previous = my.findPreviousRegionRow(region.start);
            if (previous) {
                $(html).insertAfter(previous);
            }
            else {
                console.log('previous not found');
            }
        }
        else {
            $(container).append(html);
        }
    },
    /**
     * Allow author to set witch help will be available for the region
     * @param {type} elem current clicked config button
     */
    openConfigRegionModal: function (elem, w, wutils) {
        // get wavesurfer regions
        // console.log(wavesurfer.regions.list);
        var rRows = [];
        $('.region').each(function () {
            var row = {};
            row = {
                uid: $(this).data('uuid'),
                hstart: $(this).find('.time-text.start').text(),
                hend: $(this).find('.time-text.end').text(),
                start: $(this).find('input.hidden-start').val(),
                end: $(this).find('input.hidden-end').val()
            };
            rRows.push(row);

        });
        // get current region row start text
        var currentStart = $(elem).closest('div.region').find('.time-text.start').text();
        // find region config hidden inputs
        //help-region-id -> problem is that the id might not exist (for newly created regions) -> need to select a region by time ?
        var helpRegionId = $(elem).closest('div.region').find('.hidden-config-help-region-uuid');
        //loop elem
        var loop = $(elem).closest('div.region').find('.hidden-config-loop');
        //backward
        var backward = $(elem).closest('div.region').find('.hidden-config-backward'); //$('input[name=backward]').is(':checked');
        //rate
        var rate = $(elem).closest('div.region').find('.hidden-config-rate');
        //text
        var text = $(elem).closest('div.region').find('.hidden-config-text');

        var html = '';
        html += '<div class="row">';
        html += '   <div class="col-md-12">';
        html += '       <div class="form">';
        html += '           <div class="checkbox">';
        html += '               <label>';
        if (loop.val() === '1')
            html += '               <input type="checkbox" name="loop"  value="loop" checked>';
        else
            html += '               <input type="checkbox" name="loop" value="loop">';
        html += Translator.trans('region_config_allow_loop', {}, 'media_resource');//'               Autoriser la lecture en boucle';
        html += '               </label>';
        html += '           </div>';
        html += '           <div class="checkbox">';
        html += '               <label>';
        if (backward.val() === '1')
            html += '               <input type="checkbox" name="backward" value="backward" checked>';
        else
            html += '               <input type="checkbox" name="backward" value="backward">';
        html += Translator.trans('region_config_allow_bwb', {}, 'media_resource'); //'               Autoriser la lecture en backward building';
        html += '               </label>';
        html += '           </div>';
        html += '           <div class="checkbox">';
        html += '               <label>';
        if (rate.val() === '1')
            html += '               <input type="checkbox" name="rate" value="rate" checked>';
        else
            html += '               <input type="checkbox" name="rate" value="rate">';
        html += Translator.trans('region_config_allow_rate', {}, 'media_resource');//'               Autoriser le changement de la vitesse de lecture';
        html += '               </label>';
        html += '           </div>';
        html += '           <hr/>';
        // help text
        html += '           <div class="form-group">';
        html += '               <label class="col-md-4 control-label" for="has-rate">' + Translator.trans('region_config_help_text', {}, 'media_resource') + '</label>';
        html += '               <input type="text" name="help-text" style="max-width:225px;" class="form-control" value="' + text.val() + '">';
        html += '           </div>';
        html += '           <hr/>';
        // region dropdown
        html += '           <div class="form-group">';
        html += '               <label class="col-md-4 control-label" for="has-rate">' + Translator.trans('region_config_help_region_title', {}, 'media_resource') + '</label>';
        html += '               <select id="region-select" name="region" onchange="onSelectedRegionChange(this);">';
        html += '                   <option value="-1">' + Translator.trans('none', {}, 'media_resource') + '</option>';
        // loop
        for (var i = 0; i < rRows.length; i++) {
            if (currentStart !== rRows[i].hstart) {
                // wavesurfer.regions.list
                var selected = '';
                if (helpRegionId.val() === rRows[i].uid) {
                    selected = 'selected';

                    var time = parseFloat(rRows[i].start + 0.1);
                    currentHelpRelatedRegion = wutils.getCurrentRegion(w, time);
                }

                html += '           <option value="' + rRows[i].uid + '" ' + selected + '>' + rRows[i].hstart + ' - ' + rRows[i].hend + '</option>';
            }
        }
        html += '               </select>';
        html += '               <button class="btn btn-default" onclick="previewHelpRelatedRegion();" style="margin:5px;">';
        html += '               <i class="fa fa-play"></i> ';
        html += '                / ';
        html += '               <i class="fa fa-pause"></i>';
        html += '               </button>';
        html += '           </div>';
        html += '       </div>'; // end form
        html += '   </div>'; // end col
        html += '</div>'; // end row

        var modal = bootbox.dialog({
            title: Translator.trans('dialog_region_configure', {}, 'media_resource'),
            message: html,
            buttons: {
                success: {
                    label: Translator.trans('close', {}, 'media_resource'),
                    className: "btn-default",
                    show: false,
                    callback: function () {
                        // get form values
                        var helpText = $('input[name=help-text]').val();
                        var hasLoop = $('input[name=loop]').is(':checked');
                        var hasBackward = $('input[name=backward]').is(':checked');
                        var hasRate = $('input[name=rate]').is(':checked');
                        var helpId = $("#region-select").val();
                        // set proper hidden inputs values
                        text.val(helpText);
                        rate.val(hasRate ? '1' : '0');
                        backward.val(hasBackward ? '1' : '0');
                        loop.val(hasLoop ? '1' : '0');
                        if (helpId != -1)
                            helpRegionId.val(helpId);
                        else {
                            helpRegionId.val('');
                        }
                    }
                }
            }
        });
        return modal;
    },
    /**
     * get regions that are using the given regionUuid as help region
     * @param {type} regionUuid the deleted region uuid
     * @returns {Array| jQuery hidden input concerned objects}
     */
    getRegionsUsedInHelp: function (regionUuid) {
        var result = [];
        // for each region row
        $('.region').each(function () {
            // if one or more region have the hidden input setted the deleted region is used in help
            var elem = $(this).find('input.hidden-config-help-region-uuid');
            var current_help_id = $(elem).val();
            if (current_help_id == regionUuid) {
                // push the input in result array
                result.push($(elem));
            }
        });
        return result;
    },
    /**
     * For a given region uuid, find the dom row, find the region start info
     * @param string rowUuid
     * @returns region start value
     */
    getHelpRelatedRegionStart: function (rowUuid) {
        return parseFloat($('#' + rowUuid).find('.hidden-start').val());
    },
    /**
     * Find the row after which we have to insert the new one
     * @param start 
     * @returns DOM Object the row
     */
    findPreviousRegionRow: function (start) {
        var elem = null;
        $('.region').each(function () {
            if (parseFloat($(this).find('input.hidden-end').val()) === parseFloat(start)) {
                elem = $(this);
            }
        });
        return elem ? elem[0] : null;
    },
    /**
     * Get the wavesurfer region associatied row (ie DOM object)
     * @param start
     * @param end
     * @returns the row
     */
    getRegionRow: function (start, end) {
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
    },
    /**
     * Highlight a row 
     * @param region wavesurfer.region 
     */
    highlightRegionRow: function (region) {
        var row = this.getRegionRow(region.start + 0.1, region.end - 0.1);
        if (row) {
            $('.active-row').each(function () {
                $(this).removeClass('active-row');
            });
            $(row).find('div.text-left.note').addClass('active-row');
        }
    },
    /**
     * Upadte Hidden inputs values for contenteditable=true divs (ie region notes divs and title div)
     * @param {type} elem
     * @returns {undefined}
     */
    updateHiddenNoteOrTitleInput: function (elem) {
        // get last css class name of the element
        var isNote = $(elem).hasClass('note');
        if (isNote) {
            // find associated input[name="note"] input and set val
            var hiddenNoteInput = $(elem).closest(".row.form-row.region").find('input.hidden-note');
            var content = $(elem).html() ? $(elem).html() : $(elem).text();
            $(hiddenNoteInput).val(content);
        }
        else {
            var hiddenTitleInput = $(elem).closest('.row').find('input[name=title]');
            $(hiddenTitleInput).val($(elem).text());
        }
    }
};