// share widget
// v2.0

(function ($) {

  // custom select class

  function share_widget(item, options) {

    // options

    var defaults = {
      site_url: '//' + window.location.hostname,
      theme_dir: null,
      share_url: window.location.href,
      title: document.title,
      sites: null,
      accounts: null,
      elements: {
        list: null,
        facebook: {
          display: false,
          label: 'Facebook',
          icon: 'fab fa-facebook-f'
        },
        twitter: {
          display: false,
          label: 'Twitter',
          icon: 'fab fa-twitter',
          text: null
        },
        pinterest: {
          display: false,
          label: 'Pinterest',
          icon: 'fab fa-pinterest-p'
        },
        linkedin: {
          display: false,
          label: 'LinkedIn',
          icon: 'fab fa-linkedin-in'
        },
        permalink: {
          display: true,
          label: 'Copy Permalink',
          icon: 'fas fa-link'
        }
      },
      callback: null
    };

    defaults.share_page = defaults.site_url + '/share/'

    this.options = $.extend(true, defaults, options);

    this.item = $(item);
    this.init();
  }

  share_widget.prototype = {

    init: function () {

      var plugin_instance = this
      var plugin_item = this.item
      var plugin_settings = plugin_instance.options
      var plugin_elements = plugin_settings.elements

      //
      // INIT
      //

      console.log('share', 'init');

      // share URL

      if (
        typeof plugin_item.attr('data-social-url') != 'undefined' &&
        plugin_item.attr('data-social-url') != ''
      ) {
        plugin_settings.share_url = plugin_item.attr('data-social-url')
      }

      console.log(plugin_settings.share_url)

      //
      // ELEMENTS
      //

      // trigger

      plugin_elements.trigger = plugin_item.find('.widget-trigger')

      // list

      plugin_elements.list = plugin_item.find('.widget-menu')

      // permalink

      if (plugin_elements.permalink.display == true) {
        
        $('<li class="widget-menu-item share-permalink-wrap"><a href="#" class="widget-menu-link permalink"><i class="icon ' + plugin_settings.elements.permalink.icon + '"></i><span class="label">' + plugin_settings.elements.permalink.label + '</span></a></li>').appendTo(plugin_elements.list)

      }


      // CLICK EVENTS

      // trigger

      plugin_elements.trigger.click(function(e) {
        e.preventDefault()
    	  // e.stopImmediatePropagation()

        if (plugin_item.hasClass('open')) {

          plugin_item.removeClass('open')

        } else {

          // open this one
          plugin_item.addClass('open')

        }
      });

      // share link

      plugin_item.find('.widget-menu-link').click(function(e) {
    	  e.preventDefault()
    	  e.stopImmediatePropagation()

        var share_popup

        var menu_item = $(this).closest('.widget-menu-item')

    	  if ($(this).hasClass('site-facebook')) {

          // facebook

          share_popup = window.open(
            'https://www.facebook.com/sharer/sharer.php?u=' + plugin_settings.share_url,
            'fw_share_popup', 'width=600, height=400'
          )

    	  } else if ($(this).hasClass('site-twitter')) {

          // tweet text

          var window_title = document.title

          if (window_title.indexOf(" — ") > -1) {
            window_title = window_title.split(' — ')[0]
          }

          tweet_text = window_title
          
          // check for override in data- attribute
          
          // console.log(plugin_item.data())
          
          if (typeof plugin_item.attr('data-social-tweet-text') != 'undefined') {
            tweet_text = plugin_item.attr('data-social-tweet-text')
          }

          // if a twitter account is set, add 'via @XXX'

          if (menu_item.attr('data-social-account') != '') {
            tweet_text += ' via @' + menu_item.attr('data-social-account')
          }

          // add URL

          tweet_text += ': ' + encodeURIComponent(plugin_settings.share_url)

          share_popup = window.open(
            'https://twitter.com/intent/tweet?text=' + tweet_text,
            'fw_share_popup', 'width=600, height=400'
          )

        } else if ($(this).hasClass('site-pinterest')) {

          // pinterest

          share_popup = window.open(
            'http://pinterest.com/pin/create/button/?url=' + plugin_settings.share_url,
            'fw_share_popup', 'width=600, height=400'
          )

        } else if ($(this).hasClass('site-linkedin')) {

          // linkedin

          share_popup = window.open(
            'https://www.linkedin.com/shareArticle?mini=true&url=' + plugin_settings.share_url,
            'fw_share_popup', 'width=600, height=400'
          )

        } else if ($(this).hasClass('permalink')) {
          
          let copy_success = false
          
          console.log('copy ' + plugin_settings.share_url)
          
          if (typeof navigator.clipboard != 'undefined') {
          
            navigator.clipboard.writeText(plugin_settings.share_url).then(() => {
                
              $(this).find('i').removeClass().addClass('icon fas fa-check text-success')
              $(this).find('.label').text('Copied to clipboard')
              
              copy_success = true
                
            })
            
          }
          
          if (copy_success == false) {
            
            $(this).find('i').removeClass().addClass('icon fas fa-times text-warning')
            $(this).find('.label').text('Error')
            
          }
          
        } else {

      	  var width  = 575,
              height = 320,
              left   = ($(window).width()  - width)  / 2,
              top    = ($(window).height() - height) / 2,
              url    = this.href,
              opts   = 'status=0' +
                       ',width='  + width  +
                       ',height=' + height +
                       ',top='    + top    +
                       ',left='   + left;

          window.open(url, 'sharepopup', opts);

    	  }
  	  });

    }

  }

  // jQuery plugin interface

  $.fn.share_widget = function (opt) {
    var args = Array.prototype.slice.call(arguments, 1);

    return this.each(function () {

      var item = $(this);
      var instance = item.data('share_widget');

      if (!instance) {

        // create plugin instance if not created
        item.data('share_widget', new share_widget(this, opt));

      } else {

        // otherwise check arguments for method call
        if (typeof opt === 'string') {
          instance[opt].apply(instance, args);
        }

      }
    });
  }

}(jQuery));
