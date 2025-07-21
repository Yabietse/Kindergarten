<?php
session_start(); // Start the session for user authentication

// Check if a user is logged in
$loggedIn = isset($_SESSION['user_id']);
$userEmail = $loggedIn ? $_SESSION['user_email'] : '';
$userId = $loggedIn ? $_SESSION['user_id'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Childrens Learning Ground</title>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- lightgallery css cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/css/lightgallery.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- header section starts -->

    <header class="header">

        <a href="#" class="logo"> <i class="fas fa-school"></i> Kindergarten</a>

        <nav class="navbar">
            <a href="#home">home</a>
            <a href="#about">about</a>
            <a href="yang 2.html">Lessons</a>
            <a href="#education">Quiz</a>
            <a href="#teacher">teacher</a>
            <a href="#activities">Activities</a>
            <a href="#gallery">gallery</a>
            <a href="#contact">contact</a>
        </nav>

        <div class="icons">
            <!-- Login/User Account Icon - Toggles based on login status -->
            <div class="fas fa-user" id="auth-icon"></div>
            <div class="fas fa-bars" id="menu-btn"></div>
        </div>

        <!-- Login/Registration Form -->
        <form action="#" class="login-form" id="loginRegisterForm">
            <h3 id="form-title">login now</h3>
            <input type="email" placeholder="your email" class="box" id="auth-email" required>
            <input type="password" placeholder="your password" class="box" id="auth-password" required>
            <p id="forgot-password-link">forget your password <a href="#">click here</a> </p>
            <input type="submit" value="login now" class="btn" id="auth-submit-btn">
            <p id="toggle-auth-mode-text">don't have an account <a href="#" id="toggle-register-login">register now</a> </p>
        </form>

        <!-- User Account Dropdown (initially hidden) -->
        <div class="user-account-dropdown" id="userAccountDropdown">
            <p>Welcome, <span id="user-display-email"><?php echo htmlspecialchars($userEmail); ?></span>!</p>
            <p>User ID: <span id="user-display-id"><?php echo htmlspecialchars($userId); ?></span></p>
            <a href="pages/account_manager.php" class="btn" id="manage-account-btn-direct-link">Manage Account</a>
            <a href="#" class="" id=""></a>
            <a href="#" class="dropdown-item" id="sign-out-btn">Sign Out</a>
        </div>

    </header>

    <!-- header section ends -->

    <!-- home section starts -->

    <section class="home" id="home">

        <div class="content">
            <h3>welcome to our <span>Kindergarten</span></h3>
            <p>wellcome to our website in this website you can be able to get a Music, Sport, Drawing Mathematics, Amharic, English and Sciense subjects with experienced teachers, videos and take a quiz to know how your kids are doing with their current knowledge this site have all this 4 courses from kingergarten up to third grade students</p>
            <a href="learn_more.html" class="btn" id="learnMoreBtn">learn more</a>
        </div>

        <div class="image">
            <img src="images/home.png" alt="">
        </div>

        <div class="custom-shape-divider-bottom-1684324473">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
            </svg>
        </div>

    </section>

    <!-- home section ends -->

    <!-- about us section starts -->

    <section class="about" id="about">

        <h1 class="heading"> <span>about</span> us</h1>

        <div class="row">

            <div class="image">
                <img src="images/about us.png" alt="">
            </div>

            <div class="content">
                <h3>Exploring, Growing, And Thriving Together</h3>
                <p>we are trying to help the future of ethiopia's generation to  easily get their sense of education by helping them get interested in education by making the subjects with the fun and easily understandable ways to interact with them.</p>
                <p>our teachers are well experienced with teaching kids and we also prepared a very good and fun videos about education to prevent kids from being bored to make them not lose interest.</p>
                <a href="read_more.html" class="btn" id="readMoreAboutBtn">read more</a>
            </div>

        </div>

    </section>

    <!-- about us section ends -->

    <!-- quiz section start -->

    <section class="education" id="education">

    <h1 class="heading">quiz <span> section</span></h1>

    <div class="box-container">

        <!-- Music Quiz -->
        <a href="music_quiz.html" class="box" data-video-src="videos/music_lesson.mp4" data-lesson-name="Music Lessons">
            <h3>music quiz</h3>
            <p>Music quiz for kinergarten up to 3rd grade</p>
            <img src="images/education1.png" alt="">
        </a>

        <!-- Sports Quiz -->
        <a href="sport_quiz.html" class="box" data-video-src="videos/sports_lesson.mp4" data-lesson-name="Sports Lessons">
            <h3>sports quiz</h3>
            <p>Sport quiz for kinergarten up to 3rd grade</p>
            <img src="images/education2.png" alt="">
        </a>

        <!-- Drawing Quiz -->
        <a href="drawing_quiz.html" class="box" data-video-src="videos/drawing_lesson.mp4" data-lesson-name="Drawing Lessons">
            <h3>drawing quiz</h3>
            <p>drawing quiz for kinergarten up to 3rd grade</p>
            <img src="images/education3.png" alt="">
        </a>

        <!-- Drawing Art -->
        <a href="drawing_2.html" class="box" data-video-src="videos/drawing_lesson.mp4" data-lesson-name="Drawing Lessons">
            <h3>drawing art</h3>
            <p>drawing practice for kinergarten up to 3rd grade</p>
            <img src="images/education3.png" alt="">
        </a>

        <!-- Maths Quiz -->
        <a href="maths_quiz.html" class="box" data-video-src="videos/maths_lesson.mp4" data-lesson-name="Mathematics Lessons">
            <h3>Maths quiz</h3>
            <p>mathematics quiz for kinergarten up to 3rd grade</p>
            <img src="images/maths.png" alt="">
        </a>

        <!-- Amharic Quiz -->
        <a href="amharic_quiz.html" class="box" data-video-src="videos/amharic_lesson.mp4" data-lesson-name="Amharic Lessons">
            <h3>Amharic quiz</h3>
            <p>amharic quiz for kinergarten up to 3rd grade</p>
            <img src="images/amharic.png" alt="">
        </a>

        <!-- English Quiz (already an anchor tag) -->
        <a href="english_quiz.html" class="box" data-video-src="videos/english_lesson.mp4" data-lesson-name="English Lessons">
            <h3>English quiz</h3>
            <p>English quiz for kinergarten up to 3rd grade</p>
            <img src="images/english.png" alt="">
        </a>

        <!-- Science Quiz -->
        <a href="science_quiz.html" class="box" data-video-src="videos/science_lesson.mp4" data-lesson-name="Science Lessons">
            <h3>Sciense quiz</h3>
            <p>sciense quiz for kinergarten up to 3rd grade</p>
            <img src="images/sciense.png" alt="">
        </a>

    </div>

</section>
    <!-- quiz section ends -->

    <!-- teacher section starts -->

   <section class="teacher" id="teacher">

    <h1 class="heading">our <span> teacher</span></h1>

    <div class="box-container">

        <div class="box">
            <img src="images/teacher1.png" alt="">
            <h3>abebec tibebu</h3>
            <p>Amharic and English instructor</p>
            <div class="share">
                <a href="https://web.facebook.com/groups/2733795403523028" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://x.com/masakakids" target="_blank">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://www.instagram.com/kidseducation___/" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>

         <div class="break"></div>

        <div class="box">
            <img src="images/teacher2.png" alt="">
            <h3>asnaku tomas</h3>
            <p>Science and Sport instructor</p>
            <div class="share">
                <a href="https://web.facebook.com/groups/2733795403523028" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://x.com/masakakids" target="_blank">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://www.instagram.com/kidseducation___/" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>

         <div class="break"></div>

        <div class="box">
            <img src="images/teacher3.png" alt="">
            <h3>zebiba mohammed</h3>
            <p>Mathematics instructor</p>
            <div class="share">
                <a href="https://web.facebook.com/groups/2733795403523028" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://x.com/masakakids" target="_blank">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://www.instagram.com/kidseducation___/" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>

         <div class="break"></div>

         <div class="box">
            <img src="images/teacher4.png" alt="">
            <h3>Hildana Yigezu</h3>
            <p>Music and Drama instructor</p>
            <div class="share">
                <a href="https://web.facebook.com/groups/2733795403523028" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://x.com/masakakids" target="_blank">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://www.instagram.com/kidseducation___/" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>

    </div>

</section>

    <!-- teacher section ends -->

    <!-- activities section starts -->

    <section class="activities" id="activities">

    <h1 class="heading">our <span>activities</span></h1>

    <div class="box-container">

        <a href="https://learnenglishkids.britishcouncil.org/fun-games/games" class="box" target="_blank">
            <img src="images/activities1.png" alt="">
            <h3>games and fun</h3>
        </a>

        <a href="https://www.abc.net.au/abckids/games" class="box" target="_blank">
            <img src="images/activities2.png" alt="">
            <h3>games and fun</h3>
        </a>

        <a href="https://www.abcya.com/" class="box" target="_blank">
            <img src="images/activities3.png" alt="">
            <h3>games and fun</h3>
        </a>

        <a href="https://kids.poki.com/" class="box" target="_blank">
            <img src="images/activities4.png" alt="">
            <h3>games and fun</h3>
        </a>

        <a href="https://www.boomerangtv.co.uk/games" class="box" target="_blank">
            <img src="images/activities5.png" alt="">
            <h3>games and fun</h3>
        </a>

        <a href="https://pbskids.org/games" class="box" target="_blank">
            <img src="images/activities6.png" alt="">
            <h3>games and fun</h3>
        </a>

        <a href="https://www.cartoonnetwork.co.uk/games" class="box" target="_blank">
            <img src="images/activities7.png" alt="">
            <h3>games and fun</h3>
        </a>

        <a href="https://play.google.com/store/apps/details?id=com.rvappstudios.baby.toddler.kids.games.learning.activity&hl=en&pli=1" class="box" target="_blank">
            <img src="images/activities8.png" alt="">
            <h3>games and fun</h3>
        </a>

    </div>

</section>
    <!-- activities section ends -->

    <!-- gallery section starts -->

    <section class="gallery" id="gallery">

        <h1 class="heading">our <span>gallery</span></h1>

        <div class="gallery-container">

            <a href="images/gallery-1.jpg" class="box">
                <img src="images/gallery-1.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

            <a href="images/gallery-2.jpg" class="box">
                <img src="images/gallery-2.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

            <a href="images/gallery-3.jpg" class="box">
                <img src="images/gallery-3.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

            <a href="images/gallery-4.jpg" class="box">
                <img src="images/gallery-4.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

            <a href="images/gallery-5.jpg" class="box">
                <img src="images/gallery-5.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

            <a href="images/gallery-6.jpg" class="box">
                <img src="images/gallery-6.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

             <a href="images/gallery-7.jpg" class="box">
                <img src="images/gallery-7.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

             <a href="images/gallery-8.jpg" class="box">
                <img src="images/gallery-8.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

             <a href="images/gallery-9.jpg" class="box">
                <img src="images/gallery-9.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

             <a href="images/gallery-10.jpg" class="box">
                <img src="images/gallery-10.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

             <a href="images/gallery-11.jpg" class="box">
                <img src="images/gallery-11.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

             <a href="images/gallery-12.jpg" class="box">
                <img src="images/gallery-12.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

             <a href="images/gallery-13.jpg" class="box">
                <img src="images/gallery-13.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

             <a href="images/gallery-14.jpg" class="box">
                <img src="images/gallery-14.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

             <a href="images/gallery-15.jpg" class="box">
                <img src="images/gallery-15.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

             <a href="images/gallery-16.jpg" class="box">
                <img src="images/gallery-16.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

             <a href="images/gallery-17.jpg" class="box">
                <img src="images/gallery-17.jpg" alt="">
                <div class="icon"> <i class="fas fa-plus"></i></div>
            </a>

        </div>

    </section>

    <!-- gallery section ends -->

    <!-- contact section starts -->

    <section class="contact" id="contact">

        <h1 class="heading"> <span>contact</span> us</h1>

        <div class="icons-container">

            <div class="icons">
                <i class="fas fa-clock"></i>
                <h3>opening hours :</h3>
                <p>mon - thurs: 08:00 am to 12:30 pm</p>
                <p>friday: 09:00 am to 12:00 pm</p>
            </div>

            <div class="icons">
                <i class="fas fa-envelope"></i>
                <h3>email</h3>
                <p>yabietsealemu59@gmail.com</p>
                <p>yosialex886@gmail</p>
            </div>

            <div class="icons">
                <i class="fas fa-phone"></i>
                <h3>phone number</h3>
                <p>+251929139877</p>
                <p>+251901913670</p>
            </div>

        </div>

        <div class="row">

            <div class="image">
                <img src="images/contact.gif" alt="">
            </div>

            <form action="">
                <h3>get in touch</h3>
                <div class="inputBox">
                    <input type="text" placeholder="your name">
                    <input type="email" placeholder="your email">
                </div>
                <div class="inputBox">
                    <input type="number" placeholder="your number">
                    <input type="text" placeholder="your subject">
                </div>
                <textarea placeholder="your message" cols="30" rows="10"></textarea>
                <input type="submit" value="send message" class="btn">
            </form>

        </div>

    </section>

    <!-- contact section ends -->

    <!-- footer section starts -->

    <section class="footer">

        <div class="box-container">

            <div class="box">
                <h3> <i class="fas fa-school"></i> Kindergarten </h3>
                <p>for further information contact us</p>
            </div>

            <div class="box">
                <h3>quick links</h3>
                <a href="#"> <i class="fas fa-caret-right"></i> enroll now</a>
                <a href="#"> <i class="fas fa-caret-right"></i> parent portal</a>
                <a href="#"> <i class="fas fa-caret-right"></i> school calendar</a>
                <a href="#"> <i class="fas fa-caret-right"></i> lunch menu</a>
                <a href="#"> <i class="fas fa-caret-right"></i> school supply list</a>
            </div>

            <div class="box">
                <h3>category</h3>
                <a href="#"> <i class="fas fa-caret-right"></i> about us</a>
                <a href="#"> <i class="fas fa-caret-right"></i> academics</a>
                <a href="#"> <i class="fas fa-caret-right"></i> admissions</a>
                <a href="#"> <i class="fas fa-caret-right"></i> news & events</a>
                <a href="#"> <i class="fas fa-caret-right"></i> contact us</a>
            </div>

            <div class="box">
                <h3>extra links</h3>
                <a href="#"> <i class="fas fa-caret-right"></i> privacy policy</a>
                <a href="#"> <i class="fas fa-caret-right"></i> terms of use</a>
                <a href="#"> <i class="fas fa-caret-right"></i> site map</a>
                <a href="#"> <i class="fas fa-caret-right"></i> FAQs</a>
                <a href="#"> <i class="fas fa-caret-right"></i> accessibility statement</a>
            </div>

        </div>

        <div class="credit"> &copy; copyright @ 2023 by yabietse</div>

    </section>

    <!-- footer section ends -->

    <!-- Video Modal -->
    <div id="videoModal" class="modal">
        <div class="modal-content">
            <span class="close-button">×</span>
            <div class="video-container">
                <video id="local-video-player" controls preload="auto" width="800" height="450">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>

    <!-- Text Modal (for Learn More) -->
    <div id="textModal" class="modal">
        <div class="modal-content">
            <span class="close-button" id="closeTextModalBtn">×</span>
            <h2>About Our Kindergarten</h2>
            <p>Welcome to Kindergarten, a nurturing and vibrant learning environment dedicated to fostering the intellectual and creative growth of children from kindergarten up to third grade. Our mission is to provide an engaging educational experience that sparks curiosity and builds a strong foundation for lifelong learning.</p>
            <p>We offer a comprehensive curriculum covering Music, Sports, Drawing, Mathematics, Amharic, English, and Science. Our experienced and passionate teachers utilize fun, interactive, and easily understandable methods to ensure that learning is always exciting and never boring. We believe in making education captivating through engaging videos and interactive quizzes that help track your child's progress and reinforce their knowledge.</p>
            <p>At Kindergarten, we are committed to helping Ethiopia's future generations develop a love for learning. We prioritize creating a supportive atmosphere where children feel encouraged to explore, grow, and thrive together, preparing them not just for higher education but for a bright future.</p>
        </div>
    </div>

    <!-- Full About Us Modal (for Read More) -->
    <div id="fullAboutModal" class="modal">
        <div class="modal-content">
            <span class="close-button" id="closeFullAboutModalBtn">×</span>
            <h2>More About Our Kindergarten</h2>
            <p>Welcome to Kindergarten, your comprehensive learning ground for children from kindergarten up to third grade. We are passionately dedicated to nurturing young minds and fostering a lifelong love for learning through an engaging and interactive curriculum.</p>
            <p>Our core educational offerings include vital subjects such as **Music Lessons**, **Sports Lessons**, **Drawing Lessons**, **Mathematics Lessons**, **Amharic Lessons**, **English Lessons**, and **Science Lessons**. Each subject is brought to life through easily understandable methods, interactive videos, and engaging quizzes, designed to keep children interested and help parents track their progress.</p>
            <p>We pride ourselves on our team of **experienced and dedicated teachers** who are committed to making every learning moment count. They create a supportive and inspiring environment where children feel encouraged to explore and grow.</p>
            <p>Beyond academic subjects, we believe in the importance of **Games and Fun** as a crucial part of development. Our 'Activities' section provides access to a variety of educational and entertaining games, ensuring that learning is never boring and always exciting. Our **Gallery** showcases the joyful experiences and creative achievements of our students, reflecting the vibrant community we build.</p>
            <p>At Kindergarten, we are striving to help the future generation of Ethiopia easily grasp concepts, develop key skills, and build confidence. We are committed to fostering an environment where children can truly explore, grow, and thrive together, preparing them for higher education and a bright, successful future. Join us in making learning an unforgettable adventure!</p>
        </div>
    </div>

    <!-- Manage Account Modal (simple placeholder) -->
    <div id="manageAccountModal" class="modal">
        <div class="modal-content">
            <span class="close-button" id="closeManageAccountModalBtn">×</span>
            <h2>Manage Your Account</h2>
            <p>This is where you can manage your account settings. More features will be added here soon!</p>
            <p>Your current email: <span id="managed-email"></span></p>
            <!-- Add more account management options here later -->
        </div>
    </div>

    <!-- Lightgallery cdn js link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/js/lightgallery.min.js"></script>
    <!-- custom js file link -->
    <script src="script.js"></script>

    <script>
        // Initialize Lightgallery after the DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            if (document.querySelector('.gallery .gallery-container')) {
                lightGallery(document.querySelector('.gallery .gallery-container'));
            }
        });
    </script>

    <!-- PHP variables for JavaScript access -->
    <script>
        const isLoggedIn = <?php echo json_encode($loggedIn); ?>;
        const currentUserEmail = <?php echo json_encode($userEmail); ?>;
        const currentUserId = <?php echo json_encode($userId); ?>;
    </script>

</body>
</html>
