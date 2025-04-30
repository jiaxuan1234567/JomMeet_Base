function initMap() {
    const mapEl = document.getElementById("map");

    const location = {
        lat: parseFloat(mapEl.dataset.lat),
        lng: parseFloat(mapEl.dataset.lng)
    };

    const map = new google.maps.Map(document.getElementById("map"), {
        center: location,
        zoom: 15,
        mapTypeControl: false,
        fullscreenControl: false,
        streetViewControl: false,
        zoomControl: false,
        clickableIcons: false,
        disableDefaultUI: true
    });

    new google.maps.Marker({
        position: location,
        map: map,
        icon: {
            url: '/asset/geo-alt.svg',
            scaledSize: new google.maps.Size(32, 32)
        }
    });

    map.addListener("click", function (e) {
        e.stop();
    });
}

// Load map after script is ready
window.initMap = initMap;