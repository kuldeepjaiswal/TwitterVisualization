<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <title>Geolocation - finds your current location and displays tweets on the map</title>
    <!--<link rel="stylesheet" href="../css/base.css" type="text/css" media="screen"> -->
    <style>
        html,body
        {
            height: 100%;
            width: 100%;
            margin: 0px;
            padding: 0px;


        }

        #map{
            /*
            width: 700px;
            height: 500px;
            margin: 50px auto;
            */

            height: 83%;
            width: 95%;
            margin: 10px auto;
            border-style: solid;
            border-width: 5px;
            border-color: #a57216;
            border-spacing: 5px;
            border-collapse: separate;

        }
    </style>
    <!--[if lte IE 8]>
    <!-- this is the jquery library -->
    <script src="js/jquery-2.1.3.js"></script>
    <![endif]-->
</head>

<body>


    <header role="banner">
        <center><h1>Geolocation Using native geolocation in web browsers to find the user's current position and Showing twitter feeds on the map</h1>
        </center>
    </header>




            <div id="map"></div>

            <!--<script src="http://www.google.com/jsapi?key=ABQIAAAAlJFc1lrstqhgTl3ZYo38bBQcfCcww1WgMTxEFsdaTsnOXOVOUhTplLhHcmgnaY0u87hQyd-n-kiOqQ"></script>-->
            <script>

                //request and update points every 4 seconds
                var latitude,longitude;
                var markers = [100];
                var index=0;
                var map;


                function startmarkers()
                {
                    refreshIntervalId = setInterval("requestPoints()", 8000);//interval for tweet fetch
                    //interval for new marker animation
                }




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



                function clearMarkers() {
                    for (var i = 0; i < markers.length; i++) {
                        markers[i].setMap(null);
                    }
                    markers = [];
                }

                function drop() {
                    clearMarkers();
                    for (var i = 0; i < neighborhoods.length; i++) {
                        window.setTimeout(function() {
                            addMarker(neighborhoods[i]);
                        }, i * 200);
                    }
                    iterator = 0;
                }

                function addMarker(url,time,user,position,markerText) {
                    console.log("dsdsdads");
                    var marker=new google.maps.Marker({
                        position: position,
                        map: map,
                        animation: google.maps.Animation.DROP,
                        title: user
                    });

                    console.log(marker);
                    markers[index++]=marker;


                    var contentString = '<div id="content">'+
                        '<div id="siteNotice" >'+
                        '<img src="'+url+'" style="float:left;"/>'+
                        '</div>'+
                        '<div style="margin-left:20%;">'+
                        '<h1 id="firstHeading" class="firstHeading">User: '+user+'</h1>'+
                        '<h3 id="firstHeading" class="firstHeading">Time: '+time+'</h3>'+
                        '</div>'+
                        '<div id="bodyContent">'+
                        '<p>'+markerText+'</p>'+
                        '</div>'+
                        '</div>';




                    google.maps.event.addListener(marker, 'click', function() {
                        new google.maps.InfoWindow({
                            content: contentString
                        }).open(map,marker);
                    });

                }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                function requestPoints() {
                    //alert("in function");
                    $.ajax({
                        url:'parser.php',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            latitude: latitude,
                            longitude: longitude,
                            keyword: ""
                        },
                        success: function (positions) {
                            console.log(positions);
                            updatePoints(positions);

                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            console.log(errorThrown);
                        }
                    });
                }


                function updatePoints (positions) {
                    console.log('updating markers');
                    var latitude;
                    var longitude;
                    var length=positions.length;
                    console.log(length);


                    for (var i=0; i < length; i++) {

                        //console.log(positions[i][3],positions[i][4]);
                        console.log((typeof positions[i][3])+","+(typeof positions[i][4]));
                        var latLong = new google.maps.LatLng(positions[i][3], positions[i][4]);
                        console.log(latLong);
                        var text=positions[i][5];


                        addMarker(positions[i][0],positions[i][1],positions[i][2],latLong,text);

                        console.log("done");





                    }

                    /* for (var i=0; i < length; i++) {
                     latitude = positions[i][3];
                     longitude = positions[i][4];
                     position = map.createPosition({
                     lat: latitude,
                     lng: longitude
                     });
                     markers[positions[i].registration].setPosition(position);
                     };*/
                    if (positions.length > 1) {
                        //map.fitZoom();
                    } else {
                        //map.setCenter (lttd, lgtd);
                    }
                }

                // Get some JSON data from /data.php with jQuery (AJAX)

                // $.get("/data.php", function(data) {
                //   var parseData = $.parseJSON(data);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





                function initialize() {






                    var markerText;

                    markOutLocation = function (lat, long) {
                            console.log((typeof lat)+","+(typeof long));



                        // Create map
                        var mapOptions = {
                            zoom: 13,
                            center: new google.maps.LatLng(lat, long)
                        };
                        map = new google.maps.Map(document.getElementById("map"),mapOptions);


                        var latLong = new google.maps.LatLng(lat, long);
                        console.log(latLong);


                        var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + "00FF00",
                            new google.maps.Size(21, 34),
                            new google.maps.Point(0,0),
                            new google.maps.Point(10, 34));

                         marker = new google.maps.Marker({
                                position: latLong,
                                map: map,
                                animation: google.maps.Animation.DROP,
                                title: 'Current Location',
                                icon: pinImage

                            });




                        google.maps.event.addListener(marker, 'click', function() {
                            new google.maps.InfoWindow({
                                content: markerText
                            }).open(map,marker);
                        });



                        ///////////////////////////////cirle
                        var circleOptions = {
                            strokeColor: '#FF0000',
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: '#FF0000',
                            fillOpacity: 0.35,
                            map: map,
                            center: new google.maps.LatLng(lat, long),
                            radius: 8000
                        };
                        // Add the circle for this city to the map.
                        cityCircle = new google.maps.Circle(circleOptions);


                        timeoutIntervalId = setTimeout("startmarkers()", 4000);//interval for tweet fetch


                    };
                    //map.setUIToDefault();



                    // Check for geolocation support
                    if (navigator.geolocation) {
                        // Get current position
                        navigator.geolocation.getCurrentPosition(function (position) {
                                // Success!
                                markerText= "<h2>You are here</h2><p>Nice with geolocation, ain't it?</p>";
                                markOutLocation(position.coords.latitude, position.coords.longitude);


                                document.getElementsByName("latitude")[0].value=position.coords.latitude;
                                document.getElementsByName("longitude")[0].value=position.coords.longitude;


                                latitude=position.coords.latitude;
                                longitude=position.coords.longitude;


                                //requestPoints();
                            },
                            function () {
                                // Gelocation fallback: Defaults to Stockholm, Sweden
                                markerText = "<p>Please accept geolocation for me to be able to find you. <br>I've put you in Stockholm for now.</p>";
                                markOutLocation(59.3325215, 18.0643818);
                            }
                        );
                    }
                    else {
                        // No geolocation fallback: Defaults to Eeaster Island, Chile
                        markerText = "<p>No location support. Try Easter Island for now. :-)</p>";
                        markOutLocation(-27.121192, -109.366424);
                    }



                   /* var mapOptions = {
                        zoom: 8,
                        center: new google.maps.LatLng(-34.397, 150.644)
                    };

                    var map = new google.maps.Map(document.getElementById('map'),
                        mapOptions);
*/
                }

                function loadScript() {
                    var script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp' +
                    '&signed_in=true&callback=initialize';
                    document.body.appendChild(script);
                }

                window.onload = loadScript;


                /*
                (function () {
                    google.load("maps", "2");
                    google.setOnLoadCallback(function (){
                        // Create map
                        map = new google.maps.Map2(document.getElementById("map")),
                            markerText = "<h2>You are here</h2><p>Nice with geolocation, ain't it?</p>",
                            markOutLocation = function (lat, long) {
                                console.log((typeof lat)+","+(typeof long));

                                var latLong = new google.maps.LatLng(lat, long),
                                    marker = new google.maps.Marker(latLong);

                                console.log(latLong);


                                map.setCenter(latLong, 13);
                                map.addOverlay(marker);
                                marker.openInfoWindow(markerText);
                                google.maps.Event.addListener(marker, "click", function () {
                                    marker.openInfoWindow(markerText);
                                });
                            };
                        map.setUIToDefault();


                        // Check for geolocation support
                        if (navigator.geolocation) {
                            // Get current position
                            navigator.geolocation.getCurrentPosition(function (position) {
                                    // Success!
                                    markOutLocation(position.coords.latitude, position.coords.longitude);
                                    document.getElementsByName("latitude")[0].value=position.coords.latitude;
                                    document.getElementsByName("longitude")[0].value=position.coords.longitude;


                                    latitude=position.coords.latitude;
                                    longitude=position.coords.longitude;
                                    requestPoints();
                                },
                                function () {
                                    // Gelocation fallback: Defaults to Stockholm, Sweden
                                    markerText = "<p>Please accept geolocation for me to be able to find you. <br>I've put you in Stockholm for now.</p>";
                                    markOutLocation(59.3325215, 18.0643818);
                                }
                            );
                        }
                        else {
                            // No geolocation fallback: Defaults to Eeaster Island, Chile
                            markerText = "<p>No location support. Try Easter Island for now. :-)</p>";
                            markOutLocation(-27.121192, -109.366424);
                        }
                    });
                })();

*/










            </script>






    <!-- this is the place where location is sent to php server-->
    <center><form action="parser.php" method="post" id="formid">
        <label> Search: <input type="text" name="keyword" /></label>
        <label> Latitude: <input type="text" name="latitude" /></label>
        <label> Longitude: <input type="text" name="longitude" /></label>

        <input type="submit" value="Submit">
        <label><input type="button" value="Request Points" onclick="requestPoints();"></label>
    </form></center>




    <!--   end automatic refresh-->







</body>
</html>
