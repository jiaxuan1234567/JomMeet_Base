<?php
$_title = 'Create Gathering';
require_once __DIR__ . '/Users/tojiaxuan/Documents/GitHub/JomMeet_Base/app/Presentation/View/HomeView/header.php';
?>

<div class="container-fluid" id="mainContent">
    <div id="createGatheringForm">
        <div class="d-flex mb-4 align-items-center">
            <div class="col-2">
                <button class="btn btn-outline-secondary" id="backToForm" type="button">&#8592;</button>
            </div>
            <div class="col-8 text-center">
                <h2 class="fw-bold mb-0">Gathering Details</h2>
            </div>
            <div class="col-2"></div>
        </div>

        <div class="container">
            <form class="row g-4 my-4">

                <!-- Image + Gathering Tag -->
                <div class="col-12 text-center mb-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/1161/1161388.png" alt="Gathering Banner" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                    <h4 class="mt-2 fw-semibold">"Gathering tag"</h4>
                </div>

                <!-- Theme and Date -->
                <div class="col-12 row align-items-center">
                    <div class="col-sm-1 text-sm-end">
                        <label for="inputTheme" class="col-form-label fw-semibold">Theme</label>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="inputTheme">
                    </div>

                    <div class="col-sm-1 text-sm-end">
                        <label for="inputDate" class="col-form-label fw-semibold">Date</label>
                    </div>
                    <div class="col-sm-5">
                        <input type="date" class="form-control" id="inputDate">
                    </div>
                </div>

                <!-- Pax and Time -->
                <div class="col-12 row align-items-center">
                    <div class="col-sm-1 text-sm-end">
                        <label for="inputPax" class="col-form-label fw-semibold">No. Pax</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" onclick="adjustPax(-1)">-</button>
                            <input type="number" class="form-control text-center" id="inputPax" value="1" min="1">
                            <button class="btn btn-outline-secondary" type="button" onclick="adjustPax(1)">+</button>
                        </div>
                    </div>

                    <div class="col-sm-1 text-sm-end">
                        <label for="startTime" class="col-form-label fw-semibold">Time</label>
                    </div>
                    <div class="col-sm-5 d-flex gap-2">
                        <input type="time" class="form-control" id="startTime">
                        <input type="time" class="form-control" id="endTime">
                    </div>
                </div>

                <!-- Location -->
                <div class="col-12 row align-items-center">
                    <div class="col-sm-1 text-sm-end">
                        <label for="inputLocation" class="col-form-label fw-semibold">Location</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputLocation" placeholder="Select a location" readonly>
                            <button type="button" class="btn btn-outline-primary" id="chooseLocationBtn">Choose</button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 d-flex justify-content-center gap-3 mt-4">
                    <button type="reset" class="btn btn-secondary py-2 px-4">Reset</button>
                    <button type="submit" class="btn btn-primary py-2 px-4">Create</button>
                </div>

            </form>
        </div>
    </div>

    <div id="selectLocationForm"></div>
</div>


<script src="/js/gatheringMap.js"></script>

<script>
    function adjustPax(change) {
        const paxInput = document.getElementById('inputPax');
        let current = parseInt(paxInput.value) || 1;
        current += change;
        paxInput.value = current < 1 ? 1 : current;
    }

    function loadGoogleMapsApi(onLoad) {
        if (window.google && window.google.maps && google.maps.places) {
            return onLoad();
        }
        const s = document.createElement('script');
        s.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyCIm3LWq0gbsblgi0kmbEscuFq9zUoERD4&libraries=places&callback=initMap`;
        s.async = true;
        s.defer = true;
        s.onload = onLoad;
        document.head.appendChild(s);
    }

    // Store current state
    let currentPage = "create-gathering";

    // Initialize based on current state
    function initApp() {
        if (window.location.pathname.endsWith("/location")) {
            loadLocationPicker(false); // don’t push on first paint
        } else {
            showGatheringForm(false); // don’t push on first paint
        }
    }

    // Load location picker via AJAX
    function loadLocationPicker(addToHistory = true) {
        $.ajax({
            url: '/my-gathering/create/location',
            method: 'GET',
            success: function(html) {
                $('#selectLocationForm').html(html);
                showLocationPicker(addToHistory);
            },
            error: function(xhr, status, error) {
                console.error('Failed to load location picker:', error);
                // Fallback to gathering form if location picker fails to load
                showGatheringForm();
            }
        });
    }

    function showGatheringForm(addToHistory = true) {
        if (addToHistory) {
            history.pushState({
                page: "create-gathering"
            }, "", "/my-gathering/create");
        }
        $("#selectLocationForm").hide();
        $("#createGatheringForm").show();
    }

    function showLocationPicker(addToHistory = true) {
        if (addToHistory) {
            history.pushState({
                page: "select-location"
            }, "", "/my-gathering/create/location");
        }
        $("#createGatheringForm").hide();
        $("#selectLocationForm").show();

        loadGoogleMapsApi(function() {
            // now google.maps is ready, so initMap can run
            initMap();
        });
    }

    // First render – use **replaceState** so the first entry isn’t duplicated
    if (window.location.pathname.endsWith('/location')) {
        loadLocationPicker(false);
    } else {
        showGatheringForm(false);
    }

    // popstate – never push here
    window.addEventListener('popstate', () => {
        if (window.location.pathname.endsWith('/location')) {
            showLocationPicker(false);
        } else {
            showGatheringForm(false);
        }
    });

    // User-triggered navigation – keep pushing
    $('#chooseLocationBtn').on('click', () => {
        loadLocationPicker(); // default addToHistory = true
    });

    $(document).on('click', '#backToForm', e => {
        e.preventDefault();
        showGatheringForm(false);
    });
</script>

<?php require_once __DIR__ . '/Users/tojiaxuan/Documents/GitHub/JomMeet_Base/app/Presentation/View/HomeView/footer.php'; ?>