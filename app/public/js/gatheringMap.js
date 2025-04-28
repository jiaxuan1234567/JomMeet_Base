// gatheringMap.js

let map;
let placesService;
let markers = [];
let sessionToken;

// 1) Dynamically load Maps + Places
async function loadMaps() {
    // loads core Maps + Places libraries on demand
    const [{ Map, Marker, LatLng, Size, Animation }, {
        PlacesService,
        PlacesServiceStatus,
        Place,
        AutocompleteSessionToken
    }] = await Promise.all([
        google.maps.importLibrary("maps"),
        google.maps.importLibrary("places"),
    ]);
    return {
        Map, Marker, LatLng, Size, Animation,
        PlacesService, PlacesServiceStatus, Place, AutocompleteSessionToken
    };
}

// 2) Initialize map + services
async function initMap() {
    const {
        Map,
        Marker,
        LatLng,
        Size,
        Animation,
        PlacesService,
        AutocompleteSessionToken
    } = await loadMaps();

    const defaultLocation = { lat: 3.1390, lng: 101.6869 };
    map = new Map(document.getElementById("map"), {
        center: defaultLocation,
        zoom: 13,
        mapTypeControl: false,
        fullscreenControl: false,
        streetViewControl: false,
    });

    // new session token for billing-efficient autocomplete/details
    sessionToken = new AutocompleteSessionToken();

    // legacy textSearch for searching by free-form query
    placesService = new PlacesService(map);

    // wire up UI
    $('#searchBtn').on('click', performSearch);
    $('#searchBox').on('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });
    $('#backToForm').on('click', () => history.back());

    updateCoordinates(defaultLocation);
}

// 3) Perform a text search (callback → Promise)
async function performSearch() {
    const query = $('#searchBox').val().trim();
    if (!query) return;

    clearMarkers();
    $('#resultsList').empty();

    // wrap the old callback API in a Promise
    const [places, status] = await new Promise(resolve => {
        placesService.textSearch(
            { query, bounds: map.getBounds() },
            (results, st, pagination) => resolve([results, st])
        );
    });

    if (status !== google.maps.places.PlacesServiceStatus.OK || !places) return;

    const { Marker, Size, Animation, LatLng } = await loadMaps();

    places.forEach(place => {
        const position = {
            lat: place.geometry.location.lat(),
            lng: place.geometry.location.lng(),
        };

        // list entry
        const $li = $('<li>')
            .addClass('list-group-item list-group-item-action')
            .html(`<strong>${place.name}</strong><br>${place.formatted_address || ''}`)
            .data('position', position)
            .data('placeId', place.place_id)
            .appendTo('#resultsList')
            .on('click', () => selectLatLng(position));

        // marker
        const m = new Marker({
            position,
            map,
            icon: {
                url: '/asset/geo-alt.svg',
                scaledSize: new Size(32, 32),
            },
        });
        m.placeId = place.place_id;
        m.addListener('click', () => {
            selectLatLng(position);
            m.setAnimation(Animation.BOUNCE);
            setTimeout(() => m.setAnimation(null), 700);
        });
        markers.push(m);
    });

    // zoom to first result
    if (places[0]) {
        map.panTo(places[0].geometry.location);
        map.setZoom(15);
    }
}

// 4) When the user clicks a marker or item, pan & fetch full details
async function selectLatLng(position) {
    map.panTo(position);
    map.setZoom(17);
    updateCoordinates(position);

    // now fetch the full Place details
    const placeId = markers.find(m =>
        m.getPosition().lat() === position.lat &&
        m.getPosition().lng() === position.lng
    )?.placeId;
    if (!placeId) return console.error('No placeId found');

    const { Place } = await loadMaps();

    // construct a new Place object just for details
    const place = new Place({ id: placeId });
    // fetch only the fields you need
    await place.fetchFields({
        fields: ['displayName', 'formattedAddress', 'location']
    });

    // place.displayName, place.formattedAddress, place.location now available
    // → you can now POST these back to your server to save 
    $.ajax({
        url: '/gathering/location/save',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            gathering_id: window.gatheringId,
            place_id: placeId,
            name: place.displayName,
            address: place.formattedAddress,
            latitude: place.location.lat(),
            longitude: place.location.lng()
        }),
        success: () => console.log('Saved!'),
        error: e => console.error('Save failed', e)
    });
}

// utility: clear old markers
function clearMarkers() {
    markers.forEach(m => m.setMap(null));
    markers = [];
}

// utility: update hidden form fields
function updateCoordinates(latlng) {
    const lat = typeof latlng.lat === 'function' ? latlng.lat() : latlng.lat;
    const lng = typeof latlng.lng === 'function' ? latlng.lng() : latlng.lng;
    $('#latitude').val(lat);
    $('#longitude').val(lng);
}

// expose initMap globally for the Maps callback
window.initMap = initMap;

// let map, marker;
// let placesService;
// let markers = []; // to keep track and clear them

// function initMap() {
//     const defaultLocation = { lat: 3.1390, lng: 101.6869 };
//     map = new google.maps.Map(document.getElementById("map"), {
//         center: defaultLocation,
//         zoom: 13,
//         mapTypeControl: false,
//         fullscreenControl: false,
//         streetViewControl: false,
//     });

//     updateCoordinates(defaultLocation);

//     placesService = new google.maps.places.PlacesService(map);

//     $('#searchBtn').on('click', function () {
//         performSearch();
//     });

//     $('#searchBox').on('keydown', function (e) {
//         if (e.key === 'Enter') {
//             e.preventDefault();
//             performSearch();
//         }
//     });

//     $('#backToForm').on('click', function () {
//         history.back();
//     });
// }

// function performSearch() {
//     const query = $('#searchBox').val().trim();
//     if (!query) return;

//     clearMarkers();
//     $('#resultsList').empty();

//     if (marker) {
//         marker.setMap(null);
//     }

//     placesService.textSearch({
//         query,
//         bounds: map.getBounds(),
//     }, (places, status) => {
//         if (status !== google.maps.places.PlacesServiceStatus.OK || !places) return;

//         places.forEach(place => {
//             const position = {
//                 lat: place.geometry.location.lat(),
//                 lng: place.geometry.location.lng()
//             };

//             // Create search list item
//             const $li = $('<li>', {
//                 class: 'list-group-item list-group-item-action',
//                 html: `
//                     <strong>${place.name}</strong><br>
//                     ${place.formatted_address || ''}<br>
//                     <small>Rating: ${place.rating || '–'}</small>
//                 `
//             })
//                 .data('position', position)
//                 .appendTo('#resultsList');

//             $li.data('placeId', place.place_id)
//                 .on('click', () => selectLatLng(position, place.place_id));

//             // $li.on('click', function () {
//             //     const pos = $(this).data('position');
//             //     selectLatLng(pos);
//             // });

//             // Create marker
//             const m = new google.maps.Marker({
//                 position: position,
//                 map,
//                 icon: {
//                     url: '/asset/geo-alt.svg',
//                     scaledSize: new google.maps.Size(32, 32)
//                 }
//             });

//             m.positionData = position;

//             m.addListener('click', function () {
//                 selectLatLng(this.positionData);
//                 // Bounce animation
//                 m.setAnimation(google.maps.Animation.BOUNCE);
//                 setTimeout(() => m.setAnimation(null), 700);
//             });

//             markers.push(m);
//         });

//         // Center map to first result
//         if (places[0]) {
//             const firstLoc = places[0].geometry.location;
//             map.panTo(firstLoc);
//             map.setZoom(15);
//         }
//     });
// }

// // function selectLatLng(position) {
// //     if (!position || typeof position.lat !== 'number' || typeof position.lng !== 'number') {
// //         console.error('Invalid latlng selected');
// //         return;
// //     }

// //     const loc = new google.maps.LatLng(position.lat, position.lng);
// //     map.panTo(loc);
// //     map.setZoom(17);
// //     updateCoordinates(loc);
// // }

// function selectLatLng(position, placeId) {
//     // pan/zoom as before…
//     map.panTo(position);
//     map.setZoom(17);

//     // fetch full details
//     const service = new google.maps.places.PlacesService(map);
//     service.getDetails({ placeId }, (place, status) => {
//         if (status !== google.maps.places.PlacesServiceStatus.OK) {
//             return console.error('Details fetch failed:', status);
//         }

//         // build your payload
//         const payload = {
//             place_id: place.place_id,
//             name: place.name,
//             address: place.formatted_address,
//             latitude: place.geometry.location.lat(),
//             longitude: place.geometry.location.lng()
//         };

//         // send to your backend
//         $.ajax({
//             url: '/gathering/location/save',
//             method: 'POST',
//             contentType: 'application/json',
//             data: JSON.stringify(payload),
//             success: () => alert('Location saved!'),
//             error: xhr => alert('Save failed: ' + xhr.responseText)
//         });
//     });
// }


// function clearMarkers() {
//     markers.forEach(m => m.setMap(null));
//     markers = [];
// }

// function updateCoordinates(latlng) {
//     const get = x => (typeof x === 'function' ? x() : x);
//     const lat = get(latlng.lat);
//     const lng = get(latlng.lng);

//     $('#latitude').val(lat);
//     $('#longitude').val(lng);
// }

// // jQuery ready for search input behavior
// $(document).ready(function () {
//     const $searchBox = $('#searchBox');
//     const $clearText = $('#clearText');
//     const $vertBar = $('#vertBar');
//     const $searchBtnIcon = $('#searchBtn');

//     $searchBox.on('input', function () {
//         if ($.trim($(this).val()) !== '') {
//             $clearText.removeClass('d-none');
//             $vertBar.removeClass('d-none');
//         } else {
//             $clearText.addClass('d-none');
//             $vertBar.addClass('d-none');
//         }
//     });

//     $clearText.on('click', function () {
//         $searchBox.val('');
//         $clearText.addClass('d-none');
//         $vertBar.addClass('d-none');
//         $('#resultsList').empty();
//         clearMarkers();
//         $searchBox.focus();
//     });
// });

// // Expose initMap globally
// window.initMap = initMap;
