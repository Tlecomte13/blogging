/**
 * after submit button disabled and loader icon
 */
import $ from 'jquery'

$( "form" ).submit(function() {
    $('button:submit').attr('disabled', '').html('' +
        '<span class="spinner-border spinner-border-sm"></span> chargement...' +
    '')
});
