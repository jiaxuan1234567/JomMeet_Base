<?php
$_title = 'Select Location';
require_once __DIR__ . '/../HomeView/header.php';
?>

<div class="row vh-100 g-0">
    <div class="col-md-4 bg-light d-flex flex-column p-2">
        <!-- Back + Search Bar -->
        <div class="input-group mb-3 border border-black rounded align-items-center flex-nowrap">
            <span class="input-group-text border-0 bg-white d-flex align-items-center justify-content-center">
                <a href="/my-gathering/create"><i class=" bi bi-arrow-left text-black h5 m-0" id="back" style="cursor:pointer;"></i></a>
            </span>

            <div class="position-relative flex-grow-1">
                <input
                    type="text"
                    id="searchBox"
                    class="form-control border-0 fw-bold pe-5"
                    placeholder="Search Location"
                    aria-label="Search Location">

                <i class="bi bi-x-lg text-muted position-absolute top-50 end-0 translate-middle-y me-3 d-none"
                    id="clearText"
                    style="cursor: pointer;"></i>

                <div class="position-absolute top-50 end-0 translate-middle-y d-none" id="vertBar"
                    style="height: 1.5rem; width: 1px; background-color: #888888;">
                </div>
            </div>

            <span class="input-group-text border-0 bg-white d-flex align-items-center justify-content-center">
                <i class="bi bi-search h5 m-0" id="searchBtn" style="cursor:pointer;"></i>
            </span>
        </div>

        <ul id="resultsList"
            class="list-group position-relative flex-grow-1 overflow-auto"
            style="z-index:1000;">
            <!-- JS will inject <li> items here -->
        </ul>
    </div>
    <div class="col-md-8 p-0 position-relative">
        <!-- Loading UI -->
        <div id="mapLoadingOverlay" class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center bg-white">
            <div class="spinner-border text-blue-color" role="status">
                <span class="visually-hidden">Loading map...</span>
            </div>
        </div>

        <!-- Maps Content -->
        <div id="map" class="w-100 h-100"></div>

        <!-- move the detailPanel here -->
        <div id="detailOverlay" class="position-absolute w-100 h-100 top-0 start-0" style="display: none; z-index: 10;">
            <div id="detailPanel" class="position-absolute"></div>
        </div>

        <!-- Feedback Panel -->
        <div id="feedbackPanel" class="position-absolute shadow bg-white rounded-4 p-3" style="display: none; width: 260px; top: 10px; left: 280px; z-index: 1002;"></div>
    </div>
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIm3LWq0gbsblgi0kmbEscuFq9zUoERD4&v=beta&libraries=places&loading=async&callback=initMap">
</script>

<script src="/js/gatheringMap.js"></script>


<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>