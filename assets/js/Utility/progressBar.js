import $ from 'jquery'

var i = 0

$(function ()
{
    var befretime = new Date().getTime();
    window.onload = getTimeLoad;
    function getTimeLoad()
    {
        let aftertime = new Date().getTime();
        // Time calculating in seconds
        let time = (aftertime - befretime)
        myLoop(time)
    }
});

function myLoop(time) {
    setTimeout(function () {
        $('.progress-bar').attr({
            "aria-valuenow": i,
            "style": "width:" + i + "%"
        })
        i++;
        if (i < 101) {
            myLoop();
            if (i === 100) {
                setTimeout(function () {
                    $('div.progress').fadeOut()
                }, 1000)
            }
        }
    }, time / 100)
}