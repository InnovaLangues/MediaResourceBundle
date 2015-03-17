'use strict';

var DomUtils = {
    openRegionHelpModal: function (current, audioUrl) {
        // console.log('open region help dude!');
        // bootbox.alert('open region help dude!');
        

        var domRow = this.getRegionRow(current.start + 0.1, current.end - 0.1);
        // loop available ?
        var loop = $(domRow).find('.hidden-config-loop').val() === '1' ? true : false;
        // backward available ?
        var backward = $(domRow).find('.hidden-config-backward').val() === '1' ? true : false; //$('input[name=backward]').is(':checked');
        // rate available ?
        var rate = $(domRow).find('.hidden-config-rate').val() === '1' ? true : false;
        // text
        var text = $(domRow).find('.hidden-config-text').val();

        var html = '<div class="row">';
        html += '<div class="col-md-12 text-center">';
        //0,5.85376690976691
        //html +=             '<audio src="' + audioUrl + '#t=' + current.start + ',' + current.end + '" controls>';
        html += '<audio id="help-audio-player" src="' + audioUrl + '">'; // will not show as no controls defined
        html += '</audio>';
    
        html += '<button class="btn btn-default" onclick="playHelp(' + current.start + ', ' + current.end + ')" style="margin:5px;">';
        html += ' <i class="fa fa-play"></i> ';
        html += ' / ';
        html += '<i class="fa fa-pause"></i>';
        html += '</button>';        
        if (backward) {
            html += '<button class="btn btn-default" onclick="playBackward();" style="margin:5px;">';
            html += '<i class="fa fa-exchange"></i> ';
            html += '</button>';
        }
        if (loop) {
            html += '<button class="btn btn-default" onclick="toggleLoopPlayback(this)" style="margin:5px;">';
            html += '<i class="fa fa-retweet"></i> ';
            html += '</button>';
        }

        if (rate) {
            html += '<div class="btn-group">';
            html += '<button class="btn btn-default active" onclick="setPlaybackRate(this, 1)">x1</button>';
            html += '<button class="btn btn-default" onclick="setPlaybackRate(this, 0.8)">x0.8</button>';
            html += '<button class="btn btn-default" onclick="setPlaybackRate(this, 0.5)">x0.5</button>';
            html += '</div>';
        }
        html += '<hr/>';
        if (text !== '') {
            html += '<label style="margin:5px;">' + text + '</label>';
        }
        html += '</div>';
        html += '</div>';

        var modal = bootbox.dialog({
            title: "Aide sur la région:",
            message: html,
            show: false,
            buttons: {
                success: {
                    label: "Fermer",
                    className: "btn-success",
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
    addRegionToDom: function (wavesurfer, wavesurferUtils, region) {
        var my = this;
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

        html += '<div class="col-xs-8">';
        html += '<div onclick="goTo(' + region.start + ');" contenteditable="true" class="text-left note">' + region.data.note + '</div>';
        html += '</div>';

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
            console.log('insert at end');
            $(container).append(html);
        }
    },
    /**
     * Allow author to set witch help will be available for the region
     * @param {type} elem current clicked config button
     */
    openConfigRegionModal: function (elem) {

        // find region config hidden inputs
        //help-region-id -> problem is that the id might not exist (for newly created regions) -> need to select a region by time ?
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
                    label: "Fermer",
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

