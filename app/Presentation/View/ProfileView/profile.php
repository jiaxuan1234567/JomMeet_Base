<?php
$_title = 'Profile';
require_once __DIR__ . '/../HomeView/header.php';
?>

<style>
    /* Profile PHP responsive grid via CSS */
    #hobbiesList,
    #preferencesList {
        padding-top: 10px;
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 1199px) {

        #hobbiesList,
        #preferencesList {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    @media (max-width: 991px) {

        #hobbiesList,
        #preferencesList {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 767px) {

        #hobbiesList,
        #preferencesList {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<?php if (!empty($_SESSION['flash_message'])): ?>
    <div id="flashMessage" class="flash-message" data-type="<?= $_SESSION['flash_type'] ?? '' ?>" data-msg="<?= $_SESSION['flash_message'] ?>">
    </div>
    <?php unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']) ?>
<?php endif; ?>

<!-- Header with “Profile” title and edit button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold ms-3 mt-3 mb-0">Profile</h2>
    <a href="/profile/edit">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-pencil-square" style="margin-right:70px; margin-top:20px;" viewBox="0 0 16 16">
            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
        </svg>
    </a>

</div>
<div class="container my-4">
    <!-- Phone Number -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-3 border border-primary rounded"
                style="background-color: #deecff; border-color:#0077CC;">
                <strong>Phone Number:</strong>
                <?php echo htmlspecialchars($profile['phone']) ?>
            </div>
        </div>
    </div>

    <!-- Nickname -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-3 border border-primary rounded"
                style="background-color: #deecff; border-color:#0077CC;">
                <strong>Nickname:</strong>
                <?php echo htmlspecialchars($profile['nickname']) ?>
            </div>
        </div>
    </div>

    <!-- MBTI -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-3 border border-primary rounded"
                style="background-color: #deecff; border-color:#0077CC;">
                <strong>MBTI:</strong>
                <?php echo htmlspecialchars($profile['mbti']) ?>
            </div>
        </div>
    </div>

    <!-- About Me -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-3 border border-primary rounded"
                style="background-color: #deecff; border-color:#0077CC;">
                <strong>About Me:</strong>
                <?php echo htmlspecialchars($profile['aboutme']) ?>
            </div>
        </div>
    </div>

    <!-- Hobbies -->
    <div class="p-2 mb-4 border border-primary rounded"
        style="background-color: #deecff; border-color:#0077CC !important;">
        <h6 class="fw-bold">Hobbies</h6>
        <div id="hobbiesList">
            <?php foreach ($profile['hobbies'] as $hobby): ?>
                <button class="btn btn-outline-primary w-100 btn-sm" style="color:#000000; border-color:#000000; background-color:#FFFFFF;">
                    <?php echo htmlspecialchars($hobby) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Preference Gathering -->
    <div class="p-2 mb-5 border border-primary rounded"
        style="background-color: #deecff; border-color:#0077CC !important;">
        <h6 class="fw-bold">Preference Gathering</h6>
        <div id="preferencesList">
            <?php foreach ($profile['preferences'] as $preference): ?>
                <button class="btn btn-outline-primary w-100 btn-sm" style="color:#000000; border-color:#000000; background-color:#FFFFFF;">
                    <?php echo htmlspecialchars($preference) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>