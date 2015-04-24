
/* confirm before delete */
$('a.fa-trash').click(function (ev) {
    var href = $(this).attr('href');
    $('#dataConfirmOK').attr('href', href);
    $('#dataConfirmModal').modal({show: true});
    return false;
});