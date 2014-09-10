(function($, window){

	var GAcode = window.wsu_analytics.tracker_id;
	var _DN    = window.wsu_analytics.domain;
	var _CP    = false;

	var site_data = [
		{
			"element":"a[href^='http']:not([href*='wsu.edu'])",
			"options":{
				"mode":"event,_link",
				"category":"outbound"
			}
		},
		{
			"element":"a[href*='wsu.edu']:not([href*='**SELF_DOMAIN**'])",
			"options":{
				"skip_internal":"true",
				"mode":"event,_link",
				"category":"internal"
			}
		},
		{
			"element":"a[href*='zzusis.wsu.edu'],a[href*='portal.wsu.edu'],a[href*='applyweb.com/public/inquiry']",
			"options":{
				"skip_internal":"true",
				"mode":"event,_link",
				"category":"internal",
				"skip_campaign":"true",
				"overwrites":"true"
			}
		},
		{
			"element":".youtube,.youtube2",
			"options":{
				"action":"youtube",
				"category":"videos",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.jpg']",
			"options":{
				"action":"jpg",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.zip']",
			"options":{
				"action":"zips",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.tiff']",
			"options":{
				"action":"tiff",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.tif']",
			"options":{
				"action":"tiff",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.bin']",
			"options":{
				"action":"bin",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.Bin']",
			"options":{
				"action":"bin",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.eps']",
			"options":{
				"action":"eps",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.gif']",
			"options":{
				"action":"gif",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.png']",
			"options":{
				"action":"png",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.ppt']",
			"options":{
				"action":"ppt",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.pdf']",
			"options":{
				"action":"pdf",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.doc']",
			"options":{
				"action":"doc",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.docx']",
			"options":{
				"action":"docx",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='facebook.com']",
			"options":{
				"category":"Social",
				"action":"Facebook",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='.rss']",
			"options":{
				"category":"Feed",
				"action":"RSS",
				"overwrites":"true"
			}
		},
		{
			"element":"a[href*='mailto:']",
			"options":{
				"category":"email",
				"overwrites":"true"
			}
		},
		{
			"element":".track.outbound",
			"options":{
				"category":"outbound",
				"overwrites":"true"
			}
		},
		{
			"element":".track.internal",
			"options":{
				"skip_internal":"true",
				"category":"internal",
				"noninteraction":"true",
				"overwrites":"true"
			}
		},
		{
			"element":".track.jpg",
			"options":{
				"action":"jpg",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":".track.zip",
			"options":{
				"action":"zips",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":".track.tiff",
			"options":{
				"action":"tiff",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":".track.bin",
			"options":{
				"action":"bin",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":".track.eps",
			"options":{
				"action":"eps",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":".track.gif",
			"options":{
				"action":"gif",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":".track.png",
			"options":{
				"action":"png",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":".track.ppt",
			"options":{
				"action":"ppt",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":".track.pdf",
			"options":{
				"action":"pdf",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":".track.doc",
			"options":{
				"action":"doc",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":".track.docx",
			"options":{
				"action":"docx",
				"category":"download",
				"label":"function(ele){ return ( ($(ele).attr('title')!='' && typeof($(ele).attr('title')) !=='undefined' ) ? $(ele).attr('title') : $(ele).attr('href') ) }",
				"overwrites":"true"
			}
		},
		{
			"element":".track.rss",
			"options":{
				"category":"Feed",
				"action":"RSS",
				"overwrites":"true"
			}
		},
		{
			"element":".track.internal",
			"options":{
				"skip_internal":"true",
				"category":"internal",
				"noninteraction":"true",
				"overwrites":"true"
			}
		},
		{
			"element":".track.email",
			"options":{
				"category":"email",
				"overwrites":"true"
			}
		},
		{
			"element":"#siteID",
			"options":{
				"category":"jTrackEasterEgg",
				"label":"function(){ var result; $.each($.browser, function(i, val) { result += ' ' + i + ':' + val }); return result; }",
				"alias":"jTrackEasterEgg"
			}
		},
		{
			"element":"a.modal",
			"options":{
				"category":"modal",
				"skip_internal":"true",
				"mode":"event",
				"overwrites":"true"
			}
		}
	];

	function tracker(data){
		$.jtrack.defaults.debug.run = false;
		$.jtrack.defaults.debug.v_console = false;
		$.jtrack.defaults.debug.console = false;
		$.jtrack({ load_analytics:{account:GAcode},options:jQuery.extend({},(_DN!==false?{'domainName':_DN}:{}),(_CP!==false?{'cookiePath':_CP}:{})), trackevents:data });
	}
	tracker( site_data );

})(jQuery, window);
