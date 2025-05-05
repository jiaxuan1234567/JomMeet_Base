<?php
$_title = 'Reminder';
require_once __DIR__ . '/../HomeView/header.php';
?>

<div class="container-fluid" style="min-height: 70vh;">
    <?php
    if (!empty($_SESSION['flash_message'])):
    ?>
        <div id="flashMessage"
            class="flash-message"
            data-type="<?= $_SESSION['flash_type'] ?? '' ?>"
            data-msg="<?= $_SESSION['flash_message'] ?>">
        </div>
        <?php
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        ?>
    <?php endif; ?>

    <div class="row header-reminder mt-3">
        <div class="col-sm-1 text-center pt-1">
            <a href="/my-gathering" class="bi bi-arrow-left text-black h3 mt-5"></a>
        </div>
        <div class="col-sm-10">
            <div class="fw-bold text-center" style="font-size: 30px;">
                <?php echo htmlspecialchars($gathering['theme']); ?> Reminder
            </div>
        </div>
        <div class="col-sm-1 text-center pt-1">

            <?php if (empty($reminders) && $userRole == 'Participant'): ?>
                <a href="#" class="btn btn-secondary px-4 disabled" aria-disabled="true">Post</a>

            <?php else: ?>
                <a href="#" class="btn btn-primary px-4" data-bs-toggle="collapse" data-bs-target="#createReminderForm">Post</a>
            <?php endif; ?>

        </div>
    </div>

    <hr class="hr pb-4" />

    <div class="container-fluid reminder-content">

        <?php
        $pre_desc = $_SESSION['previous_desc'] ?? '';
        unset($_SESSION['previous_desc']);

        if (!empty($_SESSION['validation_errors']) && $_SESSION['validation_errors']) {
            $show = 'collapse show';
        } else {
            $show = 'collapse';
        }

        unset($_SESSION['validation_errors']);

        ?>

        <div id="createReminderForm" class="<?php echo $show ?>">
            <div class="row justify-content-md-center">
                <div class="mb-4 card w-50 shadow-sm border-primary">
                    <div class="card-header bg-light text-primary fw-bold">
                        New Reminder
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/my-gathering/reminder/create">
                            <div class="mb-3">
                                <label for="description" class="form-label">Reminder Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($pre_desc ?? ''); ?></textarea>
                                <small id="descCount" class="form-text text-muted">0/255 characters</small>
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
                                <?php echo htmlspecialchars($reminder['role']); ?>
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
            <p class="text-center text-muted">No reminders sent.<br>The participants can only reply after you send a reminder.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    window.reminderInit = {
        description: <?php echo json_encode($pre_desc ?? ''); ?>
    };
</script>

<script src="/js/reminder.js"></script>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>