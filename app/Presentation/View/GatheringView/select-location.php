<div class="row vh-100 g-0">
    <div class="col-md-4 bg-light d-flex flex-column p-3">
        <!-- Back + Search Bar -->
        <div class="input-group mb-3 position-relative">
            <button class="btn btn-outline-secondary" id="backToForm" type="button">&#8592;</button>
            <input
                type="text"
                id="searchBox"
                class="form-control"
                placeholder="Search Location"
                aria-label="Search Location">

            <button class="btn btn-outline-secondary" id="searchBtn" type="button">
                &#128269;
            </button>
        </div>
        <ul id="resultsList"
            class="list-group position-relative flex-grow-1 overflow-auto"
            style="z-index:1000;">
            <!-- JS will inject <li> items here -->
        </ul>
    </div>
    <div class="col-md-8 p-0">
        <div id="map" style="width:100%;height:100%"></div>
    </div>
</div>
<script src="../../../public/js/gatheringMap.js"></script>