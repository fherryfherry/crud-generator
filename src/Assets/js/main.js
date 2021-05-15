/** Feather **/
feather.replace({
    'stroke-width': 2,
    'height': 17
});

$("#menu-toggle").click(function (e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});
