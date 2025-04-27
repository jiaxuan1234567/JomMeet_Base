// gatheringMap.js

let map, marker;
let placesService;
let markers = []; // to keep track and clear them

function initMap() {
    const defaultLocation = { lat: 3.1390, lng: 101.6869 };
    map = new google.maps.Map(document.getElementById("map"), {
        center: defaultLocation,
        zoom: 13,
    });

    marker = new google.maps.Marker({
        position: defaultLocation,
        map,
        draggable: true,
    });
    updateCoordinates(defaultLocation);

    // Set up PlacesService
    placesService = new google.maps.places.PlacesService(map);

    // Handle your “Search” button click
    document.getElementById('searchBtn').addEventListener('click', performSearch);

    // Optionally: also search on Enter key
    document.getElementById('searchBox').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });

    // Back button
    document.getElementById('backToForm').addEventListener('click', () => {
        history.back();
    });
}

function performSearch() {
    const query = document.getElementById('searchBox').value.trim();
    if (!query) return;

    // Clear old markers + list
    clearMarkers();
    const resultsList = document.getElementById('resultsList');
    resultsList.innerHTML = '';

    // Ask PlacesService for textSearch within the current map bounds
    placesService.textSearch({
        query,
        bounds: map.getBounds(),
    }, (places, status) => {
        if (status !== google.maps.places.PlacesServiceStatus.OK || !places) return;

        places.forEach(place => {
            // 1) render list item
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.innerHTML = `
        <strong>${place.name}</strong><br>
        ${place.formatted_address || ''}<br>
        <small>Rating: ${place.rating || '–'}</small>
      `;
            li.addEventListener('click', () => {
                selectPlace(place);
            });
            resultsList.appendChild(li);

            // 2) drop a marker
            const m = new google.maps.Marker({
                position: place.geometry.location,
                map,
                icon: {
                    url: '/path/to/your-pin-icon.svg', // or use default
                    scaledSize: new google.maps.Size(32, 32)
                }
            });
            m.addListener('click', () => selectPlace(place));
            markers.push(m);
        });

        // 3) focus map on the first result
        const firstLoc = places[0].geometry.location;
        map.panTo(firstLoc);
        map.setZoom(15);
    });
}

function selectPlace(place) {
    // reposition main draggable marker
    marker.setPosition(place.geometry.location);
    map.panTo(place.geometry.location);
    updateCoordinates(place.geometry.location);
}

function clearMarkers() {
    markers.forEach(m => m.setMap(null));
    markers = [];
}

// writes into your hidden inputs
function updateCoordinates(latlng) {
    // If latlng has methods lat() / lng(), use them...
    const get = x => (typeof x === 'function' ? x() : x);
    const lat = get(latlng.lat);
    const lng = get(latlng.lng);

    const latEl = document.getElementById("latitude");
    const lngEl = document.getElementById("longitude");
    if (latEl) latEl.value = lat;
    if (lngEl) lngEl.value = lng;
}


// Expose initMap globally (for callback)
window.initMap = initMap;
