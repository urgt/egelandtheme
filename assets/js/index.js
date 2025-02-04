jQuery(document).ready(function ($) {
  // Обработчик клика по кнопкам "Лайк" и "Дизлайк"
  $(".like-dislike-rating button").on("click", function () {
    var $container = $(this).closest(".like-dislike-rating"),
      postId = $container.data("post-id"),
      vote = $(this).data("vote"),
      nonce = $container.find(".ldr_nonce").val();

    $.ajax({
      url: LDR.ajax_url,
      type: "POST",
      data: {
        action: "update_rating",
        post_id: postId,
        vote: vote,
        nonce: nonce,
      },
      success: function (response) {
        if (response.success) {
          var newRating = response.data.rating,
            $ratingEl = $container.find(".rating-value");

          $ratingEl.text(newRating);

          if (newRating > 0) {
            $ratingEl.css("color", "green");
          } else if (newRating < 0) {
            $ratingEl.css("color", "red");
          } else {
            $ratingEl.css("color", "");
          }
        }
      },
    });
  });
});
