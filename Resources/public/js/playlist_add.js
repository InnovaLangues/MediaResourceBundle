
var prototypeHolder = $('.collection');
var collectionHolder = $('.collection tbody');
var $addButton = $('.add-collection');//add-collection

jQuery(document).ready(function () {
    // remove defaultly created Add button
    $('.form-collection-add').remove();
    $addButton.on('click', function (e) {
        e.preventDefault();
        addRegionPlaylistForm(collectionHolder);
    });
    refreshOrdering();
});

function addRegionPlaylistForm(collectionHolder) {
    var prototype = prototypeHolder.attr('data-prototype');
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
    $(collectionHolder).append(newForm);
    // set value to the hidden order input and label
    refreshOrdering();
}

function removeRegionPlaylistRow(sender) {
    $(sender).closest('tr').remove();
    refreshOrdering();
}

function refreshOrdering() {
    $('.collection tbody tr').each(function (index) {
        var order = index + 1;
        $(this).find('td.playlist_region_widget_ordering input').val(order);
        $(this).find('td.playlist_region_widget_ordering label').text(order);
    });
}