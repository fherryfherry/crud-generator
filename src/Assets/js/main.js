/** Feather **/
feather.replace({
    'stroke-width': 2,
    'height': 17
});

$("#menu-toggle").click(function (e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});

function showSubMenu(t)
{
    var toggle = $(t).data('toggle');
    if(toggle === 0) {
        $(t).next("ul").show();
        $(t).data("toggle", 1);
        $(t).attr("data-toggle", 1);
    } else {
        $(t).next("ul").hide();
        $(t).data("toggle", 0);
    }
}