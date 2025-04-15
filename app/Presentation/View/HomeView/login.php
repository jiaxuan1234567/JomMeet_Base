<?php
require_once './fileRegister.php';

include getFilePath('Header');
?>

<div class="container">
    <div>
        <spline-viewer url="https://prod.spline.design/VDuiMvkxF4DPHkAv/scene.splinecode" class="spline-model"></spline-viewer>
    </div>
    <div>
        <h1 class="fw-bold display-5 mb-4 text-center">Log In</h1>
    </div>
    <div class="container-sm bg-blue-color p-4">
        <form action="">

            <div class="row justify-content-center mb-3">
                <label for="phone" class="col-sm-2 col-form-label fw-bold col-form-label-lg text-end">Phone Number</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control form-control-lg" id="phone" placeholder="Enter phone number here">
                </div>
            </div>

            <div class="row justify-content-center mb-3">
                <label for="password" class="col-sm-2 col-form-label fw-bold col-form-label-lg text-end">Password</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control form-control-lg" id="password" placeholder="Enter password here">
                </div>
            </div>

            <div class="row justify-content-center mt-3">
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
                </div>
            </div>
            <div class="row justify-content-center mt-3">
                <div class="col-sm-8 text-center">
                    Don't have an account?
                    <a href="#">Sign up</a>
                </div>
            </div>
        </form>
    </div>

</div>

<?php include getFilePath('Footer'); ?>