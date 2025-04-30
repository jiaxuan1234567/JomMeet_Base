<?php
$_title = 'Selecet Location';
require_once __DIR__ . '/../HomeView/header.php';
?>

<style>
    #searchBox:focus {
        box-shadow: none;
        outline: none;
    }

    input[type="search"]::-webkit-search-decoration,
    input[type="search"]::-webkit-search-cancel-button,
    input[type="search"]::-webkit-search-results-button,
    input[type="search"]::-webkit-search-results-decoration {
        display: none;
    }
</style>

<script>
    // so JS knows where to send the user back
    window.redirectUrl = '<?= addslashes($redirectUrl) ?>';
</script>

<div class="row vh-100 g-0">
    <div class="col-md-4 bg-light d-flex flex-column p-2">
        <!-- Back + Search Bar -->
        <div class="input-group mb-3 border border-black rounded align-items-center flex-nowrap">
            <span class="input-group-text border-0 bg-white d-flex align-items-center justify-content-center">
                <i class="bi bi-arrow-left h5 m-0" id="backToLastPage" style="cursor:pointer;"></i>
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
        <div id="map" class="w-100 h-100"></div>

        <!-- move the detailPanel here -->
        <div
            id="detailPanel"
            class="position-absolute ms-3 bg-white shadow rounded p-3 w-50"
            style="display: none;"></div>
    </div>
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIm3LWq0gbsblgi0kmbEscuFq9zUoERD4&v=beta&libraries=places&loading=async&callback=initMap">
</script>

<script src="/js/gatheringMap.js"></script>


<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>