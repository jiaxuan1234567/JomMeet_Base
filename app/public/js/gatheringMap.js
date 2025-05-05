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

function onMarkerSelect(marker, liElem = null) {
    showDetailPanel(marker.placeData, marker.getPosition(), liElem);
    if (currentActiveMarker && currentActiveMarker !== marker) {
        resetMarker(currentActiveMarker);
    }
    highlightMarker(marker);
}

async function showDetailPanel(loc, pos, liElem) {
    let imageUrl = '/asset/image-comingsoon.jpg';
    let feedbacks = [];

    try {
        feedbacks = await $.getJSON(`/api/location-feedback?locationId=${loc.locationID}`);
    } catch (err) {
        console.warn('Feedback fetch failed:', err);
    }

    try {
        const place = new google.maps.places.Place({ id: loc.placeID });
        await place.fetchFields({ fields: ['photos'] });
        const photo = place.photos?.[0];
        if (photo?.name) {
            const apiKey = 'AIzaSyCIm3LWq0gbsblgi0kmbEscuFq9zUoERD4';
            imageUrl = `https://places.googleapis.com/v1/${photo.name}/media?key=${apiKey}&maxWidthPx=400`;
            //imageUrl = `https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=PHOTO_REF&key=${apiKey}`;

        }
    } catch (err) {
        console.warn('Google photo fetch failed:', err);
    }

    // Location comment (FEEDBACK)
    // const feedbackBtnHTML = `<button id="viewFeedbackBtn" class="btn btn-outline-secondary btn-sm w-100 rounded border mt-2" style="font-weight: 500;">View Feedback</button>`;
    const feedbackBtnHTML = feedbacks.length ? `
    <p class="mb-2 small">
      <span id="viewFeedbackBtn" class="text-decoration-underline text-primary" style="cursor: pointer;">More Comments</span>
    </p>
  `: '';

    const html = `
    <div class="card shadow border-0 rounded-4" style="width: 260px;">
      <img src="${imageUrl || 'https://cdn-icons-png.flaticon.com/512/1161/1161388.png'}"
           class="card-img-top rounded-top" style="object-fit: cover; height: 120px;">
      <div class="card-body px-3 py-2">
        <h6 class="fw-bold mb-1">${loc.locationName || 'Unnamed Location'}</h6>
        <p class="mb-2 small text-muted">${loc.address || ''}</p>
        ${loc.closeTime ? `<p class="mb-2 small text-muted">Close: ${loc.closeTime}</p>` : ''}
        <p class="mb-1 small text-muted">Comment(${feedbacks.length})</p>
        ${feedbackBtnHTML}
        <button id="selectBtn" class="btn btn-primary btn-sm w-100 rounded border-0 button-blue-color" style="font-weight: 500;">Select</button>
      </div>
    </div>`;

    $('#detailOverlay').show();
    $('#detailPanel').html(html).show().css('top', liElem
        ? `${$(liElem).offset().top - $('#detailPanel').parent().offset().top}px`
        : '10px');

    $('#selectBtn').on('click', () => submitLocationForm(loc));
    $('#viewFeedbackBtn').on('click', () => {
        $('#feedbackPanel')
            .html('<div class="text-muted small">Loading feedback...</div>')
            .css({
                top: $('#detailPanel').position().top + 'px',
                left: ($('#detailPanel').outerWidth() + 20) + 'px' // 20px padding beside
            })
            .show();

        try {
            //const feedbacks = await $.getJSON(`/api/location-feedback?locationId=${loc.locationID}`);
            if (feedbacks.length) {
                let feedbackHTML = '<div class="small text-muted">';
                feedbacks.forEach(fb => {
                    feedbackHTML += `<div class="border-top pt-1 mb-2">
                            <div><strong>${fb.name}</strong></div>
                            <div>${fb.feedbackDesc}</div>
                        </div>`;
                });
                feedbackHTML += '</div>';
                $('#feedbackPanel').html(feedbackHTML);
            } else {
                $('#feedbackPanel').html('<div class="small text-muted">No feedback yet.</div>');
            }
        } catch (err) {
            $('#feedbackPanel').html('<div class="text-danger small">Failed to load feedback.</div>');
        }
    });

    map.panTo(pos);
    map.setZoom(17);
}

function submitLocationForm(loc) {
    // Restore current fieldStates or create fresh
    const fieldStates = JSON.parse(sessionStorage.getItem('__field_states__')) || {};

    fieldStates['inputLocation'].value = loc.locationName;

    // Optionally store raw ID for post-processing
    sessionStorage.setItem('locationId', loc.locationID);

    // Save full state
    sessionStorage.setItem('__field_states__', JSON.stringify(fieldStates));

    // Redirect
    //window.location.href = '/my-gathering/create';

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/my-gathering/create/location';
    form.style.display = 'none';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'locationId';
    input.value = loc.locationID;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

async function performSearch() {
    const query = $('#searchBox').val().trim().toLowerCase();
    $('#resultsList').empty();
    $('#detailPanel').hide();

    if (!query) return;

    try {
        const results = await $.getJSON(`/api/search-location?q=${encodeURIComponent(query)}`);

        if (!results.length) {
            $('<li>').addClass('list-group-item text-start text-black fw-semibold border-0 bg-transparent')
                .text('Location Not Available')
                .appendTo('#resultsList');
            return;
        }

        results.forEach(loc => {
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
                    onMarkerSelect(marker, this);
                });
        });
    } catch (err) {
        console.error("Search failed:", err);
    }
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
        // $('#detailOverlay').hide();
        // $('#detailPanel').hide();
        // $('#feedbackPanel').hide();
        // resetActiveMarker();
        const $feedbackPanel = $('#feedbackPanel');
        const $detailPanel = $('#detailPanel');

        if ($feedbackPanel.is(':visible')) {
            $feedbackPanel.hide();
        } else if ($detailPanel.is(':visible')) {
            $('#detailOverlay').hide();
            $detailPanel.hide();
            resetActiveMarker();
        }
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