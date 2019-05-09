$(".timespan-selector").ready( function() {
    $(`option[value="${TIME_SPAN}"]`).attr('selected', 'true');
    $(".timespan-selector").show();
});

$(document).ready(function() {

    $(".timespan-selector").change( function() {
        
        console.log( $(".timespan-selector").val() );
        window.location.href = SITE_URL + '/stats_type/' + $(".timespan-selector").val();

    });

});
