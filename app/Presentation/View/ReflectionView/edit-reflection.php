<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
$_title = 'Create Reflection';
require_once __DIR__ . '/../HomeView/header.php';

$errors = $_SESSION['reflectionErrors'] ?? [];
$old = $_SESSION['old'] ?? [];

// Clear after use
unset($_SESSION['reflectionErrors']);
unset($_SESSION['old']); //user no need to redo


?>

<?php
var_dump($reflectionSelected); // Debugging the data
?>

<div class="main">
    <div class="container-fluid py-3">
        <h4>
            <a href="/reflection">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                </svg>
            </a>
            Edit Self-Reflection
        </h4>
    </div>
    <div class="container mb-5">
        <div class="container border border-2 rounded-3 m-2 align-content-center" style="background-color: rgba(222,236,255,68); border-color:#0077CC !important;">
            <div class="container d-flex align-item-center justify-content-center">
            <form method="post" action="/reflection/edit/<?php echo $reflectionSelected['selfreflectID']; ?>" class="form">
                    <div class="my-3">
                        <h4><label for="date">Date</label></h4>
                        <input type="text" id="reflectionDate" name="reflectionDate" value="<?php echo $reflectionSelected['date'] ?? '' ?>" style="width:500px;" readonly>
                    </div>
                    
                    <div class="my-3">
                        <h4><label for="title">Title</label></h4>
                        <input type="text" id="reflectionTitle" name="reflectionTitle" value="<?php echo $reflectionSelected['title'] ?? '' ?>" maxlength="255" style="width:500px;">
                    </div>

                    <div class="my-3">
                    <h4><label for="content">Content</label></h4>
                        <textarea id="reflectionContent" name="reflectionContent" style="width:500px;height:200px;"><?php echo htmlspecialchars($reflectionSelected['content'] ?? ''); ?></textarea>
                    </div>

                    <section class="d-flex justify-content-center my-3">
                        <button class="btn btn-primary rounded-pill" type="submit" style="width:300px;">Submit</button>
                    </section>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>