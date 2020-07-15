/**
 * after submit button disabled and loader icon
 */
$( "form" ).submit(function() {
    $('button:submit').attr('disabled', '').html('' +
        '<span class="spinner-border spinner-border-sm"></span> chargement...' +
    '')
});
