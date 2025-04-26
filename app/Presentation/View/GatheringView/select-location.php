<div class="row vh-100">
    <div class="col-2">
        <a href="javascript:void(0);" onclick="window.history.length > 1 ? history.back() : window.location.href='/'" class="btn btn-outline-secondary border-0">←</a>
    </div>
    <div class="col-md-4 bg-light p-3 d-flex flex-column">
        <input type="text" id="searchBox" class="form-control mb-3" placeholder="Search location...">
        <ul id="resultsList" class="list-group overflow-auto" style="flex: 1 1 auto;"></ul>
    </div>
    <div class="col-md-8 p-0">
        <div id="map" style="width: 100%; height: 100%;"></div>
    </div>
</div>

<!-- Move scripts at bottom -->
<script>
    // gatheringMap.js

    let map, marker;

    function initMap() {
        const defaultLocation = {
            lat: 3.1390,
            lng: 101.6869
        }; // Kuala Lumpur

        map = new google.maps.Map(document.getElementById("map"), {
            center: defaultLocation,
            zoom: 13,
        });

        marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true,
        });

        updateCoordinates(marker.getPosition());

        // After map is initialized, get user location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    map.setCenter(userLocation);
                    marker.setPosition(userLocation);
                    updateCoordinates(userLocation);
                },
                function(error) {
                    console.warn("Geolocation permission denied or unavailable.");
                }
            );
        }

        // When map is clicked
        map.addListener("click", function(event) {
            marker.setPosition(event.latLng);
            updateCoordinates(event.latLng);
        });

        // When marker is dragged
        marker.addListener("dragend", function(event) {
            updateCoordinates(event.latLng);
        });
    }

    function updateCoordinates(latlng) {
        if (document.getElementById("latitude") && document.getElementById("longitude")) {
            document.getElementById("latitude").value = latlng.lat();
            document.getElementById("longitude").value = latlng.lng();
        }
    }
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIm3LWq0gbsblgi0kmbEscuFq9zUoERD4&libraries=places&callback=initMap">
</script>