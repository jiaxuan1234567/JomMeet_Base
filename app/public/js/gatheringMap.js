// gatheringMap.js (Switched to classic google.maps.Marker)

let map;
let markers = [];
let savedLocations = null;
let currentActiveMarker = null;
const markerMap = {};

// Load required libraries
async function loadLibraries() {
    const [mapsLib, placesLib] = await Promise.all([
        google.maps.importLibrary("maps"),
        google.maps.importLibrary("places")
    ]);
    return { ...mapsLib, ...placesLib };
}

// Highlight marker (scale up + bounce)
function highlightMarker(marker) {
    marker.setIcon({
        url: '/asset/geo-alt.svg',
        scaledSize: new google.maps.Size(48, 48)
    });
    marker.setAnimation(google.maps.Animation.BOUNCE);
    currentActiveMarker = marker;
}

// Reset marker to default
function resetMarker(marker) {
    marker.setIcon({
        url: '/asset/geo-alt.svg',
        scaledSize: new google.maps.Size(32, 32)
    });
    marker.setAnimation(null);
}

function resetActiveMarker() {
    if (currentActiveMarker) {
        resetMarker(currentActiveMarker);
        currentActiveMarker = null;
    }
}

function updateCoordinates({ lat, lng }) {
    $('#latitude').val(lat);
    $('#longitude').val(lng);
}

function onMarkerSelect(marker) {
    showDetailPanel(marker.placeData, marker.getPosition());
    if (currentActiveMarker && currentActiveMarker !== marker) {
        resetMarker(currentActiveMarker);
    }
    highlightMarker(marker);
}

async function showDetailPanel(loc, pos, liElem) {
    let imageUrl = '/asset/image-comingsoon.jpg';

    try {
        const place = new google.maps.places.Place({ id: loc.placeID });
        await place.fetchFields({ fields: ['photos'] });
        const photo = place.photos?.[0];
        if (photo?.name) {
            const apiKey = 'AIzaSyCIm3LWq0gbsblgi0kmbEscuFq9zUoERD4';
            imageUrl = `https://places.googleapis.com/v1/${photo.name}/media?key=${apiKey}&maxWidthPx=400`;
        }
    } catch (err) {
        console.warn('Google photo fetch failed:', err);
    }

    const html = `
    <div class="card shadow border-0 rounded-4" style="width: 260px;">
      <img src="${imageUrl || 'https://cdn-icons-png.flaticon.com/512/1161/1161388.png'}"
           class="card-img-top rounded-top" style="object-fit: cover; height: 120px;">
      <div class="card-body px-3 py-2">
        <h6 class="fw-bold mb-1">${loc.locationName || 'Unnamed Location'}</h6>
        <p class="mb-2 small text-muted">${loc.address || ''}</p>
        ${loc.closeTime ? `<p class="mb-2 small text-muted">Close: ${loc.closeTime}</p>` : ''}
        ${typeof loc.commentCount !== 'undefined' ? `<p class="mb-1 small text-muted">Comment(${loc.commentCount})</p>` : ''}
        <button id="selectBtn" class="btn btn-primary btn-sm w-100 rounded border-0 button-blue-color" style="font-weight: 500;">Select</button>
      </div>
    </div>`;

    $('#detailOverlay').show();
    $('#detailPanel').html(html).show().css('top', liElem
        ? `${$(liElem).offset().top - $('#detailPanel').parent().offset().top}px`
        : '10px');

    $('#selectBtn').on('click', () => submitLocationForm(loc));
    map.panTo(pos);
    map.setZoom(17);
}

function submitLocationForm(loc) {
    sessionStorage.setItem('locationId', loc.locationID);
    sessionStorage.setItem('inputLocation', loc.locationName);
    window.location.href = '/my-gathering/create';
}

async function performSearch() {
    const query = $('#searchBox').val().trim().toLowerCase();
    $('#resultsList').empty();
    $('#detailPanel').hide();

    if (!query) return;

    const results = savedLocations.filter(loc =>
        loc.locationName.toLowerCase().includes(query) ||
        (loc.address && loc.address.toLowerCase().includes(query))
    );

    if (results.length === 0) {
        $('<li>').addClass('list-group-item text-start text-black fw-semibold border-0 bg-transparent')
            .text('Location Not Available')
            .appendTo('#resultsList');
        return;
    }

    results.forEach(loc => {
        const pos = { lat: +loc.latitude, lng: +loc.longitude };
        const marker = markerMap[loc.locationID];
        if (marker) {
            marker.setIcon({
                url: '/asset/geo-alt.svg',
                scaledSize: new google.maps.Size(36, 36)
            });
        }

        $('<li>')
            .addClass('list-group-item list-group-item-action bg-transparent rounded-0')
            .css({ borderBottom: '1px solid #dee2e6' })
            .html(`<strong>${loc.locationName}</strong><br>${loc.address || ''}`)
            .appendTo('#resultsList')
            .on('click', function () {
                onMarkerSelect(marker);
            });
    });
}

window.initMap = async function () {
    const { Map } = await loadLibraries();
    map = new Map(document.getElementById("map"), {
        center: { lat: 3.1390, lng: 101.6869 },
        zoom: 13,
        mapTypeControl: false,
        fullscreenControl: false,
        streetViewControl: false,
        clickableIcons: false,
        styles: [{ featureType: "poi", stylers: [{ visibility: "off" }] }]
    });

    try {
        savedLocations = await $.getJSON('/api/savedLocations');
        const bounds = new google.maps.LatLngBounds();

        savedLocations.forEach(loc => {
            const pos = { lat: +loc.latitude, lng: +loc.longitude };
            const marker = new google.maps.Marker({
                map,
                position: pos,
                icon: {
                    url: '/asset/geo-alt.svg',
                    scaledSize: new google.maps.Size(32, 32)
                },
                animation: google.maps.Animation.DROP
            });
            marker.placeData = loc;
            marker.addListener('click', () => onMarkerSelect(marker));
            markers.push(marker);
            markerMap[loc.locationID] = marker;
            bounds.extend(pos);
        });

        map.fitBounds(bounds);
        updateCoordinates(map.getCenter().toJSON());
    } catch (err) {
        console.error("Failed to load saved locations:", err);
        $('#mapLoadingOverlay').html('<span class="text-danger fw-bold">Failed to load map data.</span>');
        return;
    }

    $('#mapLoadingOverlay').fadeOut();
    $('#searchBtn').on('click', performSearch);
};

$(document).on('click', '#detailOverlay', function (e) {
    if (e.target.id === 'detailOverlay') {
        $('#detailOverlay').hide();
        $('#detailPanel').hide();
        resetActiveMarker();
    }
});

$(document).ready(function () {
    const debounce = (fn, delay) => {
        let t;
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn(...args), delay);
        };
    };

    const liveSearch = debounce(performSearch, 150);

    $('#searchBox').on('input', liveSearch);

    $('#clearText').on('click', function () {
        $('#searchBox').val('').trigger('input');
        $('#clearText').addClass('d-none');
        $('#vertBar').addClass('d-none');
        $('#resultsList').empty();
        $('#detailPanel').hide();
        resetActiveMarker();
        $('#searchBox').focus();
    });

    $('#searchBox').on('input', function () {
        const hasText = $.trim($(this).val()) !== '';
        $('#clearText').toggleClass('d-none', !hasText);
        $('#vertBar').toggleClass('d-none', !hasText);
    });
});


/* ------------------------ use to add location ---------------------------------------- */
// async function performSearch() {
//     const query = $('#searchBox').val().trim();
//     if (!query) return;

//     $('#resultsList').empty();

//     const { Place, Marker, LatLng, Size, Animation } = await loadLibraries();

//     // build the request
//     const request = {
//         textQuery: query,
//         fields: ['id', 'displayName', 'formattedAddress', 'location'],
//         locationBias: map.getCenter().toJSON(),
//         maxResultCount: 10
//     };

//     // call the new searchByText endpoint :contentReference[oaicite:0]{index=0}
//     //@ts-ignore
//     const { places } = await Place.searchByText(request);

//     places.forEach(place => {
//         console.log(place.id, place.displayName, place.formattedAddress, place.location);
//         const pos = place.location;

//         // 3a) list item
//         const $li = $('<li>')
//             .addClass('list-group-item list-group-item-action')
//             .html(`<strong>${place.displayName}</strong><br>${place.formattedAddress || ''}`)
//             .appendTo('#resultsList')
//             .on('click', () => selectPlace(place));

//         // 3b) marker
//         const m = new google.maps.Marker({
//             position: pos,
//             map,
//             icon: {
//                 url: '/asset/geo-alt.svg',
//                 scaledSize: google.maps.Size(32, 32)
//             },
//         });
//         m.placeData = place;  // stash the full Place object
//         m.addListener('click', () => {
//             selectPlace(place);
//             m.setAnimation(google.maps.Animation.BOUNCE);
//             setTimeout(() => m.setAnimation(null), 700);
//         });
//         markers.push(m);
//     });

//     // zoom to first result
//     if (places[0]) {
//         map.panTo(places[0].location);
//         map.setZoom(15);
//     }
// }
// async function selectPlace(place) {
//     // pan & zoom
//     map.panTo(place.location);
//     map.setZoom(17);

//     // update hidden coords
//     updateCoordinates(place.location.toJSON());

//     // if you need more fields than your initial search, you can do:
//     // const detailer = new Place({ id: place.placeId });
//     // await detailer.fetchFields({ fields:['displayName','formattedAddress','openingHours','rating'] });
//     // then read detailer.displayName, etc. :contentReference[oaicite:1]{index=1}

//     const payload = {
//         place_id: place.id,
//         name: place.displayName,
//         address: place.formattedAddress,
//         latitude: place.location.lat(),
//         longitude: place.location.lng()
//     };
//     console.log('About to save:', payload);

//     // POST back to your server
//     $.ajax({
//         url: '/gathering/location/save',
//         method: 'POST',
//         contentType: 'application/json',
//         data: JSON.stringify(payload),
//         success: e => console.log('Location saved!', e),
//         error: e => console.error('Save failed', e)
//     });
// }