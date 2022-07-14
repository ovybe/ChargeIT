let stations=[];
let last_window_opened;
let map;
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 5,
        center: { lat: 46, lng: 24 },
        clickableIcons: false,
    });
    $.ajax({
        url: "/fetcher/snp/",
        success: function(data){
            jsonData=JSON.parse(data);
            stations=jsonData;
            setMarkers();
            // console.log(stations);
        },
        error: function(data){
        }
    });
}

function setMarkers() {
    // Adds markers to the map.
    // Marker sizes are expressed as a Size of X,Y where the origin of the image
    // (0,0) is located in the top left of the image.
    // Origins, anchor positions and coordinates of the marker increase in the X
    // direction to the right and in the Y direction down.

    const image = {
        url: "https://i.imgur.com/R0zzPTz.png",
        // This marker is 20 pixels wide by 32 pixels high.
        size: new google.maps.Size(32, 32),
        // The origin for this image is (0, 0).
        origin: new google.maps.Point(0, 0),
        // The anchor for this image is the base of the flagpole at (0, 32).
        anchor: new google.maps.Point(0, 32),
        //
        // scaledSize: new google.maps.Size(32, 32),
    };
    // Shapes define the clickable region of the icon. The type defines an HTML
    // <area> element 'poly' which traces out a polygon as a series of X,Y points.
    // The final coordinate closes the poly by connecting to the first coordinate.
    const shape = {
        coords: [1, 1, 1, 20, 18, 20, 18, 1],
        type: "poly",
        // coords: [-5,32],
        // type: "circle",
    };
    let station;
    let marker=[];
    let infowindow=[];
    // console.log(stations);
     for (let i = 0; i < stations.length; i++) {
            // alert(station);
            station=stations[i];
            console.log(station);
            marker[i]=new google.maps.Marker({
                // parsefloat doesn't do it as precisely as it should.
                position: new google.maps.LatLng(station['latitude'],station['longitude']),
                map,
                icon: image,
                shape: shape,
                title: station['name'],
                zIndex: station['id'],
                optimized: true,
            });
            let contentString='<div id="content"><div class="mb-2" id="contentstuff"><p class="ms-1 mb-1 me-auto fw-bold">'+station['name']+'</p><p class="ms-1 mb-1 fw-normal">'+station['location']+'</p>';

            for(let i=0;i<Object.keys(station['types']).length;i++) {
                contentString=contentString+'<p class="ms-1 mb-1 fw-light">'+station['types'][i]+'</p>';
            }
                contentString+='<a class="ms-1" href="https://www.google.com/maps/search/'+station['location'].replace('+','%2B')+'">View on Maps</a></div><div class="text-center"><button id="canvasbtn'+station['id']+'" onclick="populateCanvas('+station['id']+')" class="btn mb-1 ms-1 btn-outline-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">View</button><a class="btn mb-1 ms-1 btn-outline-dark" href="/admin/edit/station/'+station['uuid']+'">Edit</a><a class="btn mb-1 ms-1 btn-outline-dark" href="/admin/delete/station/'+station['uuid']+'">Delete</a></div></div>';
         infowindow[i] = new google.maps.InfoWindow({
             content: structuredClone(contentString),
         });

         // $('canvasbtn'+station['id']).on("click",() => {
            //     populateCanvas(stations[i]);
            // });

         marker[i].addListener("click", () => {
             map.setZoom(14);
             map.setCenter(marker[i].getPosition());
             infowindow[i].open({
                 anchor: marker[i],
                 map,
                 shouldFocus: true,
             });
             if(last_window_opened)
                 last_window_opened.close();
             last_window_opened=infowindow[i];
         });

     }
    // for (let i = 0; i < stations.length; i++) {
    //     const station = stations[i];
    //
    //
    //     new google.maps.Marker({
    //         position: { lat: station[1], lng: station[2] },
    //         map,
    //         icon: image,
    //         shape: shape,
    //         title: station[0],
    //         zIndex: station[3],
    //     });
    // }
}
function reCenter(){
    map.setZoom(5);
    map.setCenter(new google.maps.LatLng(46,24));
}
function populateCanvas(uuid){
    // if(!$('.offcanvas').hasClass('show'))
    //     $('#canvasbtn').trigger('click');
    var station;
    // console.log(stations);
    for(var i=0;i<stations.length;i++){
        if(stations[i]['id']===uuid){
            station=stations[i];
            break;
        }
    }
    $('#sName').text(station['name']);
    $('#sLoc').text(station['location']);
    $('#sBook').attr("href", "/booking/create/"+station['id']);
    $('#sEdit').attr("href","/admin/edit/station/"+station['uuid']);
    $('#sDelete').attr("href","/admin/delete/station/"+station['uuid']);
    $.ajax({
        url: "/fetcher/plugs/",
        data:{
            id:station['uuid']
        },
        success: function(data){
            $('#plugsTable tbody tr').remove();
            plugs=data;
            // console.log(plugs);
            for(var i=0;i<plugs.length;i++){
                if(plugs[i]['status'])
                $('#plugsTable').find('tbody').append('<tr><td scope="row"><i class="bi bi-check-circle-fill" style="color: green;"></i></td><td>'+plugs[i]['type']+'</td><td>'+plugs[i]['output']+'</td></tr>');
                else
                    $('#plugsTable').find('tbody').append('<tr><td scope="row"><i class="bi bi-x-circle-fill" style="color: red;"></i></td><td>'+plugs[i]['type']+'</td><td>'+plugs[i]['output']+'</td></tr>');
            }
            // console.log(plugs);
            // console.log(stations);
        },
        error: function(data){
        }
    });
}

// The following example creates complex markers to indicate beaches near
// Sydney, NSW, Australia. Note that the anchor is set to (0,32) to correspond
// to the base of the flagpole.


// Data for the markers consisting of a name, a LatLng and a zIndex for the
// order in which these markers should display on top of each other.



window.initMap = initMap;