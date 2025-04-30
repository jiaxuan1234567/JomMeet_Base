// gatheringMap.new.js

let map;
let markers = [];
let savedLocations = null;
const markerMap = {};

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
        clickableIcons: false,
        styles: [
            {
                featureType: "poi",
                stylers: [{ visibility: "off" }]
            }
        ]
    });

    // one InfoWindow for the whole page
    infoWindow = new google.maps.InfoWindow();

    // then fetch and wire up your savedLocations…
    savedLocations = await $.getJSON('/api/savedLocations');

    const bounds = new google.maps.LatLngBounds();

    savedLocations.forEach(loc => {
        const pos = {
            lat: parseFloat(loc.latitude),
            lng: parseFloat(loc.longitude)
        };

        const marker = new google.maps.Marker({
            position: pos,
            map,
            icon: {
                url: '/asset/geo-alt.svg',
                scaledSize: new google.maps.Size(32, 32)
            }
        });

        marker.placeData = loc;

        marker.addListener('click', () => showDetailPanel(loc, pos, null));

        markers.push(marker);
        markerMap[loc.locationID] = marker; // map it
        bounds.extend(pos);
    });

    map.fitBounds(bounds);

    // wire up your search UI
    $('#searchBtn').on('click', performSearch);
    $('#searchBox').on('keydown', e => {
        //e.preventDefault();
        performSearch();

    });

    // seed hidden lat/lng inputs
    updateCoordinates(map.getCenter().toJSON());
}

/* ------------------------ use to add location ---------------------------------------- */
// async function performSearch() {
//     const query = $('#searchBox').val().trim();
//     if (!query) return;

//     clearMarkers();
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


/* ------------------------ performSearch start ---------------------------------------- */

async function performSearch() {
    const raw = $('#searchBox').val();
    const query = $('#searchBox').val().trim().toLowerCase();

    // 1) Always clear UI if the box is empty
    if (!query) {
        $('#resultsList').empty();
        $('#detailPanel').hide();
        return;
    }

    // 2) Otherwise, do your normal search/filtering…
    $('#resultsList').empty();
    $('#detailPanel').hide();

    // search location logic
    const results = savedLocations.filter(loc =>
        loc.locationName.toLowerCase().includes(query) ||
        (loc.address && loc.address.toLowerCase().includes(query))
    );

    if (results.length === 0) {
        $('<li>')
            .addClass('list-group-item text-start text-black fw-semibold border-0 bg-transparent')
            .text('Location Not Available')
            .appendTo('#resultsList');
        return; // stop further processing
    }

    results.forEach(loc => {
        const marker = markerMap[loc.locationID];
        if (marker) {
            marker.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(() => marker.setAnimation(null), 700);
        }

        // parseFloat ensures lat & lng are real numbers
        const pos = {
            lat: parseFloat(loc.latitude),
            lng: parseFloat(loc.longitude)
        };

        // list item
        $('<li>')
            .addClass('list-group-item list-group-item-action bg-transparent rounded-0')
            .css({
                'border-top': 'none',
                'border-left': 'none',
                'border-right': 'none',
                'border-bottom': '1px solid #dee2e6' // Bootstrap’s default border color
            })
            .html(`<strong>${loc.locationName}</strong><br>${loc.address || ''}`)
            .appendTo('#resultsList')
            .on('click', function () {
                showDetailPanel(loc, pos, this);
            });
    });

    if (results[0]) {
        // convert to numbers here too
        const firstPos = {
            lat: parseFloat(results[0].latitude),
            lng: parseFloat(results[0].longitude)
        };
        map.panTo(firstPos);
        map.setZoom(15);
    }
}

// location details
function showDetailPanel(loc, pos, liElem) {
    const html = `
    <div class="card shadow border-0 rounded-4" style="width: 260px;">
      <img src="${loc.image || 'https://cdn-icons-png.flaticon.com/512/1161/1161388.png'}"
           class="card-img-top rounded-top"
           style="object-fit: cover; height: 120px;">
  
      <div class="card-body px-3 py-2">
        <h6 class="fw-bold mb-1">${loc.locationName || 'Unnamed Location'}</h6>
        <p class="mb-2 small text-muted">${loc.address || ''}</p>
  
        ${loc.closeTime ? `<p class="mb-2 small text-muted">Close: ${loc.closeTime}</p>` : ''}
  
        ${typeof loc.commentCount !== 'undefined' ? `
          <p class="mb-1 small text-muted">Comment(${loc.commentCount})</p>
        ` : ''}
        <p class="mb-1 small text-muted">Comment (20)</p>
        <a href="#" class="mb-1 small text-muted"><p>More Comments</p></a>
        <button id="selectBtn"
                class="btn btn-primary btn-sm w-100 rounded border-0 button-blue-color"
                style="font-weight: 500;">
          Select
        </button>
      </div>
    </div>
  `;
    const $overlay = $('#detailOverlay').show();
    const $panel = $('#detailPanel').html(html).show();

    if (liElem) {
        // panel is absolutely positioned within its parent (.col-md-8.position-relative)
        const parentTop = $panel.parent().offset().top;
        const liTop = $(liElem).offset().top;
        $panel.css('top', (liTop - parentTop) + 'px');
    } else {
        // fallback if marker clicked
        $panel.css('top', '10px');
    }

    // hook up Select
    $panel.find('#selectBtn').on('click', () => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/my-gathering/create/location';

        // Location values
        const locationFields = {
            locationID: loc.locationID,
            locationName: loc.locationName
        };

        // Previous form values from query string
        const urlParams = new URLSearchParams(window.location.search);
        const keysToKeep = ['inputTheme', 'inputPax', 'inputDate', 'startTime', 'endTime'];

        keysToKeep.forEach(key => {
            if (urlParams.has(key)) {
                locationFields[key] = urlParams.get(key);
            }
        });

        // Append all as hidden fields
        for (const key in locationFields) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = locationFields[key];
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
    });

    // center and bounce
    map.panTo(pos);
    map.setZoom(17);
    markers.forEach(m => {
        if (m.placeData.placeId === loc.placeID) {
            m.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(() => m.setAnimation(null), 700);
        }
    });
}

$(document).on('click', '#detailOverlay', function (e) {
    // If the user clicks directly on the overlay (not the panel), hide everything
    if (e.target.id === 'detailOverlay') {
        $('#detailOverlay').hide();
        $('#detailPanel').hide();
    }
});

function selectSavedLocation(loc, pos) {
    map.panTo(pos);
    map.setZoom(17);
    $('#latitude').val(pos.lat);
    $('#longitude').val(pos.lng);
    // if you want to re-save, you already have it in DB—no need to POST again
}
/* ------------------------ performSearch end ---------------------------------------- */

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

// 1) A small debounce helper:
function debounce(fn, wait) {
    let t;
    return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), wait);
    };
}

// 2) Replace your keydown handler with an input handler:
$(document).ready(function () {
    const $searchBox = $('#searchBox');
    const $clearText = $('#clearText');
    const $vertBar = $('#vertBar');

    // show/hide the clear button as before
    $searchBox.on('input', function () {
        if ($.trim($(this).val()) !== '') {
            $clearText.removeClass('d-none');
            $vertBar.removeClass('d-none');
        } else {
            $clearText.addClass('d-none');
            $vertBar.addClass('d-none');
        }
    });

    // clear behavior as before
    $clearText.on('click', function () {
        $searchBox.val('');
        $clearText.addClass('d-none');
        $vertBar.addClass('d-none');
        $('#resultsList').empty();
        $('#detailPanel').hide();
        $searchBox.focus();
    });

    // 3) Debounced live search on each input
    const liveSearch = debounce(performSearch, 150);
    $('#searchBox')
        .off('keydown')
        .on('input', liveSearch);
});

// expose initMap for the API loader callback
window.initMap = initMap;
