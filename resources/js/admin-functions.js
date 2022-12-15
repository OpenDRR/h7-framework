(function($) {

  $(function() {

    var elements_field = $('.page-builder-field')

		if (elements_field.length) {

			var toolbar, collapse_btn, insert_btn

			$.ajax({
				type: 'POST',
				url: admin_ajax_data.url,
				data: {
					'action': 'get_page_builder_toolbar'
				},
				success: function (data) {
					// console.log(data)

					elements_field.find('.acf-label').first().prepend(data)

				},
				error: function () {
					console.log('error')
				},
				complete: function() {

					toolbar = elements_field.find('#page-builder-toolbar')
					overlay = elements_field.find('#page-builder-modal-overlay')
					collapse_btn = toolbar.find('#toolbar-item-toggle')
					insert_btn = toolbar.find('#toolbar-item-insert')

					// TOGGLE

					collapse_btn.click(function(e) {
			      e.preventDefault()

			      if ($(this).hasClass('collapse-all')) {

			        $('.page-builder-field > .acf-input > .acf-flexible-content > .values > .layout:not(.-collapsed) > .acf-fc-layout-handle').trigger('click')

			        $(this).removeClass('collapse-all').addClass('expand-all')
			        $(this).find('i').removeClass('fa-compress').addClass('fa-expand')
			        $(this).find('span').text('Expand all')

			      } else {

			        $('.page-builder-field > .acf-input > .acf-flexible-content > .values > .layout.-collapsed > .acf-fc-layout-handle').trigger('click')

			        $(this).find('i').removeClass('fa-expand').addClass('fa-compress')
			        $(this).find('span').text('Collapse all')
			        $(this).removeClass('expand-all').addClass('collapse-all')

			      }
			    })

					// INSERT

					if ($('body').hasClass('post-new-php')) {

						insert_btn.remove()

					} else {

						var templates

						var template_modal = elements_field.find('#template-modal'),
								template_menu = elements_field.find('#template-menu'),
								template_index = elements_field.find('#template-index'),
								template_submit = elements_field.find('#template-submit'),
								template_cancel = elements_field.find('#template-cancel'),
								template_reload = elements_field.find('#template-reload')

						insert_btn.click(function(e) {
							e.preventDefault()
							
							// populate the 'index' select
							
							template_index.empty().append('<option value="0">Before existing content</option>')
							
							let index_i = 1
							
							$('[data-key="builder_elements"] > .acf-input > .acf-flexible-content > .values > .layout').each(function() {
								
								let this_label = 'After ' + $(this).find('.acf-fc-layout-handle').first().text().split(' ')[1]
								
								if (
									$(this).find('[data-key="builder_layout_section_id"] input').length &&
									$(this).find('[data-key="builder_layout_section_id"] input').val() != ''
								) {
									this_label += ' — #' + $(this).find('[data-key="builder_layout_section_id"] input').val()
								}
								
								$('<option value="' + index_i + '">' + this_label + '</option>').appendTo(template_index)
								
								index_i += 1
								
							})
							
							// open the modal
							overlay.addClass('open')
							template_modal.show()

						})
						
						template_menu.change(function() {
							if ($(this).val() != '') {
								template_index.prop('disabled', false)
								template_submit.prop('disabled', false)
							} else {
								template_submit.prop('disabled', true)
								template_index.prop('disabled', true)
							}
						})

						// submit

						template_submit.click(function(e) {
							e.preventDefault()

							if (template_menu.val() != '') {

								$.ajax({
									type: 'POST',
									url: admin_ajax_data.url,
									dataType: 'json',
									data: {
										source: template_menu.val(),
										index: template_index.val(),
										target: acf.get('post_id'),
										action: 'fw_get_template_fields'
									},
									success: function(data) {

										console.log(data)
										
										if (data.status == 'success') {
											
											$('#template-modal-footer').remove()
											$('#template-modal-reload').show()
											
											template_modal.find('.page-builder-modal-body').html('<h3>Success</h3><p>' + data.message + ' Reload this page now to see the new elements.</p>')
										}

									},
									error: function(huh, wha) {

										console.log('error', huh, wha)

									},
									complete: function() {

										// location.reload()

									}
								})

							}

						})

						// cancel

						template_cancel.click(function(e) {
							e.preventDefault()
							overlay.removeClass('open')
							template_modal.hide()
						})
						
						// reload
						
						template_reload.click(function(e) {
							e.preventDefault()
							location.reload()
						})

					}

				}
			})

		}



		// //
		// // INSERT FROM TEMPLATE
		// //
		//
		// if (!$('body').hasClass('post-new-php') && elements_field.length) {
		//
	  //   var insert_btn = $('<a href="#" style="display: block; float: right;"><i class="fas fa-paste" style="margin-right: 10px;"></i><span>Insert from Template</span></span>')
		//
		// 	insert_btn.prependTo(toolbar)
		//
		// 	var templates
		//
		// 	var template_overlay = $('<div id="template-modal-overlay">').appendTo('body')
		//
		// 	var template_modal = $('<div id="template-modal"><h2>Insert fields from a template</h2><p>Note: After you click \'Insert\' this page will reload and <strong>any unsaved changes will be lost.</strong></p>').appendTo(template_overlay)
		//
		// 	var template_menu = $('<select id="template-menu"><option disabled>- Select a template -</option></select>').appendTo(template_modal)
		//
		// 	var template_submit = $('<button id="template-submit">Insert</button>').insertAfter(template_menu)
		//
		// 	var template_cancel = $('<a id="template-cancel">Cancel</a>').insertAfter(template_submit)
		//
		// 	insert_btn.click(function(e) {
		// 		e.preventDefault()
		//
		// 		$.ajax({
		// 			type: 'POST',
		// 			url: admin_ajax_data.url,
		// 			data: {
		// 				'action': 'get_template_posts'
		// 			},
		// 			success: function (data) {
		//
		// 				if (data != 'none') {
		// 					templates = JSON.parse(data)
		// 				}
		//
		// 				console.log(templates)
		//
		// 				templates.forEach(function(template, i) {
		//
		// 					$('<option value="' + template['id'] + '">' + template['title'] + '</option>').appendTo(template_menu)
		//
		// 				})
		//
		// 				template_overlay.show()
		//
		// 			},
		// 			error: function () {
		//
		// 				console.log('error')
		//
		// 			},
		// 			complete: function() {
		//
		// 			}
		// 		})
		//
		// 		template_submit.click(function() {
		//
		// 			if (template_menu.val() != '') {
		//
		// 				$.ajax({
		// 					type: 'POST',
		// 					url: admin_ajax_data.url,
		// 					data: {
		// 						'source': template_menu.val(),
		// 						'target': acf.get('post_id'),
		// 						'action': 'get_template_fields'
		// 					},
		// 					success: function (data) {
		//
		// 						console.log(data)
		//
		// 					},
		// 					error: function () {
		//
		// 						console.log('error')
		//
		// 					},
		// 					complete: function() {
		//
		// 						location.reload()
		//
		// 					}
		// 				})
		//
		// 			}
		//
		// 		})
		//
		// 	})
		//
		// 	template_cancel.click(function(e) {
		// 		e.preventDefault()
		//
		// 		template_overlay.hide()
		// 	})
		//
		// }

		//
		// test - populate block headings with content type
		//

		// elements_field.find('[data-layout="block"]').each(function() {
		// 	var content_label = $(this).find('.blocks-loop-field').find('.acf-fc-layout-handle').first().text()
		//
		// 	var current_label = $(this).find('.acf-fc-layout-handle').first().text()
		//
		// 	$(this).find('.acf-fc-layout-handle').first().text(current_label + ' — ' + content_label)
		// })

  });
})(jQuery);
