<?php
$_title = 'Reminder';
require_once __DIR__ . '/../HomeView/header.php';

// dummy data
$reminders = [
    [
        "reminderID" => 1,
        "gatheringID" => 1,
        "profileID" => 1,   // need to check if the user is host or not
        "description" => "Where should we meet?",
        "createdAt" => "6:00pm",
    ],
    [
        "reminderID" => 2,
        "gatheringID" => 1,
        "profileID" => 2,
        "description" => "I think main entrance is the best",
        "createdAt" => "6:00pm",
    ],
    [
        "reminderID" => 3,
        "gatheringID" => 1,
        "profileID" => 3,
        "description" => "I'm three member",
        "createdAt" => "6:00pm",
    ],
];
?>

<div class="container-fluid" style="min-height: 70vh;">
    <div class="row header-reminder mt-3">
        <div class="col-sm-1 text-center">
            <a href="/my-gathering" class="btn btn-primary px-4">Back</a>
        </div>
        <div class="col-sm-10">
            <div class="fw-bold text-center" style="font-size: 30px;">
                <?php echo htmlspecialchars($reminders[0]['gatheringID']); ?> {Gathering Name} Reminder
                <!-- Past gathering ID from gathering list -->
            </div>
        </div>
        <div class="col-sm-1 text-center">
            <a href="#" class="btn btn-primary px-4">Post</a>
        </div>
    </div>

    <hr class="hr pb-4" />

    <div class="container-fluid reminder-content">
        <?php if (!empty($reminders)): ?>
            <?php foreach ($reminders as $reminder): ?>
                <div class="row justify-content-md-center">
                    <div class="mb-5 card w-50 shadow-sm">

                        <div class="card-header row bg-blue-color">
                            <div class="role text-sm-start col-6">
                                <?php echo htmlspecialchars($reminder['profileID']); ?> Host / Participant
                            </div>
                            <div class="time text-sm-end col-6 text-muted">
                                <?php echo htmlspecialchars($reminder['createdAt']); ?>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($reminder['profileID']); ?> Username</h5>
                            <p class="card-text"><?php echo htmlspecialchars($reminder['description']); ?></p>
                        </div>
                        
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">No reminders found.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>