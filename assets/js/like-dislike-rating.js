jQuery(document).ready(function ($) {
  // Получение рейтинга для каждого блока при загрузке страницы
  $(".post__item-rating").each(function () {
    var $ratingContainer = $(this);
    var postId = $ratingContainer.data("post-id");

    $.ajax({
      url: LDR.ajax_url,
      type: "POST",
      dataType: "json",
      data: {
        action: "get_rating",
        post_id: postId,
      },
      success: function (response) {
        if (response.success) {
          $ratingContainer.find(".rating_value").text(response.data.rating);
          // Установка цвета в зависимости от значения рейтинга
          setRatingColor(
            $ratingContainer.find(".rating_value"),
            response.data.rating
          );
        }
      },
    });
  });

  // Обработчик клика по кнопкам голосования
  $(".post__item-rating button").on("click", function () {
    var $container = $(this).closest(".post__item-rating");
    var postId = $container.data("post-id");
    var vote = $(this).data("vote");

    $.ajax({
      url: LDR.ajax_url,
      type: "POST",
      dataType: "json",
      data: {
        action: "update_rating",
        post_id: postId,
        vote: vote,
        nonce: LDR.nonce,
      },
      success: function (response) {
        if (response.success) {
          var newRating = response.data.rating;
          $container.find(".rating_value").text(newRating);
          setRatingColor($container.find(".rating_value"), newRating);
        }
      },
    });
  });

  // Функция для установки цвета текста рейтинга
  function setRatingColor($el, rating) {
    if (rating > 0) {
      $el.css("color", "green");
    } else if (rating < 0) {
      $el.css("color", "red");
    } else {
      $el.css("color", "");
    }
  }
});
