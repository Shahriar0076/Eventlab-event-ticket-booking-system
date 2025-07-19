(function ($) {
  "use strict";

  $(".sort").sortable({
    cursor: "move",
    axis: "y",
    update: function (e, ui) {
      let sorting = {};
      $.each($(".sort").find(`tr`), function (i, element) {
        var id = $(element).data(`id`);
        sorting[id] = i + 1;
      });

      updateSortOrder(sorting);
    },
  });
})(jQuery);

function sortOrderAction(sorting, action, csrf) {
  $.ajax({
    type: "POST",
    url: action,
    data: {
      sorting,
    },
    headers: {
      "X-CSRF-TOKEN": csrf,
    },
  });
}
