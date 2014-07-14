(function($, args){
	$('#toc').toc({
		'selectors': args.selectors, //elements to use as headings
		'container': 'main', //element to find all selectors in
		'smoothScrolling': true, //enable or disable smooth scrolling on click
		'prefix': 'toc', //prefix for anchor tags and class names
		'headerText': function(i, heading, $heading) { //custom function building the header-item text
			return $heading.text();
		}
	});
}(jQuery, WSUWP_TOC));