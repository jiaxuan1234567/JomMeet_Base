<?php
$_title = 'Create Gathering';
include '../app/Presentation/View/HomeView/header.php'
?>
<div class="container-fluid" id="mainContent">
    <div id="createGatheringForm">
        <div class="d-flex mb-4">
            <div class="col-2">
                <a href="javascript:void(0);" onclick="window.history.length > 1 ? history.back() : window.location.href='/'" class="btn btn-outline-secondary border-0">←</a>
            </div>
            <div class="col-8 text-center">
                <h2 class="fw-bold">Gathering Details</h2>
            </div>
            <div class="col-2">
            </div>
        </div>

        <div class="container">
            <form class="row my-4">
                <div class="col-12 text-center mb-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/1161/1161388.png" alt="Gathering Banner" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                    <h4 class="mt-2 fw-semibold">"Gathering tag"</h4>
                </div>
                <div class="col-12 row">
                    <label for="inputTheme" class="col-sm-1 form-label">Theme</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="inputTheme">
                    </div>
                    <label for="inputDate" class="col-sm-1 form-label">Date</label>
                    <div class="col-sm-5">
                        <input type="date" class="form-control" id="inputDate">
                    </div>
                </div>
                <div class="col-12 row">
                    <!-- No. Pax -->
                    <label for="inputPax" class="col-sm-1 form-label">No. Pax</label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" onclick="adjustPax(-1)">-</button>
                            <input type="number" class="form-control text-center" id="inputPax" value="1" min="1">
                            <button class="btn btn-outline-secondary" type="button" onclick="adjustPax(1)">+</button>
                        </div>
                    </div>

                    <!-- Time -->
                    <label for="startTime" class="col-sm-1 form-label">Time</label>
                    <div class="col-sm-5">
                        <div class="d-flex gap-2">
                            <input type="time" class="form-control" id="startTime">
                            <input type="time" class="form-control" id="endTime">
                        </div>
                    </div>
                </div>
                <div class="col-12 row">
                    <label for="inputLocation" class="col-sm-1 form-label">Location</label>
                    <div class="col-sm-11">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputLocation" placeholder="Select a location" readonly>
                            <button class="btn btn-outline-primary" id="chooseLocationBtn" type="button">Choose</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center gap-2">
                    <button type="reset" class="btn btn-secondary py-2 px-4">Reset</button>
                    <button type="submit" class="btn btn-primary py-2 px-4">Create</button>
                </div>
            </form>
        </div>
    </div>
    <div id="selectLocationForm"></div>

</div>




<script>
    function adjustPax(change) {
        const paxInput = document.getElementById('inputPax');
        let current = parseInt(paxInput.value) || 1;
        current += change;
        paxInput.value = current < 1 ? 1 : current;
    }

    // Store current state
    let currentPage = "create-gathering";

    // Initialize based on current state
    function initApp() {
        if (window.location.pathname.endsWith("/select-location")) {
            loadLocationPicker();
        } else {
            showGatheringForm();
        }
    }

    // Load location picker via AJAX
    function loadLocationPicker() {
        $.ajax({
            url: '/Presentation/View/GatheringView/select-location.php',
            method: 'GET',
            success: function(html) {
                $('#selectLocationForm').html(html);
                showLocationPicker();
            },
            error: function(xhr, status, error) {
                console.error('Failed to load location picker:', error);
                // Fallback to gathering form if location picker fails to load
                showGatheringForm();
            }
        });
    }

    // Show location picker (after content is loaded)
    function showLocationPicker() {
        currentPage = "select-location";
        history.pushState({
                page: "select-location"
            },
            "",
            "/own/create/select-location"
        );
        $("#createGatheringForm").hide();
        $("#selectLocationForm").show();
        initializeMap(); // Make sure this function exists
    }

    // Show gathering form
    function showGatheringForm() {
        currentPage = "create-gathering";
        history.pushState({
                page: "create-gathering"
            },
            "",
            "/own/create"
        );
        $("#selectLocationForm").hide();
        $("#createGatheringForm").show();
    }

    // Handle navigation
    window.addEventListener('popstate', function(event) {
        if (window.location.pathname.endsWith("/select-location")) {
            if (currentPage !== "select-location") {
                loadLocationPicker();
            }
        } else {
            showGatheringForm();
        }
    });

    // Initialize the app when DOM is ready
    $(document).ready(function() {
        initApp();

        // Example button click handler
        $('#chooseLocationBtn').click(function(e) {
            e.preventDefault();
            loadLocationPicker();
        });
    });
</script>

<?php include '../app/Presentation/View/HomeView/footer.php' ?>