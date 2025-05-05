<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Spline Viewer for 3D Model -->
    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.79/build/spline-viewer.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>

    <!-- Global CSS -->
    <link rel="stylesheet" href="<?php echo (new FileHelper('asset'))->getFilePath('AppCSS') ?>">

    <!-- Global JS -->
    <!-- <script src="/js/app.js"></script> -->

    <!-- Favicon -->
    <!-- <link rel="icon" type="image/x-icon" href="asset/bubble.png"> -->

    <!-- Tab Title -->
    <title>Create Profile</title>

    <style>
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
</head>

<body style="background-color: #f5f5f7;">
    <main>
        <div class="container-fluid my-4">
            <div class="d-flex align-items-center mb-5">
                <div class="ms-3 mt-2">
                    <a href="/login">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#333333" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                        </svg>
                    </a>
                </div>
                <h2 class="fw-bold flex-grow-1 text-center">Welcome to JomMeet</h2>
            </div>
        </div>

        <form id="profileForm" action="/profile/create" method="POST">
            <!-- Nickname and MBTI -->
            <div class="row justify-content-center g-3 mb-4">
                <div class="col-12 col-md-9">
                    <label for="nickname" class="form-label fw-bold fs-5">Nickname</label>

                    <input type="text" id="nickname" name="nickname" class="form-control w-75  <?= isset($errors['nickname']) ? 'is-invalid' : '' ?>" maxlength="30" placeholder="Nickname for your profile." required />
                    <div id="nicknameCount" class="d-block text-end fs-6 w-75" style="color:#0C0C0D; opacity:40%;">0/20 characters</div>
                    <div class="invalid-feedback text-danger" id="errorNickname"> <?= $errors['nickname'] ?? '' ?>
                    </div>
                </div>

                <div class="col-12 col-md-1">
                    <label for="mbti" class="form-label fw-bold fs-5">MBTI</label>
                    <select id="mbti" class="form-select <?= isset($errors['mbti']) ? 'is-invalid' : '' ?>" name="mbti" required>
                        <option value="" disabled <?= 'selected' ?>>Select</option>
                        <?php foreach ($types as $t): ?>
                            <option
                                value="<?= htmlspecialchars($t) ?>">
                                <?= htmlspecialchars($t) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="errorMbti" class="invalid-feedback">
                        <?= $errors['mbti'] ?? '' ?>
                    </div>
                </div>
            </div>

            <!-- About Me -->
            <div class="row mb-4">
                <div class="col-12 col-md-10 offset-md-1">
                    <label class="form-label fw-bold fs-5">About Me</label>
                    <textarea id="aboutme" name="aboutme" class="form-control <?= isset($errors['aboutme']) ? 'is-invalid' : '' ?>" rows="4" maxlength="270" placeholder="Share a bit about yourself!" style="resize: none;" required></textarea>
                    <div id="aboutCount" class="d-block text-end fs-6" style="color:#0C0C0D; opacity:40%;">0/255 characters</div>
                    <div id="errorAboutme" class="invalid-feedback">
                        <?= $errors['aboutme'] ?? '' ?>
                    </div>
                </div>
            </div>

            <!-- Hobbies -->
            <div class="row mb-4">
                <div class="col-12 col-md-10 offset-md-1" style="display: grid;">
                    <h6 class="fw-bold fs-5">Hobbies</h6>
                    <div class="border rounded p-3 mb-4" id="hobbiesList" style="background-color: #ffffff;">
                        <?php foreach ($hobbyOptions as $hobby): ?>
                            <button
                                type="button"
                                class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                                data-value="<?= htmlspecialchars($hobby) ?>">
                                <?php echo htmlspecialchars($hobby) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <div id="errorHobbies" class="text-danger small">
                        <?= $errors['hobbies'] ?? '' ?>
                    </div>
                </div>
            </div>

            <!-- Preferences -->
            <div class="row mb-4">
                <div class="col-12 col-md-10 offset-md-1" style="display: grid;">
                    <h6 class="fw-bold fs-5">Preference Gathering</h6>
                    <div class="border rounded p-3 mb-4" id="preferencesList" style="background-color: #ffffff;">
                        <?php foreach ($preferenceOptions as $pref): ?>
                            <button
                                type="button"
                                class="btn btn-outline-dark w-100 pref-btn fw-bold"
                                style="<?= $btnCls ?>"
                                data-value="<?php echo htmlspecialchars($pref) ?>">
                                <?php echo htmlspecialchars($pref) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <div id="errorPreferences" class="text-danger small">
                        <?= $errors['preferences'] ?? '' ?>
                    </div>
                </div>
            </div>

            <input type="hidden" name="phone" value="<?= htmlspecialchars($_SESSION['new_profile']['phone']) ?>">
            <input type="hidden" name="password" value="<?= htmlspecialchars($_SESSION['new_profile']['password']) ?>">
            <input type="hidden" name="hobbies" id="hiddenHobbies">
            <input type="hidden" name="preferences" id="hiddenPrefs">

            <!-- Form Buttons -->
            <div class="col-12 d-flex justify-content-center gap-3 mt-4 mb-5">
                <button type="reset" id="resetBtn" class="btn btn-secondary py-2 px-4">Reset</button>
                <button type="submit" id="createBtn" class="btn btn-primary py-2 px-4">Create</button>
            </div>

        </form>

        <script>
            window.profileInit = {
                nickname: "",
                aboutme: "",
                mbti: "",
                hobbies: [],
                preferences: []
            };
        </script>

        <script src="/js/create-profile.js"></script>
    </main>

</body>

</html>