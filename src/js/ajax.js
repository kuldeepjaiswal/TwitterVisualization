function initialize(latitudes,longitudes,locations) {
  var latitude = parseFloat(latitudes);
  var longitude = parseFloat(longitudes);
  var mapOptions = {
    zoom: 17,
    center: new google.maps.LatLng(latitude,longitude)
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'),
                                mapOptions);
  var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0], locations[i][3]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
 }

$(function() {
	$("#map-canvas").hide(100);
    $("#properties").hide(100);
    
	$.ajax({
        url: 'ajax_files/placesearch.php',
        type: 'POST',
        data: "funcname=cities&countryid="+0,
        dataType: 'json',
        success: function( messages) {
            for(var id in messages) {
				var o = new Option(messages[id], id);
				/// jquerify the DOM object 'o' so we can use the html method
				$(o).html(messages[id]);
				$("#city-select").append(o);
				//jQuery('#' + id).html(messages[id]);
            }
        }
    });

var availableTags = [];
var locations = {};
$( "#city-select" ).change(function() {
	$.ajax({
        url: 'ajax_files/placesearch.php',
        type: 'POST',
        data: "funcname=locations&cityid="+$(this).val(),
        dataType: 'json',
		success: function( messages) {
			$('#location option[value!=""]').remove();
			for(var id in messages) {
				var o = new Option(messages[id], id);
				/// jquerify the DOM object 'o' so we can use the html method
				$(o).html(messages[id]);
				$("#location").append(o);
				//jQuery('#' + id).html(messages[id]);
            }
        }
    });
});

$("#GridList").click(function(){
	$("#properties").show(1000);
	$("#properties-map").hide(1000);
});
$("#MapList").click(function(){
	$("#properties-map").show(1000);
	$("#properties").hide(1000);
});

var lati;
var longi;
var cooks = [];


$(".page-sub-page page-listing-lines page-search-results").ready(function(){
	var b = qs["location"];
	if(!b)
	{
		b = 1;
	}
	$.when($.ajax({
        url: 'ajax_files/placesearch.php',
        type: 'POST',
        data: "funcname=mappoints&locationid="+b,
        dataType: 'json',
		success: function( messages) {
			for(var id in messages) {
				lati = id;
				longi = messages[id];
			}
				$.ajax({
			url: 'ajax_files/placesearch.php',
			type: 'POST',
			data: "funcname=cookspoint&locationid="+b,
			dataType: 'json',
			success: function( messages) {
				cooks = [];
				var results_js="";
				var cnt = 0;
				for(var id in messages) {
				    if(cnt%3==0)
					{
						results_js += "<div class='row'>";
					}
					results_js += "<div class='col-md-4 col-sm-4'>";
					var box = "<div class='property equal-height'><figure class='tag status'>"+messages[id][7]+" stars</figure><a href='chef_menu.php?id="+id+"'><div class='property-image'><img src='img/uploads/profile-pic/"+messages[id][10]+"'></div><div class='overlay'><div class='info'><h3>"+messages[id][0]+" "+messages[id][13]+"</h3><h3>"+messages[id][11]+"</h3><figure>"+messages[id][12]+"</figure></div><ul class='additional-info'><li><header>Category:</header><figure>"+messages[id][9]+"</figure></li><li><header>Min. Amount:</header><figure>Rs "+messages[id][8]+"</figure></li><li><header>Time:</header><figure>"+messages[id][4]+"</figure></li></ul></div></div></a><!-- /.property -->";
					results_js+=box;
					results_js += "</div>"
					var details = [box,messages[id][1],messages[id][2],cnt];
					cooks.push(details);
					if(cnt%3==2)
					{
						results_js += "</div>";
					}
					cnt = cnt+1;
					
				}
				initialize(lati,longi,cooks);
				$("#results_js").html(results_js);
				$("#search-count").html(cnt);
			}
			});	
		}
		})).then(function(){
					
			$("#map-canvas").show(1000);
			
		});
});

$("#search-button").click(function(){
	$(".error1").hide();
	$(".error").hide();
    var a = $("#city-select").val();
    var b = $("#location").val();
  if(a && b)
  {
  
	$.when($.ajax({
        url: 'ajax_files/placesearch.php',
        type: 'POST',
        data: "funcname=mappoints&locationid="+b,
        dataType: 'json',
		success: function( messages) {
			for(var id in messages) {
				lati = id;
				longi = messages[id];
			}
				$.ajax({
			url: 'ajax_files/placesearch.php',
			type: 'POST',
			data: "funcname=cookspoint&locationid="+b,
			dataType: 'json',
			success: function( messages) {
				cooks = [];
				var cnt = 1;
				for(var id in messages) {
					
					var details = [messages[id][0],messages[id][1],messages[id][2],cnt];
					cooks.push(details);
					cnt = cnt+1;
					
				}
				initialize(lati,longi,cooks);
			}
			});	
		}
		})).then(function(){
					
			$('html,body').animate({
			scrollTop: $("#searchcont").offset().top},
			'slow');
			$("#map-canvas").show(1000);
	
		});
  }
  else if(a)
  {
	$(".error1").show();
	return false;
  }
  else if(b)
  {
	$(".error").show();
	return false;
  }
  else
  {
	$(".error").show();
	$(".error1").show();
	return false;
  }
});
});