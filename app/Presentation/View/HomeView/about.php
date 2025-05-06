<?php
$_title = 'Help';
require_once __DIR__ . '/../HomeView/header.php';
?>

<div class="container py-5" style="min-height: 70vh;">
    <h1 class="mb-4 text-center">
        <a href="/" class="text-decoration-none">
            <img src="<?php echo (new FileHelper('asset'))->getFilePath('iconPNG') ?>" class="img-fluid mx-auto d-block" alt="Logo" width="300" />
            <h3 class="mt-2 text-black">JomMeet A NewFriend</h5>
        </a>
    </h1>

    <nav class="mb-5">
        <ul class="nav nav-pills justify-content-center gap-3">
            <li class="nav-item"><a class="nav-link" href="#aboutus">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="#contactus">Contact Us</a></li>
        </ul>
    </nav>

    <div class="container bg-blue-color p-4 rounded-3 shadow-sm mb-5 text-center">
        <section id="aboutus" class="px-3">
            <h2>About Us</h2>
            <div class="mt-4">
                <p>At JomMeet, we believe that great friendships start with shared experiences. Our platform connects individuals who are looking to meet new people, socialize, and create meaningful connections—all through fun and engaging gatherings.</p>
                <p>Whether you're new to a city, looking to expand your social circle, or simply want to try something new, JomMeet is here to help you find the perfect gathering that suits your interests.</p>
                <p>Simply choose your preferred location type, join a gathering, and let the conversations flow! With JomMeet, meeting new friends is as simple as selecting a place and showing up.
                Join us today and turn strangers into friends, one meetup at a time!</p>
            </div>
        </section>
    </div>

    <div class="container bg-blue-color p-4 rounded-3 shadow-sm mb-5 text-center">
        <section id="contactus" class="px-3">
            <h2>Contact Us</h2>
            
            <p>For inquiries, feedback, or assistance, please don't hesitate to contact us. Our dedicated team at JomMeet is here to address any questions or concerns you may have. Reach out to us via email at malaysiajommeet@gmail.com or give us a call at 03-1800-111-111. We look forward to hearing from you and assisting you with your needs.</p>

            <p>Working hour: 9am-5pm</p>

            <p>JomMeet Sdn. Bhd.</br>
            Unit C, Level 10, Menara KLCC</br>
            No. 5, Jalan Bumi 1,59000 Kuala Lumpur
            </p>

        </section>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>