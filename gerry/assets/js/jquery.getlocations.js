/*
**  jquery.getlocations.js -- jQuery plugin for location handling
**  Licensed under GPL <http://www.gnu.org/licenses/gpl.txt>
**
*/
(function($){
    
    $.getlocations = function(href_to_go, target, params) {
		//object allready existing?
		if(typeof window.Get_Locations == 'undefined') {
			var loc_obj = window.location;
			var get_locations = new Object();
			get_locations['location'] = loc_obj;
			get_locations['baseref'] = $('base')?$('base').attr('href'):'';
			//baseref cut or expand to folder-level with "/" trailered
			//if there is a hint for a file-path (exg .html .php .-htm) then cut the file
			// http://example.com/index.php => http://example.com/
			// http://example.com/de/en => http://example.com/de/en/ 
			get_locations['baseref_ie'] = get_locations['baseref'].replace(/([^\/])$|\/?[^\/]+\.(php|html|htm)$/i, '$1/')
			get_locations['hash'] = loc_obj.hash;
			get_locations['host'] = loc_obj.host;
			get_locations['hostname'] = loc_obj.hostname;
			get_locations['href'] = loc_obj.href;
			get_locations['pathname'] = loc_obj.pathname;
			get_locations['port'] = loc_obj.port;
			get_locations['protocol'] = loc_obj.protocol;
			get_locations['search'] = loc_obj.search;
			get_locations['ssl'] = loc_obj.protocol.match(/https/);
			// get pathes
			get_locations['pathes'] = new Array();
			get_locations['pathes'] = loc_obj.pathname.split("/");
			get_locations['filename'] = get_locations['pathes'][get_locations['pathes'].length-1];
			get_locations['topleveldomain'] = loc_obj.hostname.match(/[a-z]+$/i)
			
			// get params
			var params = loc_obj.search.substr(1,loc_obj.search.length).split("&");
			var key_value = new Object();
			for (var index in params) {
			var param_split = params[index].split("=");
				key_value[param_split[0]] = param_split[1];
			}
			get_locations['params'] = key_value;
			
			window.Get_Locations = get_locations;
		}
        if(typeof href_to_go == 'string' && href_to_go != ''){
			//differ between absolute-path and relative-path
			href_to_go = (href_to_go.match(/^\/|^http/i))? href_to_go : Get_Locations['baseref_ie']+href_to_go;
			if(typeof params == 'string' && params != '')window.open(href_to_go, target, params);
			else if(typeof target == 'string' && target != '')window.open(href_to_go, target);
			else window.location.href = href_to_go;
		}
        return window.Get_Locations;
    }
    /*
    $.fn.getlocations = function () {
        return this.each(function() {
            var loc_obj = window.location;
            
            var get_locations = new Object();
                get_locations['hash'] = loc_obj.hash;
                get_locations['host'] = loc_obj.host;
                get_locations['hostname'] = loc_obj.hostname;
                get_locations['href'] = loc_obj.href;
                get_locations['pathname'] = loc_obj.pathname;
                get_locations['port'] = loc_obj.port;
                get_locations['protocol'] = loc_obj.protocol;
                get_locations['search'] = loc_obj.search;
                
            // get pathes
                get_locations['pathes'] = new Array();
                get_locations['pathes'] = loc_obj.pathname.split("/");
                
            // get params
                var params = loc_obj.search.substr(1,loc_obj.search.length).split("&");
                
                for (var index in params) {
                    var param_split = params[index].split("=");
                    
                    var key_value = new Object();
                        key_value["'" + param_split[0] + "'"] = param_split[1];
                }
                
                get_locations['params'] = key_value;
            
            return get_locations;
        })
    }
    */
})(jQuery);