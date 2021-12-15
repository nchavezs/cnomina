var classes;
var id;
var name;

$(".custom-select").each(function() {
    classes = $(this).attr("class"),
        id = $(this).attr("id"),
        name = $(this).attr("name");
    var template = '<div class="' + classes + '">';
    template += '<span class="custom-select-trigger">' + $("#" + id + ">option:selected").html() + '</span>';
    template += '<div class="custom-options">';
    $(this).find("option").each(function() {
        template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
    });
    template += '</div></div>';

    $(this).wrap('<div class="custom-select-wrapper"></div>');
    $(this).hide();
    $(this).after(template);
});
$(".custom-option:first-of-type").hover(function() {
    $(this).parents(".custom-options").addClass("option-hover");
}, function() {
    $(this).parents(".custom-options").removeClass("option-hover");
});

var cambio = true;
$(".custom-select-trigger").on("click", function() {
    $(".custom-select").removeClass("opened");
    if (cambio) {
        $(this).parents(".custom-select").addClass("opened");
    } else {
        $(this).parents(".custom-select").removeClass("opened");
    }
    cambio = !cambio;

    $('html').one('click', function() {
        $(".custom-select").removeClass("opened");
        cambio = true;
    });
    event.stopPropagation();
});
$(".custom-option").on("click", function() {
    $(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
    $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
    $(this).addClass("selection");
    $(this).parents(".custom-select").removeClass("opened");
    $(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
    $("#" + id).val($("#" + id + ">option:selected").val()).change();
    cambio = true;
});