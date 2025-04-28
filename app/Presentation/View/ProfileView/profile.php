<?php
$_title = 'Profile';
require_once __DIR__ . '/../HomeView/header.php';
?>

<div class="container my-5">
    <!-- Header with “Profile” title and edit button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Profile</h2>
    </div>

    <!-- Phone Number -->
    <div class="p-3 mb-3 border border-primary rounded"
        style="background-color: #eaf2ff;">
        <strong>Phone Number:</strong> 012 - 234 5678
    </div>

    <!-- Nickname -->
    <div class="p-3 mb-3 border border-primary rounded"
        style="background-color: #eaf2ff;">
        <strong>Nickname:</strong> Ironman
    </div>

    <!-- MBTI -->
    <div class="p-3 mb-3 border border-primary rounded"
        style="background-color: #eaf2ff;">
        <strong>MBTI:</strong> INTJ
    </div>

    <!-- About Me -->
    <div class="p-3 mb-3 border border-primary rounded"
        style="background-color: #eaf2ff;">
        <strong>About Me:</strong> I am a shy person who enjoys connecting with others at my own pace.
    </div>

    <!-- Hobbies -->
    <div class="p-3 mb-3 border border-primary rounded"
        style="background-color: #eaf2ff;">
        <h6 class="mb-2">Hobbies</h6>
        <button class="btn btn-outline-primary btn-sm me-2 mb-2">Swimming</button>
        <button class="btn btn-outline-primary btn-sm me-2 mb-2">Badminton</button>
        <button class="btn btn-outline-primary btn-sm mb-2">Singing</button>
    </div>

    <!-- Preference Gathering -->
    <div class="p-3 mb-3 border border-primary rounded"
        style="background-color: #eaf2ff;">
        <h6 class="mb-2">Preference Gathering</h6>
        <button class="btn btn-outline-primary btn-sm me-2 mb-2">Sports</button>
        <button class="btn btn-outline-primary btn-sm mb-2">Nature</button>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>