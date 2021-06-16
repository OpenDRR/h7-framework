// flexbox carousel
// v1.0

;(function ($) {

  // custom select class

  function new_carousel (item, options) {
    this.item = $(item)

    // options

    var defaults = {
      debug: true,
      elements: {},
      current_slide: 1,
      num_slides: 1,
      elements: {
        track: null,
        slides: {},
      },
      classes: {
        slide: 'slide'
      },
      transition: 'slide',
      auto_height: true
    }

    this.options = $.extend(true, defaults, options)
    this.init()
  }

  new_carousel.prototype = {

    // init

    init: function () {

      var plugin_instance = this
      var plugin_item = plugin_instance.item
      var plugin_settings = plugin_instance.options
      var plugin_elements = plugin_settings.elements
      var plugin_classes = plugin_settings.classes

      //
      // INIT
      //

      // add init/debug classes

      plugin_item.addClass('carousel carousel-initialized is-set')

      if (plugin_item.debug == true) {
        plugin_item.addClass('carousel-debug')
      }

      // settings

      if (plugin_settings.auto_height == true) {
        plugin_item.addClass('auto-height')
      }

      // utilities

      plugin_settings.is_reversing = false

      //
      // OBJECTS
      //

      // slides

      plugin_elements.slides = $('.' + plugin_classes.slide)

      plugin_elements.slides.each(function(e) {
        $(this).addClass('carousel-slide').attr('data-slide-num', $(this).index() + 1)
      })

      plugin_settings.num_slides = plugin_elements.slides.length

      // track

      plugin_elements.slides.wrapAll('<div class="carousel-track">')
      plugin_elements.track = plugin_item.find('.carousel-track')

      // wrapper

      plugin_elements.track.wrap('<div class="carousel-wrap">')
      plugin_elements.wrap = plugin_item.find('.carousel-wrap')

      // dots
      plugin_elements.dots = $('<div class="carousel-dots">').appendTo(plugin_item)

      plugin_elements.slides.each(function() {
        $('<span class="dot" data-slide-num="' + $(this).attr('data-slide-num') + '">' + $(this).attr('data-slide-num') + '</span>').appendTo(plugin_elements.dots)
      })

      plugin_elements.dots.on('click', '.dot', function(e) {
        console.log($(this).attr('data-slide-num'))


      })

      // status
      plugin_elements.status = $('<div class="carousel-status">').appendTo(plugin_item)

      // controls
      plugin_elements.controls = $('<div class="carousel-controls">').appendTo(plugin_item)

      plugin_elements.prev = $('<button class="toggle" data-toggle="prev">Prev</button>').appendTo(plugin_elements.controls)
      plugin_elements.next = $('<button class="toggle" data-toggle="next">Next</button>').appendTo(plugin_elements.controls)

      //
      // SETUP
      //

      plugin_instance.change_height(1, false)
      plugin_instance.update_status()

      //
      // ACTIONS
      //

      $('.toggle').on('click', function(e) {

        var new_slide_num

        if ($(this).data('toggle') == 'next') {

          // add 1 to current

          new_slide_num = plugin_settings.current_slide + 1

          // if we went past the end, reset it to 1

          if (new_slide_num == plugin_settings.num_slides + 1) {
            new_slide_num = 1
          }

        } else {

          // subtract 1 from current

          new_slide_num = plugin_settings.current_slide - 1

          // if we went past the end, reset it to num_slides

          if (new_slide_num == 0) {
            new_slide_num = plugin_settings.num_slides
          }

        }

        plugin_instance.goto(new_slide_num)

      })

      $('.toggle').on('click', function(e) {
/*
        var $newSeat
        var $el = plugin_item.find('.is-ref')
        var $currSliderControl = $(e.currentTarget)

        var slide_direction = $currSliderControl.data('toggle')

        // prev slide is always updated to whatever the current one was
        // when this click happened

        prev_slide = plugin_settings.current_slide

        $el.removeClass('is-ref');

        plugin_instance.before_change(slide_direction)

        if (slide_direction === 'next') {

          // define the new seat

          $newSeat = plugin_instance.next($el);

          // add 1 to current

          plugin_settings.current_slide = parseInt($newSeat.attr('data-slide-num')) + 1

          // if we went past the end, reset it to 1

          if (plugin_settings.current_slide == plugin_settings.num_slides + 1) {
            plugin_settings.current_slide = 1
          }

          is_reversing = false
          plugin_item.removeClass('is-reversing');

        } else {

          $newSeat = plugin_instance.prev($el);

          // subtract 1 from current

          plugin_settings.current_slide = parseInt($newSeat.attr('data-slide-num')) + 1

          // if we went past the end, reset it to num_slides

          if (plugin_settings.current_slide == plugin_settings.num_slides + 1) {
            plugin_settings.current_slide = 1
          }

          is_reversing = true
          plugin_item.addClass('is-reversing');

        }

        // set the new is-ref

        $newSeat.addClass('is-ref').css('order', 1);

        // adjust the order of the rest of the slides

        for (var i = 2; i <= plugin_elements.slides.length; i++) {

          $newSeat = plugin_instance.next($newSeat).css('order', i);

        }

        // remove .is-set to turn on the slide transition

        plugin_item.removeClass('is-set');

        return setTimeout(function() {

          plugin_instance.after_change()

          plugin_instance.update_status()
          plugin_item.addClass('is-set');

        }, 50);
*/

      });


      if (plugin_settings.debug == true) {
        console.log('carousel', 'initialized')
      }
    },

    get_slide_num: function(direction) {

      var plugin_instance = this
      var plugin_item = this.item
      var plugin_settings = plugin_instance.options
      var plugin_elements = plugin_settings.elements

      var slide_num

      if (direction == 'next') {

        slide_num = plugin_settings.current_slide + 1

        if (slide_num == plugin_settings.num_slides + 1) {
          slide_num = 1
        }

      } else if (direction == 'prev') {

        slide_num = plugin_settings.current_slide - 1

        if (slide_num == 0) {
          slide_num = plugin_settings.num_slides
        }

      }

      return slide_num

    },

    before_change: function(fn_options) {

      var plugin_instance = this
      var plugin_item = this.item
      var plugin_settings = plugin_instance.options
      var plugin_elements = plugin_settings.elements

      var settings = $.extend(true, {
        direction: 'next'
      }, fn_options)

      plugin_instance.change_height(plugin_instance.get_slide_num(settings.direction))

    },

    after_change: function(fn_options) {

      var plugin_instance = this
      var plugin_item = this.item
      var plugin_settings = plugin_instance.options
      var plugin_elements = plugin_settings.elements

      var settings = $.extend(true, {
        direction: 'next'
      }, fn_options)




    },

    goto: function(slide_num) {

      var plugin_instance = this
      var plugin_item = this.item
      var plugin_settings = plugin_instance.options
      var plugin_elements = plugin_settings.elements

      // prev_slide = plugin_settings.current_slide

      $el = plugin_elements.wrap.find('.is-ref')

      $el.removeClass('is-ref');

      // if the target is less than the current

      console.log('current: ' + plugin_settings.current_slide)
      console.log('goto: ' + slide_num)

      if (
        (slide_num < plugin_settings.current_slide) ||
        (plugin_settings.current_slide == 1 && slide_num == plugin_settings.num_slides)
      ) {

        is_reversing = true
        plugin_item.addClass('is-reversing')

      } else {

        is_reversing = false
        plugin_item.removeClass('is-reversing')

      }

      $newSeat = plugin_instance.prev(plugin_elements.wrap.find('[data-slide-num="' + slide_num + '"]'))

      console.log($newSeat)

      // set the new is-ref

      $newSeat.addClass('is-ref').css('order', 1);

      // adjust the order of the rest of the slides

      for (var i = 2; i <= plugin_elements.slides.length; i++) {

        $newSeat = plugin_instance.next($newSeat).css('order', i);

      }

      // remove .is-set to turn on the slide transition

      plugin_item.removeClass('is-set');

      return setTimeout(function() {

        // update the current slide value
        plugin_settings.current_slide = slide_num

        // after slide callout
        plugin_instance.after_change()

        // update status count
        plugin_instance.update_status()

        // add '.is-set' - causes the slide transition
        plugin_item.addClass('is-set');

      }, 50);


    },

    next: function(element) {

      var plugin_instance = this
      var plugin_item = this.item
      var plugin_settings = plugin_instance.options
      var plugin_elements = plugin_settings.elements

      if (element.next().length) {
        return element.next();
      } else {
        return plugin_elements.slides.first();
      }

    },

    prev: function(element) {

      var plugin_instance = this
      var plugin_item = this.item
      var plugin_settings = plugin_instance.options
      var plugin_elements = plugin_settings.elements

      if (element.prev().length) {
        return element.prev();
      } else {
        return plugin_elements.slides.last();
      }

    },

    update_status: function() {

      var plugin_instance = this
      var plugin_item = this.item
      var plugin_settings = plugin_instance.options
      var plugin_elements = plugin_settings.elements

      plugin_elements.dots.find('span').removeClass('current')
      plugin_elements.dots.find('[data-slide-num="' + plugin_settings.current_slide + '"]').addClass('current')

      plugin_elements.status.html(plugin_settings.current_slide + ' / ' + plugin_settings.num_slides)

    },

    change_height: function(slide_num, animate = true) {

      var plugin_instance = this
      var plugin_item = plugin_instance.item
      var plugin_settings = plugin_instance.options
      var plugin_elements = plugin_settings.elements
      var plugin_classes = plugin_settings.classes

      new_height = plugin_item.find('[data-slide-num="' + slide_num + '"]').outerHeight()

      if (animate == true) {

        plugin_elements.wrap.animate({
          height: new_height + 'px'
        }, 500)

      } else {

        plugin_elements.wrap.css('height', new_height + 'px')

      }

    }


  }

  // jQuery plugin interface

  $.fn.new_carousel = function (opt) {
    var args = Array.prototype.slice.call(arguments, 1)

    return this.each(function () {
      var item = $(this)
      var instance = item.data('new_carousel')

      if (!instance) {
        item.data('new_carousel', new new_carousel(this, opt))
      } else {
        if (typeof opt === 'string') {
          instance[opt].apply(instance, args)
        }
      }
    })
  }

}(jQuery))
