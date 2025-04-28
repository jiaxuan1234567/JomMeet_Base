// gatheringMap.new.js

let map;
let markers = [];

// 1) Dynamically load Maps + Places libraries
async function loadLibraries() {
    const [mapsLib, placesLib] = await Promise.all([
        google.maps.importLibrary("maps"),    // core Map, Marker, LatLng, etc.
        google.maps.importLibrary("places"),  // Place, AutocompleteSessionToken
    ]);
    return { ...mapsLib, ...placesLib };
}

// 2) Initialize map & session token
async function initMap() {
    const { Map, AutocompleteSessionToken } = await loadLibraries();

    map = new Map(document.getElementById("map"), {
        center: { lat: 3.1390, lng: 101.6869 },
        zoom: 13,
        mapTypeControl: false,
        fullscreenControl: false,
        streetViewControl: false,
    });

    // for field-mask billing (if you use fetchFields or autocomplete)
    window.sessionToken = new AutocompleteSessionToken();

    // wire up your search UI
    $('#searchBtn').on('click', performSearch);
    $('#searchBox').on('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });
    $('#backToForm').on('click', () => history.back());

    // seed hidden lat/lng inputs
    updateCoordinates(map.getCenter().toJSON());
}

// 3) Text‐query search with the new API
async function performSearch() {
    const query = $('#searchBox').val().trim();
    if (!query) return;

    clearMarkers();
    $('#resultsList').empty();

    const { Place, Marker, LatLng, Size, Animation } = await loadLibraries();

    // build the request
    const request = {
        textQuery: query,
        fields: ['id', 'displayName', 'formattedAddress', 'location'],
        locationBias: map.getCenter().toJSON(),
        maxResultCount: 10
    };

    // call the new searchByText endpoint :contentReference[oaicite:0]{index=0}
    //@ts-ignore
    const { places } = await Place.searchByText(request);

    places.forEach(place => {
        console.log(place.id, place.displayName, place.formattedAddress, place.location);
        const pos = place.location;

        // 3a) list item
        const $li = $('<li>')
            .addClass('list-group-item list-group-item-action')
            .html(`<strong>${place.displayName}</strong><br>${place.formattedAddress || ''}`)
            .appendTo('#resultsList')
            .on('click', () => selectPlace(place));

        // 3b) marker
        const m = new google.maps.Marker({
            position: pos,
            map,
            icon: {
                url: '/asset/geo-alt.svg',
                scaledSize: google.maps.Size(32, 32)
            },
        });
        m.placeData = place;  // stash the full Place object
        m.addListener('click', () => {
            selectPlace(place);
            m.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(() => m.setAnimation(null), 700);
        });
        markers.push(m);
    });

    // zoom to first result
    if (places[0]) {
        map.panTo(places[0].location);
        map.setZoom(15);
    }
}

// 4) Handle selection: pan, zoom, update form, and save
async function selectPlace(place) {
    // pan & zoom
    map.panTo(place.location);
    map.setZoom(17);

    // update hidden coords
    updateCoordinates(place.location.toJSON());

    // if you need more fields than your initial search, you can do:
    // const detailer = new Place({ id: place.placeId });
    // await detailer.fetchFields({ fields:['displayName','formattedAddress','openingHours','rating'] });
    // then read detailer.displayName, etc. :contentReference[oaicite:1]{index=1}

    const payload = {
        place_id: place.id,
        name: place.displayName,
        address: place.formattedAddress,
        latitude: place.location.lat(),
        longitude: place.location.lng()
    };
    console.log('About to save:', payload);

    // POST back to your server
    $.ajax({
        url: '/gathering/location/save',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: e => console.log('Location saved!', e),
        error: e => console.error('Save failed', e)
    });
}

// remove old markers from the map
function clearMarkers() {
    markers.forEach(m => m.setMap(null));
    markers = [];
}

// update your hidden <input>s with the given coords
function updateCoordinates({ lat, lng }) {
    $('#latitude').val(lat);
    $('#longitude').val(lng);
}

// expose initMap for the API loader callback
window.initMap = initMap;
