$(function() {
    var $grid = $(".card-grid").isotope({
        itemSelector: ".grid-item",
        masonry: {
            gutter: 20
        }
    });

    $(".filters").on("click", ".filter", function() {
        var filterValue = $(this).attr("data-filter");
        $grid.isotope({ filter: filterValue });
    });
});