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
    <!-- <link rel="stylesheet" href="<?php echo (new FileHelper('asset'))->getFilePath('AppCSS') ?>"> -->

    <!-- Global JS -->
    <!-- <script src="/js/app.js"></script> -->

    <!-- Favicon -->
    <!-- <link rel="icon" type="image/x-icon" href="asset/bubble.png"> -->

    <!-- Tab Title -->
    <title>Create Profile</title>
</head>

<body style="background-color: #f5f5f7;">
    <main>
        <div class="container-fluid my-3">
            <!-- Header with “Profile” title and edit button -->
            <div class="d-flex align-items-center mb-5">
                <div class="ms-3 mt-2">
                    <!-- testing purpose -->
                    <a href="/profile/edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#333333" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                        </svg>
                    </a>
                </div>
                <h2 class="fw-bold flex-grow-1 text-center">Welcome to JomMeet</h2>
            </div>
        </div>

        <!-- <form class="d-flex" id="submit" style="width: 750px;" action="/profile/save" method="POST"> -->
        <!-- <input class="form-control me-2" name="searchTerm" type="search" placeholder="Search by theme, date, time, or preference" aria-label="Search" value="<?php echo htmlspecialchars($_POST['searchTerm'] ?? ''); ?>"> -->
        <!-- <button type="submit" class="btn btn-outline-primary">Search</button> -->

        <form id="profileForm" action="/profile/create" method="POST">
            <!-- Nickname and MBTI -->
            <div class="row justify-content-center g-3 mb-4">
                <div class="col-md-9">
                    <label for="nickname" class="form-label fw-bold fs-5">Nickname</label>

                    <input type="text" name="nickname" class="form-control w-75" placeholder="Nickname for your profile." required />
                    <div class="d-block text-end fs-6 w-75" style="color:#0C0C0D; opacity:40%;">0/20 characters</div>
                </div>
                <div class="col-md-1">
                    <label for="mbti" class="form-label fw-bold fs-5">MBTI</label>
                    <select class="form-select" name="mbti" required>
                        <option value="" disabled selected>Select</option>
                        <option>INTJ</option>
                        <option>INTP</option>
                        <option>ENTJ</option>
                        <option>ENTP</option>
                        <option>INFJ</option>
                        <option>INFP</option>
                        <option>ENFJ</option>
                        <option>ENFP</option>
                        <option>ISTJ</option>
                        <option>ISFJ</option>
                        <option>ESTJ</option>
                        <option>ESFJ</option>
                        <option>ISTP</option>
                        <option>ISFP</option>
                        <option>ESTP</option>
                        <option>ESFP</option>
                    </select>
                </div>
            </div>

            <!-- About Me -->
            <div class="row mb-4">
                <div class="col-md-10 offset-md-1">
                    <label class="form-label fw-bold fs-5">About Me</label>
                    <textarea name="about_me" class="form-control" rows="4" placeholder="Share a bit about yourself!" style="resize: none;" required></textarea>
                    <div class="d-block text-end fs-6" style="color:#0C0C0D; opacity:40%;">0/255 characters</div>
                </div>
            </div>

            <!-- Hobbies -->
            <div class="row mb-4">
                <div class="col-md-10 offset-md-1" style="display: grid;">
                    <h6 class="fw-bold fs-5">Hobbies</h6>
                    <div class="border rounded p-3 mb-4" id="hobbiesList" style="display: grid;grid-template-columns: repeat(8, 1fr); gap: 1.5rem; background-color: #ffffff;">
                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Basketball">
                            Basketball
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Badminton">
                            Badminton
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Hiking">
                            Hiking
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Singing">
                            Singing
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Photography">
                            Photography
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Reading">
                            Reading
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Jogging">
                            Jogging
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Camping">
                            Camping
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Traveling">
                            Traveling
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Swimming">
                            Swimming
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Yoga">
                            Yoga
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Meditation">
                            Meditation
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Drawing">
                            Drawing
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Painting">
                            Painting
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Squash">
                            Squash
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Gym">
                            Gym
                        </button>
                    </div>
                </div>
            </div>

            <!-- Preferences -->
            <div class="row mb-4">
                <div class="col-md-10 offset-md-1" style="display: grid;">
                    <h6 class="fw-bold fs-5">Preference Gathering</h6>
                    <div class="border rounded p-3 mb-4" id="hobbiesList" style="display: grid;grid-template-columns: repeat(8, 1fr); gap: 1.5rem; background-color: #ffffff;">

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Entertainment">
                            Entertainment
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Sports">
                            Sports
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Dining">
                            Dining
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Nature">
                            Nature
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Hangout">
                            Hangout
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Coffee">
                            Coffee
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Picnic">
                            Picnic
                        </button>

                        <button type="button"
                            class="btn btn-outline-dark w-100 hobby-btn fw-bold"
                            data-value="Chill">
                            Chill
                        </button>
                    </div>
                </div>
            </div>


            <!-- Form Buttons -->
            <div class="col-12 d-flex justify-content-center gap-3 mt-4">
                    <button type="reset" class="btn btn-secondary py-2 px-4">Reset</button>
                    <button type="submit" class="btn btn-primary py-2 px-4">Create</button>
            </div>

        </form>

    </main>

    <script>
        function toggleSelection(button, inputName) {
            const value = button.getAttribute('data-value');
            const isActive = button.classList.contains('active');

            if (!isActive) {
                button.classList.add('active');
                button.classList.replace('btn-outline-dark', 'btn-dark');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = inputName + '[]';
                input.value = value;
                input.setAttribute('data-key', value);
                document.getElementById('hiddenInputs').appendChild(input);
            } else {
                button.classList.remove('active');
                button.classList.replace('btn-dark', 'btn-outline-dark');
                document.querySelector(`input[data-key="${value}"]`)?.remove();
            }
        }

        document.querySelectorAll('.hobby-btn').forEach(btn =>
            btn.addEventListener('click', () => toggleSelection(btn, 'hobbies'))
        );
        document.querySelectorAll('.pref-btn').forEach(btn =>
            btn.addEventListener('click', () => toggleSelection(btn, 'preferences'))
        );
    </script>
</body>

</html>