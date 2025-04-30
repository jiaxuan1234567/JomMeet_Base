<?php
$_title = 'Profile';
require_once __DIR__ . '/../HomeView/header.php';
$userid = $_SESSION['profile_id'] ?? null;
?>

<div class="container-fluid my-3">
    <!-- Header with “Profile” title and edit button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-1 ms-1">Profile</h2>
        <div class="me-4 mb-1">
            <a href="/profile/edit">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Phone Number -->
    <div class="ms-5 p-2 mb-4 border border-primary rounded"
        style="background-color: #deecff; border-color:#0077CC !important; margin-right: 60px;">
        <strong>Phone Number:</strong>
        <?php echo htmlspecialchars($profile['phone']) ?>
    </div>

    <!-- Nickname -->
    <div class="ms-5 p-2 mb-4 border border-primary rounded"
        style="background-color: #deecff; border-color:#0077CC !important; margin-right: 60px;">
        <strong>Nickname:</strong>
        <?php echo htmlspecialchars($profile['nickname']) ?>
    </div>

    <!-- MBTI -->
    <div class="ms-5 p-2 mb-4 border border-primary rounded"
        style="background-color: #deecff; border-color:#0077CC !important; margin-right: 60px;">
        <strong>MBTI:</strong>
        <?php echo htmlspecialchars($profile['mbti']) ?>
    </div>

    <!-- About Me -->
    <div class="ms-5 p-2 mb-4 border border-primary rounded"
        style="background-color: #deecff; border-color:#0077CC !important; margin-right: 60px;">
        <strong>About Me:</strong>
        <?php echo htmlspecialchars($profile['aboutme']) ?>
    </div>

    <!-- Hobbies -->
    <div class="ms-5 p-2 mb-4 border border-primary rounded"
        style="background-color: #deecff; border-color:#0077CC !important; margin-right: 60px;">
        <h6 class="fw-bold mb-2">Hobbies</h6>
        <?php foreach ($profile['hobbies'] as $hobby): ?>
            <button class="btn btn-outline-primary btn-sm me-2 mb-2" style="color:#000000; border-color:#000000; background-color:#FFFFFF;">
                <?php echo htmlspecialchars($hobby) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Preference Gathering -->
    <div class="ms-5 p-2 mb-4 border border-primary rounded"
        style="background-color: #deecff; border-color:#0077CC !important; margin-right: 60px;">
        <h6 class="fw-bold mb-2">Preference Gathering</h6>
        <?php foreach ($profile['preferences'] as $preference): ?>
            <button class="btn btn-outline-primary btn-sm me-2 mb-2" style="color:#000000; border-color:#000000; background-color:#FFFFFF;">
                <?php echo htmlspecialchars($preference) ?>
            </button>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>