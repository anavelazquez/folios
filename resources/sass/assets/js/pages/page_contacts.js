var ContactPage = function () {

    return {
        
    	//Basic Map
        initMap: function () {
			var map;
			$(document).ready(function(){
			  map = new GMaps({
				div: '#map',
				scrollwheel: false,				
				lat: 19.538390,
				lng: -96.908680,
                                zoom: 18,
                                title: 'Poder Judicial del Estado de Veracruz de Ignacio de la Llave'

			  });
			  
			  var marker = map.addMarker({
				
		       });
			});
        },

        //Panorama Map
        initPanorama: function () {
		    var panorama;
		    $(document).ready(function(){
		      panorama = GMaps.createPanorama({
		        el: '#panorama',
		        lat : 19.538178,
		        lng : -96.908079        
		      });
		    });
		}        

    };
}();