function juzgado(id)
{

var nombre,archivo,direccion,latitud,longitud,telefono='';
                        

    $.ajax( 'getJuzgado',{
    	               type: 'GET',dataType: 'json',data:{id:id}, async: false ,success: function( data ) 
                        {                                           
                         
                         nombre=data[0]['centrotrabajo'];
                         archivo=data[0]['imagen'];
                         direccion=data[0]['domicilio'];
                         latitud=data[0]['latitud'];
                         longitud=data[0]['longitud'];
                         distrito=data[0]['distrito']
    
    					
                                               

                        },
                      error: function(  )
                        {
                        	console.log( 'respuesta incorrecta ajax' );
                        }
                    });

     $.ajax( 'getTelefonos',{
                     type: 'GET',dataType: 'json',data:{id:id}, async: false ,success: function( data ) 
                        {    

                        if(data!=null)
                                               {
                                                   $.each (data, function (i) 
                                                   {

                                                        telefono+='LADA:'+data[i].lada+' Telefono:'+data[i].telefono+' ';
                                                   });
                                                 }                                       
                         
                       
    
              
                                               

                        },
                      error: function(  )
                        {
                          console.log( 'respuesta incorrecta ajax' );
                        }
                    });



$("#distritoNombre").html(distrito);	
$("#telefono").html(telefono);  
$("#centroNombre").html(nombre);	
$("#direccion").html(direccion);	



$("#imgJuz").attr("src", 'img/juzgados/'+archivo);	



          var map;        
            var myCenter=new google.maps.LatLng(latitud,longitud);
var marker=new google.maps.Marker({
    position:myCenter
});

function initialize() {
  var mapProp = {
      center:myCenter,
      zoom: 18,
      draggable: false,
      scrollwheel: false,
      mapTypeId:google.maps.MapTypeId.ROADMAP,
       zoomControl: true,
  mapTypeControl: true,
  scaleControl: true,
  streetViewControl: true,
  rotateControl: true,
  scrollwheel: true,
  mapTypeControl: true,
  cursor:true


  };


  
  map=new google.maps.Map(document.getElementById("map"),mapProp);
  marker.setMap(map);
    
  google.maps.event.addListener(marker, 'click', function() {
      
    infowindow.setContent(contentString);
    infowindow.open(map, marker);
    
  }); 
};

initialize()
$('#modalmapa').on('show.bs.modal', function() {
   //Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)
   resizeMap();
})

function resizeMap() {
   if(typeof map =="undefined") return;
   setTimeout( function(){resizingMap();} , 400);
}

function resizingMap() {
   if(typeof map =="undefined") return;
   var center = map.getCenter();
   google.maps.event.trigger(map, "resize");
   map.setCenter(center); 
}


}


 
 $(document).ready(function() {
        
            var map;        
            var myCenter=new google.maps.LatLng(53, -1.33);
var marker=new google.maps.Marker({
    position:myCenter
});

function initialize() {
  var mapProp = {
      center:myCenter,
      zoom: 16,
      draggable: false,
      scrollwheel: false,
      mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  
  map=new google.maps.Map(document.getElementById("map"),mapProp);
  marker.setMap(map);
    
  google.maps.event.addListener(marker, 'click', function() {
      
    infowindow.setContent(contentString);
    infowindow.open(map, marker);
    
  }); 
};
google.maps.event.addDomListener(window, 'load', initialize);

google.maps.event.addDomListener(window, "resize", resizingMap());

$('#modalmapa').on('show.bs.modal', function() {
   //Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)
   resizeMap();
})

function resizeMap() {
   if(typeof map =="undefined") return;
   setTimeout( function(){resizingMap();} , 400);
}

function resizingMap() {
   if(typeof map =="undefined") return;
   var center = map.getCenter();
   google.maps.event.trigger(map, "resize");
   map.setCenter(center); 
}
        
        });
        


      