// scroll progress
// v1.0

(function ($) {

  function scroll_progress(item, options) {

    this.item = $(item);

    // options

    var defaults = {
      style: 'circle',
      classes: {
        section: '.fw-page-section',
        heading: '.progress-head'
      },
      markup: {
        link: '<a>',
        bar: '<span class="sp-well"><span class="sp-bar"></span></span>',
        circle: '<span class="sp-well"><span class="circle"><span class="circle-half left"></span><span class="circle-half right"></span></span></span>'
      },
      elements: {
        bar: null
      },
      sections: null,
      debug: false
    };

    this.options = $.extend(true, defaults, options);
    this.init();
  }

  scroll_progress.prototype = {

    // init

    init: function () {

      var plugin_instance = this
      var plugin_item = plugin_instance.item
      var plugin_settings = plugin_instance.options
      var plugin_classes = plugin_settings.classes
      var plugin_elements = plugin_settings.elements

      //
      // INIT
      //

      if (plugin_settings.debug == true) {
        console.log('scroll progress', 'init')
      }

      //
      // ELEMENTS
      //

      if (
        typeof plugin_item.attr('data-progress-section') !== 'undefined' &&
        plugin_item.attr('data-progress-section') != ''
      ) {
        plugin_classes.section = plugin_item.attr('data-progress-section')
      }

      if (
        typeof plugin_item.attr('data-progress-heading') !== 'undefined' &&
        plugin_item.attr('data-progress-heading') != ''
      ) {
        plugin_classes.heading = plugin_item.attr('data-progress-heading')
      }

      if (typeof plugin_item.attr('data-scroll-progress-style') !== 'undefined') {

        plugin_settings.style = plugin_item.attr('data-scroll-progress-style')

      }

      if (plugin_settings.style == 'circle') {
        plugin_item.addClass('style-circle')
        plugin_elements.bar = plugin_settings.markup.circle
      } else {
        plugin_item.addClass('style-bar')
        plugin_elements.bar = plugin_settings.markup.bar
      }

      // build menu

      plugin_settings.sections = $(plugin_classes.section + ':not(#hero)')

      plugin_settings.sections.each(function(i) {

        // section ID

        var section_ID = $(this).attr('id')

        // create new menu item

        var new_item = $('<li class="sp-item-' + i + '">').appendTo(plugin_item)

        // add link element

        var new_link = $(plugin_settings.markup.link).append(plugin_settings.markup[plugin_settings.style]).appendTo(new_item).addClass('smooth-scroll-link scroll-progress-link').attr('href', '#' + section_ID)

        // add text

        var link_text = 'Section'

        if ($(this).find(plugin_classes.heading).length) {

          link_text = $(this).find(plugin_classes.heading).first().html()

        }

        $('<span class="sp-text">' + link_text + '</span>').appendTo(new_link)

      })

      // add circle/bar element

      // plugin_elements.bar.appendTo($('.scroll-progress-link'))

      //
      // ACTIONS
      //

      // window scroll function

      if (plugin_settings.debug == true) {
        // console.log($(plugin_settings.section));
      }

      $(window).scroll(function() {

        // current distance scrolled in pixels

        var scroll_top = $(this).scrollTop()

        // offset scroll_top by half the window height

        var waypoint = scroll_top + ($(window).height() / 2)

        var check_next_section = true

        plugin_settings.sections.each(function(i) {

          // section specs

          var this_top = $(this).offset().top
          var this_height = $(this).height()
          var this_bottom = this_top + this_height

          // percentage value

          var percent = 0

          // matching progress item

          var progress_item = plugin_item.find('li:eq(' + i + ')');
          progress_item.removeClass('active');

          if (plugin_settings.debug == true) {
            // console.log(i, $(this), progress_item);
          }

          if (waypoint < this_top) {

            // the section is below the window

            percent = 0

            check_next_section = false

          } else if (waypoint >= this_top && waypoint <= this_bottom) {

            // scrolling within the section

            progress_item.addClass('active')

            // distance scrolled within the section
            var distance_scrolled = waypoint - this_top

            // percent value

            percent = (distance_scrolled / this_height) * 100

            if (plugin_settings.debug == true) {
              //console.log(distance_scrolled, this_height)
            }

            check_next_section = false

          } else if (waypoint > this_bottom) {

            // console.log(progress_item.find('a').attr('href'))

            // scrolled past the section

            percent = 100

            check_next_section = true

          }

          if (plugin_settings.debug == true) {
            console.log(progress_item.find('a').attr('href'), percent)
          }

          plugin_instance._set_percentage(progress_item, percent)


        })

      }); // window scroll

      $(document.body).trigger("sticky_kit:recalc");


    },

    _set_percentage: function(item, percent) {

      var plugin_instance = this
      var plugin_item = this.item
      var plugin_settings = plugin_instance.options
      var plugin_elements = plugin_settings.elements

      if (plugin_settings.style == 'circle') {

        // 360º value

        var new_rotate = Math.ceil((360 * (percent / 100)) / 5) * 5

        // snap to 0 or 360

        if (new_rotate <= 10) {
          new_rotate = 0
        } else if (new_rotate >= 350) {
          new_rotate = 360
        }

        if (new_rotate == 0 ) {

          item.removeClass('active over-half');
          plugin_instance._object_rotation(item.find('.circle-half'), 0);

        } else if (new_rotate <= 180) {

          item.removeClass('over-half');
          plugin_instance._object_rotation(item.find('.circle-half'), new_rotate);

        } else if (new_rotate > 180 && new_rotate < 360) {

          item.addClass('over-half');
          plugin_instance._object_rotation(item.find('.circle-half.right'), 180);
          plugin_instance._object_rotation(item.find('.circle-half.left'), new_rotate);

        } else if (new_rotate >= 360) {

          item.addClass('over-half');
          plugin_instance._object_rotation(item.find('.circle-half.right'), 180);
          plugin_instance._object_rotation(item.find('.circle-half.left'), 360);

        }

      } else if (plugin_settings.style == 'bar') {
        item.find('.sp-bar').css('width', percent + '%')
      }

      if (percent >= 100) {
        item.addClass('complete')
      } else {
        item.removeClass('complete')
      }

    },

    _object_rotation: function(element, new_value) {

      var matrix = element.css("-webkit-transform") ||
        element.css("-moz-transform") ||
        element.css("-ms-transform")  ||
        element.css("-o-transform")   ||
        element.css("transform");

      if (matrix !== 'none' && typeof matrix !== 'undefined') {
        var values = matrix.split('(')[1].split(')')[0].split(',');
        var a = values[0];
        var b = values[1];
        var angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
      } else {
        var angle = 0;
      }

  	  // if a new value is set, change the CSS
  	  // otherwise, just return the current value

  	  if (typeof new_value !== 'undefined') {

    	  element.css("-webkit-transform", 'rotate(' + new_value + 'deg)')
          .css("-moz-transform", 'rotate(' + new_value + 'deg)')
          .css("-ms-transform", 'rotate(' + new_value + 'deg)')
          .css("-o-transform", 'rotate(' + new_value + 'deg)')
          .css("transform", 'rotate(' + new_value + 'deg)');

  	  } else {

        return angle;

  	  }

    }

  }

  // jQuery plugin interface

  $.fn.scroll_progress = function (opt) {
    var args = Array.prototype.slice.call(arguments, 1);

    return this.each(function () {

      var item = $(this);
      var instance = item.data('scroll_progress');

      if (!instance) {

        // create plugin instance if not created
        item.data('scroll_progress', new scroll_progress(this, opt));

      } else {

        // otherwise check arguments for method call
        if (typeof opt === 'string') {
          instance[opt].apply(instance, args);
        }

      }
    });
  }

}(jQuery));
