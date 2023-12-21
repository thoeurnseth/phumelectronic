(function() {
	'use strict';

	var $ = jQuery;

	var ThemeHelper = {
		run_closeMenuAreaLayout: function run_closeMenuAreaLayout() {
			var menuArea = $('.additional-menu-area');
			var trigger = $('.side-menu-trigger', menuArea);
			trigger.removeClass('side-menu-close').addClass('side-menu-open');

			if (menuArea.find('> .rt-cover').length) {
				menuArea.find('> .rt-cover').remove();
			}

			$('.sidenav').css('transform', 'translateX(-100%)');
		},
		run_closeSideMenu: function run_closeSideMenu() {
			var wrapper = $('body').find('>#page'),
				$this = $('#side-menu-trigger a.menu-times');
			wrapper.removeClass('open').find('.offcanvas-mask').remove();
			$("#offcanvas-body-wrapper").attr('style', '');
			$this.prev('.menu-bar').removeClass('open');
			$this.addClass('close');
		},
		run_sticky_menu: function run_sticky_menu() {
			var wrapperHtml = $('<div class="main-header-sticky-wrapper"></div>');
			var wrapperClass = '.main-header-sticky-wrapper';
			$('.main-header').clone().appendTo(wrapperHtml);
			$('#page').append(wrapperHtml);
			var height = $(wrapperClass).outerHeight() + 30;
			$(wrapperClass).css('margin-top', '-' + height + 'px');
			$(window).scroll(function() {
				if ($(this).scrollTop() > 300) {
					$('body').addClass('rdthemeSticky');
				} else {
					$('body').removeClass('rdthemeSticky');
				}
			});
		},
		run_sticky_meanmenu: function run_sticky_meanmenu() {
			$(window).scroll(function() {
				if ($(this).scrollTop() > 50) {
					$('body').addClass("mean-stick");
				} else {
					$('body').removeClass("mean-stick");
				}
			});
		},
		run_isotope: function run_isotope($container, filter) {
			$container.isotope({
				filter: filter,
				layoutMode: 'fitRows',
				animationOptions: {
					duration: 750,
					easing: 'linear',
					queue: true
				}
			});
		},
		add_vertical_menu_class: function add_vertical_menu_class() {
			var screenWidth = $('body').outerWidth();

			if (screenWidth < 992) {
				$('.vertical-menu').addClass('vertical-menu-mobile');
			} else {
				$('.vertical-menu').removeClass('vertical-menu-mobile');
			}
		},
		owl_change_num_pagination: function owl_change_num_pagination($owlParent, index) {
			$owlParent.find('.owl-numbered-dots-items span').removeClass('active');
		}
	};
	var Theme = {
		rt_offcanvas_menu: function rt_offcanvas_menu() {
			$('#page').on('click', '.offcanvas-menu-btn', function(e) {
				e.preventDefault();
				var $this = $(this),
					wrapper = $(this).parents('body').find('>#page'),
					wrapMask = $('<div />').addClass('offcanvas-mask'),
					offCancas = document.getElementById('offcanvas-body-wrap');

				if ($this.hasClass('menu-status-open')) {
					wrapper.addClass('open').append(wrapMask);
					$this.removeClass('menu-status-open').addClass('menu-status-close');
					offCancas.style.transform = 'translateX(' + 0 + 'px)';
					$('body').css({
						overflow: 'hidden',
						transition: 'all 0.3s ease-out'
					});
				} else {
					wrapper.removeClass('open').find('> .offcanvas-mask').remove();
					$this.removeClass('menu-status-close').addClass('menu-status-open');
					offCancas.style.transform = 'translateX(' + -100 + '%)';

					if (MetroObj.rtl == 'yes') {
						offCancas.style.transform = 'translateX(' + 100 + '%)';
					}

					$('body').css({
						overflow: 'visible',
						transition: 'all 0.3s ease-out'
					});
				}

				return false;
			});
			$('#page').on('click', '#side-menu-trigger a.menu-times', function(e) {
				e.preventDefault();
				var $this = $(this);
				$("#offcanvas-body-wrapper").attr('style', '');
				$this.prev('.menu-bar').removeClass('open');
				$this.addClass('close');
				ThemeHelper.run_closeSideMenu();
				return false;
			});

			$(document).on('click', '#page.open .offcanvas-mask', function() {
				ThemeHelper.run_closeSideMenu();
			});
			$(document).on('keyup', function(event) {
				if (event.which === 27) {
					event.preventDefault();
					ThemeHelper.run_closeSideMenu();
				}
			});
		},
		rt_offcanvas_menu_layout: function rt_offcanvas_menu_layout() {
			var menuArea = $('.additional-menu-area');
			menuArea.on('click', '.side-menu-trigger', function(e) {
				e.preventDefault();
				var self = $(this);

				if (self.hasClass('side-menu-open')) {
					$('.sidenav').css('transform', 'translateX(0%)');

					if (!menuArea.find('> .rt-cover').length) {
						menuArea.append("<div class='rt-cover'></div>");
					}

					self.removeClass('side-menu-open').addClass('side-menu-close');
				}
			});
			menuArea.on('click', '.closebtn', function(e) {
				e.preventDefault();
				ThemeHelper.run_closeMenuAreaLayout();
			});
			$(document).on('click', '.rt-cover', function() {
				ThemeHelper.run_closeMenuAreaLayout();
			});
		},
		scroll_to_top: function scroll_to_top() {
			$('.scrollToTop').on('click', function() {
				$('html, body').animate({
					scrollTop: 0
				}, 800);
				return false;
			});
			$(window).scroll(function() {
				if ($(window).scrollTop() > 300) {
					$('.scrollToTop').addClass('back-top');
				} else {
					$('.scrollToTop').removeClass('back-top');
				}
			});
		},
		preloader: function preloader() {
			$('#preloader').fadeOut('slow', function() {
				$(this).remove();
			});
		},
		sticky_menu: function sticky_menu() {
			if (MetroObj.hasStickyMenu == 1) {
				ThemeHelper.run_sticky_menu();
				ThemeHelper.run_sticky_meanmenu();
			}
		},
		ripple_effect: function ripple_effect() {
			if (typeof $.fn.ripples == 'function') {
				$('.rt-water-ripple').ripples({
					resolution: 712,
					dropRadius: 30,
					perturbance: 0.01
				});
			}
		},
		category_search_dropdown: function category_search_dropdown() {
			$('.category-search-dropdown-js .dropdown-menu li').on('click', function(e) {
				var $parent = $(this).closest('.category-search-dropdown-js'),
					slug = $(this).data('slug'),
					name = $(this).text();
				$parent.find('.dropdown-toggle').text($.trim(name));
				$parent.find('input[name="product_cat"]').val(slug);
			});

			if ($.fn.autocomplete) {
				$(".ps-autocomplete-js .product-autocomplete-js").autocomplete({
					minChars: 2,
					search: function search(event, ui) {
						if (!$(event.target).parent().find('.product-autocomplete-spinner').length) {
							$('<i class="product-autoaomplete-spinner fa fa-spinner fa-spin"></i>').insertAfter(event.target);
						}
					},
					source: function source(req, response) {
						req.action = 'metro_product_search_autocomplete';
						$.ajax({
							dataType: "json",
							type: "POST",
							url: MetroObj.ajaxurl,
							data: req,
							success: function success(data) {
								response(data);
							}
						});
					},
					response: function response(event, ui) {
						$(event.target).parent().find('.product-autoaomplete-spinner').remove();
					}
				});
			}
		},
		search_popup: function search_popup() {
			$('.search-icon-area a').on("click", function(event) {
				event.preventDefault();
				$("#rdtheme-search-popup").addClass("open");
				$('#rdtheme-search-popup > form > input').focus();
			});
			$("#rdtheme-search-popup, #rdtheme-search-popup button.close").on("click keyup", function(event) {
				if (event.target == this || event.target.className == "close" || event.keyCode == 27) {
					$(this).removeClass("open");
				}
			});
		},
		vertical_menu: function vertical_menu() {
			$('.vertical-menu-btn').on('click', function(e) {
				e.preventDefault();
				$(this).closest('.vertical-menu-area').toggleClass("opened");
			});
		},
		vertical_menu_mobile: function vertical_menu_mobile() {
			ThemeHelper.add_vertical_menu_class();
			$(window).on('resize', function() {
				ThemeHelper.add_vertical_menu_class();
			});
			$('.vertical-menu').on('click', 'li.menu-item-has-children span.has-dropdown', function(e) {
				if ($(this).find('+ ul.sub-menu').length) {
					$(this).closest('li').toggleClass('submenu-opend');
					$(this).find('+ ul.sub-menu').slideToggle();
				}

				return false;
			});
		},
		mobile_menu: function mobile_menu() {
			$('#site-header .main-navigation nav').meanmenu({
				meanMenuContainer: '#meanmenu',
				meanScreenWidth: MetroObj.meanWidth,
				removeElements: "#site-header, .top-header-desktop",
				siteLogo: MetroObj.siteLogo,
				meanExpand: '<i class="flaticon-plus-symbol"></i>',
				meanContract: '<i class="flaticon-substract"></i>',
				meanMenuClose: '<i class="flaticon-unchecked"></i>',
				appendHtml: MetroObj.appendHtml
			});
		},
		mobile_menu_max_height: function mobile_menu_max_height() {
			var wHeight = $(window).height();
			wHeight = wHeight - 50;
			$('.mean-nav > ul').css('max-height', wHeight + 'px');
		},
		multi_column_menu: function multi_column_menu() {
			$('.main-navigation ul > li.mega-menu').each(function() {
				var items = $(this).find(' > ul.sub-menu > li').length;
				var bodyWidth = $('body').outerWidth();
				var parentLinkWidth = $(this).find(' > a').outerWidth();
				var parentLinkpos = $(this).find(' > a').offset().left;
				var width = items * 220;
				var left = width / 2 - parentLinkWidth / 2;
				var linkleftWidth = parentLinkpos + parentLinkWidth / 2;
				var linkRightWidth = bodyWidth - (parentLinkpos + parentLinkWidth);

				if (width / 2 > linkleftWidth) {
					$(this).find(' > ul.sub-menu').css({
						width: width + 'px',
						right: 'inherit',
						left: '-' + parentLinkpos + 'px'
					});
				} else if (width / 2 > linkRightWidth) {
					$(this).find(' > ul.sub-menu').css({
						width: width + 'px',
						left: 'inherit',
						right: '-' + linkRightWidth + 'px'
					});
				} else {
					$(this).find(' > ul.sub-menu').css({
						width: width + 'px',
						left: '-' + left + 'px'
					});
				}
			});
		},
		isotope: function isotope() {
			if (typeof $.fn.isotope == 'function' && typeof $.fn.imagesLoaded == 'function') {
				var $blogIsotopeContainer = $('.post-isotope');
				$blogIsotopeContainer.imagesLoaded(function() {
					$blogIsotopeContainer.isotope();
				});
				var $isotopeContainer = $('.rt-el-isotope-container');
				$isotopeContainer.imagesLoaded(function() {
					$isotopeContainer.each(function() {
						var $container = $(this).find('.rt-el-isotope-wrapper'),
							filter = $(this).find('.rt-el-isotope-tab a.current').data('filter');
						ThemeHelper.run_isotope($container, filter);
					});
				});
				$('.rt-el-isotope-tab a').on('click', function() {
					$(this).closest('.rt-el-isotope-tab').find('.current').removeClass('current');
					$(this).addClass('current');
					var $container = $(this).closest('.rt-el-isotope-container').find('.rt-el-isotope-wrapper'),
						filter = $(this).attr('data-filter');
					ThemeHelper.run_isotope($container, filter);
					return false;
				});
			}
		},
		slick_carousel: function slick_carousel() {
			if (typeof $.fn.slick == 'function') {
				$(".rt-slick-slider").each(function() {
					$(this).slick({
						rtl: MetroObj.rtl
					});
				});
				$(document).on('afterLoadMore afterInfinityScroll', function() {
					$(".product_loaded .rt-slick-slider").each(function() {
						$(this).slick({
							rtl: MetroObj.rtl
						});
					});
					$(".product_loaded").removeClass('product_loaded');
				});
			}
		},
		owl_carousel: function owl_carousel() {
			if (typeof $.fn.owlCarousel == 'function') {
				$(".owl-custom-nav .owl-next").on('click', function() {
					$(this).closest('.owl-wrap').find('.owl-carousel').trigger('next.owl.carousel');
				});
				$(".owl-custom-nav .owl-prev").on('click', function() {
					$(this).closest('.owl-wrap').find('.owl-carousel').trigger('prev.owl.carousel');
				});
				$(".rt-owl-carousel").each(function() {
					var options = $(this).data('carousel-options');

					if (MetroObj.rtl == 'yes') {
						options['rtl'] = true;
						options['navText'] = ["<i class='fa fa-angle-right'></i>", "<i class='fa fa-angle-left'></i>"];
					}

					$(this).owlCarousel(options);
				});
				$(".owl-numbered-dots .owl-numbered-dots-items span").on('click', function() {
					var index = $(this).data('num');
					var $owlParent = $(this).closest('.owl-wrap').find('.owl-carousel');
					$owlParent.trigger('to.owl.carousel', index);
					$owlParent.find('.owl-numbered-dots-items span').removeClass('active');
					$owlParent.find('.owl-numbered-dots-items [data-num="' + index + '"]').addClass('active');
				});
			}
		},
		countdown: function countdown() {
			if (typeof $.fn.countdown == 'function') {
				try {
					var day = MetroObj.day == 'Day' ? 'Day%!D' : MetroObj.day,
						hour = MetroObj.hour == 'Hour' ? 'Hour%!D' : MetroObj.hour,
						minute = MetroObj.minute == 'Minute' ? 'Minute%!D' : MetroObj.minute,
						second = MetroObj.second == 'Second' ? 'Second%!D' : MetroObj.second;
					$('.rtjs-coutdown').each(function() {
						var $CountdownSelector = $(this).find('.rtjs-date');
						var eventCountdownTime = $CountdownSelector.data('time');
						$CountdownSelector.countdown(eventCountdownTime).on('update.countdown', function(event) {
							$(this).html(event.strftime('' + '<div class="rt-countdown-section"><div class="rtin-count">%D</div><div class="rtin-text">' + day + '</div></div>' + '<div class="rt-countdown-section"><div class="rtin-count">%H</div><div class="rtin-text">' + hour + '</div></div>' + '<div class="rt-countdown-section"><div class="rtin-count">%M</div><div class="rtin-text">' + minute + '</div></div>' + '<div class="rt-countdown-section"><div class="rtin-count">%S</div><div class="rtin-text">' + second + '</div></div>'));
						}).on('finish.countdown', function(event) {
							$(this).html(event.strftime(''));
						});
					});
					$('.rtjs-coutdown-2').each(function() {
						var $CountdownSelector = $(this).find('.rtjs-date');
						var eventCountdownTime = $CountdownSelector.data('time');
						$CountdownSelector.countdown(eventCountdownTime).on('update.countdown', function(event) {
							$(this).html(event.strftime('' + '<div class="rt-countdown-section-top">' + '<div class="rt-countdown-section"><div class="rt-countdown-section-inner"><div class="rtin-count">%D</div><div class="rtin-text">' + day + '</div></div></div>' + '<div class="rt-countdown-section ml10"><div class="rt-countdown-section-inner"><div class="rtin-count">%H</div><div class="rtin-text">' + hour + '</div></div></div>' + '</div><div class="rt-countdown-section-bottom">' + '<div class="rt-countdown-section"><div class="rt-countdown-section-inner"><div class="rtin-count">%M</div><div class="rtin-text">' + minute + '</div></div></div>' + '<div class="rt-countdown-section ml10"><div class="rt-countdown-section-inner"><div class="rtin-count">%S</div><div class="rtin-text">' + second + '</div></div></div></div>'));
						}).on('finish.countdown', function(event) {
							$(this).html(event.strftime(''));
						});
					});
				} catch (err) {
					console.log('Countdown : ' + err.message);
				}
			}
		},
		magnific_popup: function magnific_popup() {
			if (typeof $.fn.magnificPopup == 'function') {
				$('.rt-video-popup').magnificPopup({
					disableOn: 700,
					type: 'iframe',
					mainClass: 'mfp-fade',
					removalDelay: 160,
					preloader: false,
					fixedContentPos: false
				});
			}
		}
	};

	var WooCommerce = {
		meta_reloation: function meta_reloation() {
			$('.product-type-variable .single-product-top-2 .product_meta-area-js, .product-type-variable .single-product-top-3 .product_meta-area-js').insertAfter('form.variations_form table.variations');
		},
		sticky_product_thumbnail: function sticky_product_thumbnail() {
			if (typeof $.fn.stickySidebar == 'function') {
				var screenWidth = $('body').outerWidth();

				if (screenWidth > 991) {
					var top = 20;

					if (MetroObj.hasStickyMenu == 1) {
						top += $('.main-header-sticky-wrapper').outerHeight();
					}

					if (MetroObj.hasAdminBar == 1) {
						top += $('#wpadminbar').outerHeight();
					}

					$('.single-product-top-2 .rtin-left > div').stickySidebar({
						topSpacing: top
					});
				}
			}
		},
		quantity_change: function quantity_change() {
			$(document).on('click', '.quantity .input-group-btn .quantity-btn', function() {
				var $input = $(this).closest('.quantity').find('.input-text');

				if ($(this).hasClass('quantity-plus')) {
					$input.trigger('stepUp').trigger('change');
				}

				if ($(this).hasClass('quantity-minus')) {
					$input.trigger('stepDown').trigger('change');
				}
			});
		},
		slider_nav: function slider_nav() {
			$('.rt-el-product-slider').each(function() {
				var $target = $(this).find('.owl-custom-nav .owl-nav button.owl-prev, .owl-custom-nav .owl-nav button.owl-next'),
					$img = $(this).find('.rtin-thumb-wrapper').first();

				if ($img.length) {
					var height = $img.outerHeight();
					height = height / 2 - 24;
					$target.css('top', height + 'px');
				}
			});
		},
		dokan_my_order_responsive_table: function dokan_my_order_responsive_table() {
			$('.shop_table.my_account_orders').wrap("<div class='table-responsive'></div>");
		},
		wishlist_icon: function wishlist_icon() {
			$(document).on('click', '.rdtheme-wishlist-icon', function() {
				if ($(this).hasClass('rdtheme-add-to-wishlist')) {
					var $obj = $(this),
						productId = $obj.data('product-id'),
						afterTitle = $obj.data('title-after');
					var data = {
						'action': 'metro_add_to_wishlist',
						'context': 'frontend',
						'nonce': $obj.data('nonce'),
						'add_to_wishlist': productId
					};
					$.ajax({
						url: MetroObj.ajaxurl,
						type: 'POST',
						data: data,
						beforeSend: function beforeSend() {
							$obj.find('.wishlist-icon').hide();
							$obj.find('.ajax-loading').show();
							$obj.addClass('rdtheme-wishlist-ajaxloading');
						},
						success: function success(data) {
							if (data['result'] != 'error') {
								$obj.find('.ajax-loading').hide();
								$obj.removeClass('rdtheme-wishlist-ajaxloading');
								$obj.find('.wishlist-icon').removeClass('fa-heart-o').addClass('fa-heart').show();
								$obj.removeClass('rdtheme-add-to-wishlist').addClass('rdtheme-remove-from-wishlist');
								$obj.attr('title', afterTitle);
							} else {
								console.log(data['message']);
							}
						}
					});
					return false;
				}
			});
		}
	};

	function loadmore_n_infinityscroll() {
		var loadMoreWrapper = $('.rdtheme-loadmore-wrapper'),
			infinityScrollWrapper = $('.rdtheme-infscroll-wrapper');

		if (loadMoreWrapper.length) {
			loadMore(loadMoreWrapper);
		}

		if (infinityScrollWrapper.length) {
			infinityScroll(infinityScrollWrapper);
		}

		function loadMore($wrapper) {
			var button = $('.rdtheme-loadmore-btn'),
				shopData = $('.rdtheme-loadmore-data'),
				maxPage = shopData.data('max'),
				query = shopData.attr('data-query'),
				CurrentPage = 1;
			button.on('click', button, function() {
				var data = {
					'action': 'rdtheme_loadmore',
					'context': 'frontend',
					'nonce': shopData.data('nonce'),
					'query': query,
					'view': $('body').hasClass('product-list-view') ? 'list' : 'grid',
					'paged': CurrentPage
				};
				$.ajax({
					url: MetroObj.ajaxurl,
					type: 'POST',
					data: data,
					beforeSend: function beforeSend() {
						disableBtn(button);
					},
					success: function success(data) {
						if (data) {
							CurrentPage++;
							$wrapper.append(data);
							WcUpdateResultCount($wrapper);

							if (CurrentPage == maxPage) {
								removeBtn(button);
							} else {
								enableBtn(button);
							}

							$(document).trigger("afterLoadMore");
						} else {
							removeBtn(button);
						}
					}
				});
				return false;
			});
		}

		function infinityScroll($wrapper) {
			var canBeLoaded = true,
				shopData = $('.rdtheme-loadmore-data'),
				icon = $('.rdtheme-infscroll-icon'),
				query = shopData.attr('data-query'),
				CurrentPage = 1;
			$(window).on('scroll load', function() {
				if (!canBeLoaded) {
					return;
				}

				// Price Filter
				var _min_price = jQuery('#min_price').val();
				var _max_price = jQuery('#max_price').val();
				if(_max_price!='' ){
					query = JSON.parse(query);
					query['meta_query'] = [
						{
							'key': '_price',
							'value': [_min_price, _max_price],
							'compare': 'BETWEEN',
							'type': 'NUMERIC',
						}
					];
					query = JSON.stringify(query);
				}

				var data = {
					'action': 'rdtheme_loadmore',
					'context': 'frontend',
					'nonce': shopData.data('nonce'),
					'query': query,
					'paged': CurrentPage
				};

				if (isScrollable($wrapper)) {
					$.ajax({
						url: MetroObj.ajaxurl,
						type: 'POST',
						data: data,
						beforeSend: function beforeSend() {
							canBeLoaded = false;
							icon.show();
						},
						success: function success(data) {
							if (data) {
								CurrentPage++;
								canBeLoaded = true;
								$wrapper.append(data);
								WcUpdateResultCount($wrapper);
								icon.hide();
								$(document).trigger("afterInfinityScroll");
							} else {
								icon.remove();
							}
						}
					});
				}
			});
		}

		function isScrollable($wrapper) {
			var ajaxVisible = $wrapper.offset().top + $wrapper.outerHeight(true),
				ajaxScrollTop = $(window).scrollTop() + $(window).height();

			if (ajaxVisible <= ajaxScrollTop && ajaxVisible + $(window).height() > ajaxScrollTop) {
				return true;
			}

			return false;
		}

		function WcUpdateResultCount($wrapper) {
			var count = $($wrapper).find('.product').length;
			$('.wc-last-result-count').text(count);
		}

		function disableBtn(button) {
			button.attr('disabled', 'disabled');
			button.find('.rdtheme-loadmore-btn-text').hide();
			button.find('.rdtheme-loadmore-btn-icon').show();
		}

		function enableBtn(button) {
			button.find('.rdtheme-loadmore-btn-icon').hide();
			button.find('.rdtheme-loadmore-btn-text').show();
			button.removeAttr('disabled');
		}

		function removeBtn(button) {
			button.parent('.rdtheme-loadmore-btn-area').remove();
		}
	}

	function widthgen() {
		$(window).on('load resize', elementWidth);

		function elementWidth() {
			$('.elementwidth').each(function() {
				var $container = $(this),
					width = $container.outerWidth(),
					classes = $container.attr("class").split(' ');
				var classes1 = startWith(classes, 'elwidth');
				classes1 = classes1[0].split('-');
				classes1.splice(0, 1);
				var classes2 = startWith(classes, 'elmaxwidth');
				classes2.forEach(function(el) {
					$container.removeClass(el);
				});
				classes1.forEach(function(el) {
					var maxWidth = parseInt(el);

					if (width <= maxWidth) {
						$container.addClass('elmaxwidth-' + maxWidth);
					}
				});
			});
		}

		function startWith(item, stringName) {
			return $.grep(item, function(elem) {
				return elem.indexOf(stringName) == 0;
			});
		}
	}

	loadmore_n_infinityscroll();
	widthgen();

	function content_ready_scripts() {
		Theme.countdown();
		Theme.magnific_popup();
		Theme.vertical_menu();
		Theme.vertical_menu_mobile();
		Theme.category_search_dropdown();
		Theme.rt_offcanvas_menu();
		Theme.rt_offcanvas_menu_layout();
		WooCommerce.dokan_my_order_responsive_table();
	}

	function content_load_scripts() {
		Theme.isotope();
		Theme.owl_carousel();
		Theme.slick_carousel();
		Theme.ripple_effect();
	}

	$(document).ready(function() {
		Theme.scroll_to_top();
		Theme.sticky_menu();
		Theme.mobile_menu();
		Theme.multi_column_menu();
		Theme.search_popup();
		WooCommerce.quantity_change();
		WooCommerce.wishlist_icon();
		WooCommerce.meta_reloation();
		WooCommerce.sticky_product_thumbnail();
		content_ready_scripts();
	});
	$(window).on('load', function() {
		content_load_scripts();
		Theme.preloader();
	});
	$(window).on('load resize', function() {
		Theme.mobile_menu_max_height();
		WooCommerce.slider_nav();
	});
	$(window).on('elementor/frontend/init', function() {
		if (elementorFrontend.isEditMode()) {
			elementorFrontend.hooks.addAction('frontend/element_ready/widget', function() {
				content_ready_scripts();
				content_load_scripts();
			});
		}
	});

}());