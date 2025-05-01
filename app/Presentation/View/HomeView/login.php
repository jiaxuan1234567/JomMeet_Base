<?php
$title = "Login";

use Presentation\Controller\GatheringController\HomeController;

$asset = new FileHelper('asset');
?>
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>

<link rel="stylesheet" href="<?php echo (new FileHelper('asset'))->getFilePath('AppCSS') ?>">


<div class="container mt-5">
    <div class="text-center">
        <img src="<?= $asset->getFilePath('iconPNG'); ?>" alt="" class="mx-auto d-block" style="width:150px; height:150px;">
        <h1 class="text-center mb-4 fs-1">Login</h1>
    </div>
    <div class="row justify-content-center align-items-center" style="height: 400px;">
        <div class="col-md-6">
            <div class="card shadow mx-auto" style="max-width: 1000px;">
                <div class="card-body bg-blue-color" style="height: 250px;">
                    <form action="login/process" method="POST">
                        <div class="mb-3 row align-items-center">
                            <label for="phoneNumber" class="col-sm-4 col-form-label">Phone Number</label>
                            <div class="col-sm-8">
                                <input type="tel"
                                    class="form-control"
                                    id="phoneNumber"
                                    name="phoneNumber"
                                    placeholder="Enter your phone number"
                                    required
                                    pattern="[0-9]{10,11}"
                                    title="Please enter a valid phone number">
                            </div>
                        </div>

                        <div class="mb-3 row align-items-center">
                            <label for="password" class="col-sm-4 col-form-label">Password</label>
                            <div class="col-sm-8">
                                <input type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    placeholder="Enter your password"
                                    required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        Don't have an account? <a href="">Sign up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 15px;
        border: none;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px;
    }

    .btn-primary {
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>