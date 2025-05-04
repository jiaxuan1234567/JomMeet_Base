<?php
$_title = 'Reminder';
require_once __DIR__ . '/../HomeView/header.php';
?>

<div class="container-fluid" style="min-height: 70vh;">
    <div class="row header-reminder mt-3">
        <div class="col-sm-1 text-center">
            <a href="/my-gathering" class="btn btn-primary px-4">Back</a>
        </div>
        <div class="col-sm-10">
            <div class="fw-bold text-center" style="font-size: 30px;">
                <?php echo htmlspecialchars($gathering['theme']); ?> Reminder
            </div>
        </div>
        <div class="col-sm-1 text-center">
            <a href="#" class="btn btn-primary px-4" data-bs-toggle="collapse" data-bs-target="#createReminderForm">Post</a>
        </div>
    </div>

    <hr class="hr pb-4" />

    <div class="container-fluid reminder-content">

        <div id="createReminderForm" class="collapse">
            <div class="row justify-content-md-center">
                <div class="mb-4 card w-50 shadow-sm border-primary">
                    <div class="card-header bg-light text-primary fw-bold">
                        New Reminder
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/my-gathering/reminder/create">
                            <div class="mb-3">
                                <label for="description" class="form-label">Reminder Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <input type="hidden" name="gatheringID" value="<?php echo htmlspecialchars($gathering['gatheringID']); ?>">
                            <button type="submit" class="btn btn-primary">Post Reminder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($reminders)): ?>
            <?php foreach ($reminders as $reminder): ?>
                <div class="row justify-content-md-center">
                    <div class="mb-5 card w-50 shadow-sm">

                        <div class="card-header row bg-blue-color">
                            <div class="role text-sm-start col-6">
                                <?php echo htmlspecialchars($reminder['profileID']); ?> Host / Participant
                            </div>
                            <div class="time text-sm-end col-6 text-muted">
                                <?php echo htmlspecialchars($reminder['timeAgo']); ?>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($reminder['nickname']); ?></h5>
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