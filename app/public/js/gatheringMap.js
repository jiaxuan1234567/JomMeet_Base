let map, marker;
let placesService;
let markers = []; // to keep track and clear them

function initMap() {
    const defaultLocation = { lat: 3.1390, lng: 101.6869 };
    map = new google.maps.Map(document.getElementById("map"), {
        center: defaultLocation,
        zoom: 13,
        mapTypeControl: false,
        fullscreenControl: false,
        streetViewControl: false,
    });

    updateCoordinates(defaultLocation);

    placesService = new google.maps.places.PlacesService(map);

    $('#searchBtn').on('click', function () {
        performSearch();
    });

    $('#searchBox').on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });

    $('#backToForm').on('click', function () {
        history.back();
    });
}

function performSearch() {
    const query = $('#searchBox').val().trim();
    if (!query) return;

    clearMarkers();
    $('#resultsList').empty();

    if (marker) {
        marker.setMap(null);
    }

    placesService.textSearch({
        query,
        bounds: map.getBounds(),
    }, (places, status) => {
        if (status !== google.maps.places.PlacesServiceStatus.OK || !places) return;

        places.forEach(place => {
            const position = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng()
            };

            // Create search list item
            const $li = $('<li>', {
                class: 'list-group-item list-group-item-action',
                html: `
                    <strong>${place.name}</strong><br>
                    ${place.formatted_address || ''}<br>
                    <small>Rating: ${place.rating || '–'}</small>
                `
            })
                .data('position', position)
                .appendTo('#resultsList');

            $li.on('click', function () {
                const pos = $(this).data('position');
                selectLatLng(pos);
            });

            // Create marker
            const m = new google.maps.Marker({
                position: position,
                map,
                icon: {
                    url: '/asset/geo-alt.svg',
                    scaledSize: new google.maps.Size(32, 32)
                }
            });

            m.positionData = position;

            m.addListener('click', function () {
                selectLatLng(this.positionData);
                // Bounce animation
                m.setAnimation(google.maps.Animation.BOUNCE);
                setTimeout(() => m.setAnimation(null), 700);
            });

            markers.push(m);
        });

        // Center map to first result
        if (places[0]) {
            const firstLoc = places[0].geometry.location;
            map.panTo(firstLoc);
            map.setZoom(15);
        }
    });
}

function selectLatLng(position) {
    if (!position || typeof position.lat !== 'number' || typeof position.lng !== 'number') {
        console.error('Invalid latlng selected');
        return;
    }

    const loc = new google.maps.LatLng(position.lat, position.lng);
    map.panTo(loc);
    map.setZoom(17);
    updateCoordinates(loc);
}

function clearMarkers() {
    markers.forEach(m => m.setMap(null));
    markers = [];
}

function updateCoordinates(latlng) {
    const get = x => (typeof x === 'function' ? x() : x);
    const lat = get(latlng.lat);
    const lng = get(latlng.lng);

    $('#latitude').val(lat);
    $('#longitude').val(lng);
}

// jQuery ready for search input behavior
$(document).ready(function () {
    const $searchBox = $('#searchBox');
    const $clearText = $('#clearText');
    const $vertBar = $('#vertBar');
    const $searchBtnIcon = $('#searchBtn');

    $searchBox.on('input', function () {
        if ($.trim($(this).val()) !== '') {
            $clearText.removeClass('d-none');
            $vertBar.removeClass('d-none');
        } else {
            $clearText.addClass('d-none');
            $vertBar.addClass('d-none');
        }
    });

    $clearText.on('click', function () {
        $searchBox.val('');
        $clearText.addClass('d-none');
        $vertBar.addClass('d-none');
        $('#resultsList').empty();
        clearMarkers();
        $searchBox.focus();
    });
});

// Expose initMap globally
window.initMap = initMap;
