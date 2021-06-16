(function() {
  $(function() {
    $("body").on("click", ".example_controls button", (function(_this) {
      return function(e) {
        return $(e.currentTarget).closest(".example").find("iframe")[0].contentWindow.scroll_it();
      };
    })(this));
    return $(".nav").stick_in_parent().on("sticky_kit:stick", (function(_this) {
      return function(e) {
        return setTimeout(function() {
          return $(e.target).addClass("show_hidden");
        }, 0);
      };
    })(this)).on("sticky_kit:unstick", (function(_this) {
      return function(e) {
        return setTimeout(function() {
          return $(e.target).removeClass("show_hidden");
        }, 0);
      };
    })(this));
  });

}).call(this);
