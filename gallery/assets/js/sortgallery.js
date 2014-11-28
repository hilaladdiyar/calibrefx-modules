jQuery(document).ready(function($){
	var $container = $('.sortgallery-wrapper');

	if ($container.length && !$container.hasClass('not-to-isotope')) {

		$(window).on('load', function() {
			$container.isotope({
				itemSelector : '.sortgallery-item',
				layoutMode : 'fitRows'
			});
		});

		// Init filters
		if ($('#cat-filter').length) _items_filter($('#cat-filter'), 'data-categories', $container);
		if ($('#tag-filter').length) _items_filter($('#tag-filter'), 'data-tags', $container);
	} 

	$('a.sortgallery-link, a.fancybox').fancybox();
	
	// Add filter event
	function _items_filter($el, $data, $container) {

		if($el.hasClass('not-to-filter')) return;

		// Add all filter class
		$el.addClass('item-filter');

		// Add categories to item classes
		$($container).each(function(i) {
			var 
				$this = $(this);
				$this.addClass($this.attr($data));
		});

		$el.on('click', 'a', function(e){
			var 
				$this   = $(this),
				$option = $this.attr($data);

			// Add active filter class
			$('.item-filter').removeClass('active-filter');
			$el.addClass('active-filter');
			$('.item-filter:not(.active-filter) li a').removeClass('active');
			$('.item-filter:not(.active-filter) li:first-child a').addClass('active');

			// Add/remove active class for this filter
			$el.find('a').removeClass('active');
			$this.addClass('active');

			if ($option) {
				if ($option !== '*') $option = $option.replace($option, '.' + $option)
				$container.isotope({ filter : $option });
			}

			e.preventDefault();

		});

		$el.find('a').first().addClass('active');
	}
	
	$('.gallery-image').lazyload({
		effect : "fadeIn"
	});
});

