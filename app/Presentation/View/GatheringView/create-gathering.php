<?php
$_title = 'Create Gathering';
require_once __DIR__ . '/../HomeView/header.php';

$asset = new FileHelper('asset');
?>

<div class="container-fluid" id="mainContent" style="min-height: 70vh;">
    <div id="createGatheringForm">
        <div class="d-flex mb-4 align-items-center border-bottom border-2 px-2 py-3">
            <div class="col-2">
                <a href="/my-gathering"><i class="bi bi-arrow-left text-black h3 m-0" id="back" style="cursor:pointer;"></i></a>
            </div>
            <div class="col-8 text-center">
                <h2 class="fw-bold mb-0 h5">Gathering Details</h2>
            </div>
            <div class="col-2"></div>
        </div>

        <div class="container">
            <form class="row my-4" id="createGatheringFormEl" action="/my-gathering/create" method="post">

                <!-- Image + Gathering Tag -->
                <div class="col-12 text-center mb-4">
                    <!-- Outer ring wrapper -->
                    <div class="position-relative d-inline-block" style="width: 130px; height: 130px;">
                        <!-- Ring border -->
                        <div class="rounded-circle border border-dark w-100 h-100 position-absolute top-0 start-0"></div>
                        <button type="button"
                            class="btn rounded-circle overflow-hidden p-0 border-0 position-absolute top-50 start-50 translate-middle"
                            id="selectTagBtn"
                            style="width: 120px; height: 120px;">

                            <!-- Tag Image -->
                            <img src="<?= $asset->getFilePath('defaultTag') ?>"
                                id="selectedTagImage"
                                class="w-100 h-100 rounded-circle object-fit-cover">

                            <!-- Overlay with icon -->
                            <div class="edit-icon-overlay position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center rounded-circle bg-opacity-25">
                                <i class="bi bi-pencil-square text-white display-4"></i>
                            </div>
                        </button>
                    </div>
                    <h5 class="mt-3 fw-bold mb-0" id="selectedTagLabel">Select A Preference</h5>
                    <input type="hidden" name="gatheringTag" id="gatheringTag">
                </div>

                <!-- Theme and Date -->
                <div class="col-12 row align-items-center mb-4">
                    <div class="col-1 text-end">
                        <label for="inputTheme" class="col-form-label fw-semibold">Theme</label>
                    </div>
                    <div class="col-5">
                        <div class="gathering-input-wrapper position-relative">
                            <input type="text" id="inputTheme" name="inputTheme"
                                class="form-control gathering-input w-100 text-start"
                                placeholder="Enter Gathering Theme">
                        </div>
                    </div>

                    <div class="col-1 text-end">
                        <label for="inputDate" class="col-form-label fw-semibold">Date</label>
                    </div>
                    <div class="col-5">
                        <div class="gathering-input-wrapper position-relative">
                            <input type="date"
                                id="inputDate"
                                name="inputDate"
                                class="form-control gathering-input text-center"
                                value="<?= htmlspecialchars($allowedDate) ?>"
                                min="<?= $allowedDate ?>">
                            <button type="button" id="triggerDatePicker"
                                class="btn btn-primary button-blue-color text-white gathering-button">
                                <i class="bi bi-calendar-event-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pax and Time -->
                <div class="col-12 row align-items-center mb-4">
                    <div class="col-1 text-end">
                        <label for="inputPax" class="col-form-label fw-semibold">No. Pax</label>
                    </div>
                    <div class="col-5">
                        <div class="gathering-input-wrapper position-relative">
                            <button class="btn btn-primary button-blue-color text-white gathering-button"
                                id="decreasePax" type="button">−</button>

                            <input type="number"
                                id="inputPax"
                                name="inputPax"
                                class="form-control gathering-input text-center"
                                value="<?= htmlspecialchars($paxLimit['minPax']) ?>"
                                min="<?= $paxLimit['minPax'] ?>" max="<?= $paxLimit['maxPax'] ?>" readonly
                                style="max-width: 60px;">

                            <button class="btn btn-primary button-blue-color text-white gathering-button"
                                id="increasePax" type="button">+</button>
                        </div>
                    </div>

                    <div class="col-1 text-end">
                        <label for="startTime" class="col-form-label fw-semibold">Time</label>
                    </div>
                    <div class="col-5">
                        <div class="d-flex gathering-input-wrapper gap-2 ">
                            <div class="flex-grow-1 position-relative">
                                <input type="time"
                                    id="startTime"
                                    name="startTime"
                                    step="60"
                                    class="form-control gathering-input text-center time-blue btn btn-primary">
                            </div>

                            <span class="fw-bold d-flex align-items-center">to</span>

                            <div class="flex-grow-1 position-relative">
                                <small id="nextDayNote" class="text-black position-absolute end-0 top-100" style="display: none;">
                                    (next day)
                                </small>
                                <input type="time"
                                    id="endTime"
                                    name="endTime"
                                    step="60"
                                    class="form-control gathering-input text-center time-blue btn btn-primary">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="col-12 row align-items-center mb-4">
                    <div class="col-1 text-end">
                        <label for="inputLocation" class="col-form-label fw-semibold">Location</label>
                    </div>
                    <div class="col-11">
                        <div class="gathering-input-wrapper position-relative">
                            <input type="text"
                                id="inputLocation"
                                name="inputLocation"
                                class="form-control gathering-input text-start"
                                placeholder="Select a location"
                                readonly
                                style="cursor: default;">

                            <input type="hidden" name="locationId" id="locationId">

                            <a href="/my-gathering/create/location"
                                class="btn btn-primary button-blue-color text-white gathering-button border-0"
                                id="chooseLocationBtn">
                                Choose
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-start">
                    <div class="text-danger small error-message" id="error-gatheringTag"></div>
                    <div class="text-danger small error-message" id="error-inputTheme"></div>
                    <div class="text-danger small error-message" id="error-inputDate"></div>
                    <div class="text-danger small error-message" id="error-inputPax"></div>
                    <div class="text-danger small error-message" id="error-startTime"></div>
                    <div class="text-danger small error-message" id="error-endTime"></div>
                    <div class="text-danger small error-message" id="error-inputLocation"></div>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 d-flex justify-content-center gap-3 mt-4">
                    <button type="reset" class="btn border-black rounded-1 px-4 py-2 fw-semibold" id="createResetBtn">
                        Reset
                    </button>
                    <button type="submit" class="btn btn-primary py-2 px-4 button-blue-color border-0" id="createBtn" disabled>Create</button>
                </div>

            </form>
        </div>
    </div>

    <div id="selectLocationForm"></div>

    <!-- Select Preference Modal -->
    <div class="modal fade" id="tagSelectionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3 rounded-4 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Select a Preference</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row row-cols-3 g-4 justify-content-center">
                        <?php foreach ($preferenceTags as $tag): ?>
                            <div class="col text-center tag-option px-2"
                                data-value="<?= $tag['value'] ?>"
                                data-label="<?= $tag['label'] ?>"
                                data-image="<?= $tag['image'] ?>"
                                style="cursor: pointer;">
                                <div class="position-relative mx-auto"
                                    style="width: 80px; height: 80px;">
                                    <img src="<?= $tag['image'] ?>"
                                        class="rounded-circle border border-2 border-secondary shadow-sm tag-image w-100 h-100"
                                        style="object-fit: cover;">
                                </div>
                                <p class="small fw-semibold mt-2 mb-0"><?= $tag['label'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script src="/js/gatheringForm.js"></script> -->
<script src="/js/gatheringFormCommon.js"></script>
<script src="/js/gatheringCreate.js"></script>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>