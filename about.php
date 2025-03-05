<?php
session_start();
error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Result Archive System</title>
    <link rel="stylesheet" href="./boostrap/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <?php include('./favicon.php'); ?>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Kalpurush';
            src: url('https://cdn.jsdelivr.net/npm/kalpurush@1.0.0/fonts/Kalpurush.woff2') format('woff2');
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: url('https://via.placeholder.com/1920x1080.png?text=JSTU+Campus') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        body::before {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .navbar {
            background: linear-gradient(90deg, #0288d1, #1976d2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8em;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            color: #e0f7fa !important;
            font-weight: 600;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .nav-link:hover {
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        .cover-section {
            position: relative;
            height: 500px;
            background: url('./resources/aboutUs/about_us.jpg') no-repeat center center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
        }

        .cover-section::before {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        .cover-content {
            position: relative;
            z-index: 2;
            color: #ffffff;
        }

        .cover-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3em;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 10px;
        }

        .cover-content p {
            font-size: 1.3em;
            color: #e0f7fa;
        }

        .container {
            padding: 0px 0;
        }

        h1,
        h2 {
            font-family: 'Playfair Display', serif;
            color: #1976d2;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2.8em;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 2.2em;
            margin-bottom: 30px;
        }

        p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #37474f;
        }

        .bangla-text {
            font-family: 'Kalpurush', sans-serif;
            font-size: 1.2em;
            color: #0288d1;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 20px auto;
            object-fit: cover;
            border: 3px solid #0288d1;
            transition: transform 0.3s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.6em;
            color: #0288d1;
        }

        .card-text {
            font-size: 1em;
            color: #37474f;
        }

        .motivation {
            background: linear-gradient(135deg, #fff3e0, #ffebee);
            border-radius: 15px;
            padding: 40px;
            margin: 40px 0;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .motivation h2 {
            color: #d81b60;
        }

        .motivation p {
            font-style: italic;
            color: #d81b60;
        }

        .our-story {
            background: linear-gradient(135deg, #e8f5e9, #e0f7fa);
            border-radius: 20px;
            padding: 50px;
            margin: 40px 0;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border: 2px solid #0288d1;
            position: relative;
            overflow: hidden;
        }

        .our-story::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(2, 136, 209, 0.1) 0%, transparent 70%);
            animation: rotateGlow 15s infinite linear;
            z-index: 0;
        }

        .our-story h2 {
            color: #0288d1;
            position: relative;
            font-weight: 600;
            z-index: 1;
        }

        .our-story p {
            color: #263238;
            font-style: normal;
            position: relative;
            z-index: 1;
        }

        .our-story .typed-element {
            font-size: 1em;
            color: #0288d1;
            /* font-weight: 600; */
            position: relative;
            z-index: 1;
        }

        @keyframes rotateGlow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        footer {
            background: linear-gradient(90deg, #0288d1, #1976d2);
            color: #ffffff;
            padding: 20px 0;
            font-family: 'Open Sans', sans-serif;
            transition: background 0.3s ease;
        }

        @media (max-width: 768px) {
            .cover-section {
                height: 300px;
            }

            .cover-content h1 {
                font-size: 2em;
            }

            .cover-content p {
                font-size: 1em;
            }

            .container {
                padding: 40px 15px;
            }

            h1 {
                font-size: 2.2em;
            }

            h2 {
                font-size: 1.8em;
            }

            .card-img-top {
                width: 120px;
                height: 120px;
            }

            .motivation {
                padding: 20px;
            }

            .our-story {
                padding: 30px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark" data-aos="fade-down" data-aos-duration="1000">
        <div class="container">
            <a class="navbar-brand" href="./index.php">Result Archive System - JSTU</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="./about.php">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="cover-section" data-aos="fade-in" data-aos-duration="1000">
        <div class="cover-content" data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="200">
            <h1>About Us</h1>
            <p>Discover the Team Behind JSTU’s Result Archive System</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row mb-5 text-center" data-aos="fade-up" data-aos-duration="1000">
            <div class="col">
                <h1>About the Result Archive System</h1>
                <p>The Result Archive System is designed to help students, coordinators, and administrators manage academic results efficiently. It ensures secure storage, easy access, and streamlined updates for academic records, empowering the JSTU community with modern technology.</p>
                <p class="bangla-text">ফলাফল সংরক্ষণ ব্যবস্থা ছাত্র-ছাত্রী, সমন্বয়কারী এবং প্রশাসকদের জন্য একটি কার্যকর সমাধান। এটি নিরাপদ সংরক্ষণ, সহজে প্রবেশাধিকার এবং শিক্ষাগত রেকর্ডের আপডেট নিশ্চিত করে।</p>
            </div>
        </div>

        <div class="motivation" data-aos="fade-up" data-aos-duration="1000">
            <h2 data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="200">Your Work Matters</h2>
            <p data-aos="fade-right" data-aos-duration="1000" data-aos-delay="400">"No one in the brief history of computing has ever written a piece of perfect software. It's unlikely that you'll be the first." - Andy Hunt</p>
        </div>

        <div class="our-story" data-aos="fade-up" data-aos-duration="1000">
            <h2>Our Story</h2>
            <p class="typed-element bangla-text" id="typed-text">অরূপ, আদর আর সাইফ—আমাদের তিন বন্ধু। আমরা পড়াশোনা করি জামালপুর বিজ্ঞান ও প্রযুক্তি বিশ্ববিদ্যালয়ে। একই সেশনে পড়ি, কম্পিউটার সায়েন্স অ্যান্ড ইঞ্জিনিয়ারিং (CSE) বিভাগে। চার বছরের এই বিশ্ববিদ্যালয় জীবনে কখন যে বন্ধুত্বটা এত গভীর হয়ে গেছে, তা আমরা নিজেরাও জানি না। আমাদের স্বভাব তিনটি আলাদা, তবুও বন্ধুত্বটা ছিল অদ্ভুত শক্ত। অরূপ ছিল চুপচাপ স্বভাবের, আদর ছিল ধুপধাপ কথা বলে কাউকে পরোয়া করে না, আর মাঝে মাঝে বোকা বোকা কথা বলে আমাদের হাসাতে জানে। সাইফ—সে ছিল একটু চুপচাপ, কিন্তু মনের দিক থেকে সবচেয়ে গভীর।

                বিশ্ববিদ্যালয়ের শেষ সেমিস্টার। চূড়ান্ত পরীক্ষার পর আমরা সবাই আলাদা হয়ে যাবে, এই ভাবনাটা আমাদের মনকে ভারী করে তুলছিল। এক বিকেলে ক্যাম্পাসের পেছনের লেকের ধারে বসে ছিলাম। আকাশে সূর্যটা তখন কমলা রঙে সেজেছে, চারপাশে শুধু পাখির ডাক আর হালকা হাওয়া।

                "ইয়ার, এভাবেই শেষ হয়ে যাবে নাকি সব?" হঠাৎ আদর বলে উঠল। তার স্বরে একটু কষ্ট মেশানো।

                অরূপ মৃদু হেসে বলল, "শেষ কোথায়? এখনো তো শুরুই হলো না ঠিকমতো! জব, ইন্টার্নশিপ—আরও কত কিছু বাকি!"

                সাইফ কিছু না বলে লেকের পানিতে পাথর ছুড়তে লাগল। তার চোখে যেন একটা অনিশ্চয়তা খেলা করছিল। বিদেশে মাস্টার্স করার স্বপ্নটা তাকে ভেতরে ভেতরে টানছে, কিন্তু আমাদের ছেড়ে যাওয়ার ভয়টাও কম নয়।

                "জানিস, আমরা হয়তো আলাদা হয়ে যাব, কিন্তু এই স্মৃতিগুলো আমাদের সঙ্গেই থাকবে," সাইফ শেষমেশ বলল।

                আমরা তিনজন কিছুক্ষণ চুপচাপ বসে রইলাম। দূরে লাইব্রেরি বিল্ডিংয়ের ঘড়িটা ছয়টা বাজিয়ে দিল। চারপাশে ক্যাম্পাসের কোলাহল, টিএসসির চায়ের দোকান থেকে ভেসে আসা গান, আর লেকের জলের মৃদু ঢেউ—সব মিলিয়ে যেন বিদায়ের সুর।

                বিশ্ববিদ্যালয়ের জীবনের কিছু স্মৃতি আমাদের মনের মধ্যে চিরকাল থাকবে। যেমন, সাইফ স্যারের বিয়েতে গিয়েছিল, সেখানে একসাথে ২৬ পিস খাসির গোসত খেয়েছিল। সেটি ছিল তার জন্য এক অবিস্মরণীয় অভিজ্ঞতা। আর আদরের কথা বললে, জামালপুরে সে কত শত মেয়ের সাথে প্রেম করলো, কিন্তু যখনই কেউ বিয়ের কথা বলে, সে যেন এক অদ্ভুত যুক্তি দিয়ে চলে আসে—"বিয়ে করে ফেললে তো সুন্দরী মেয়েদের দেখার সুযোগ পাবো না!" এই কথাগুলো এখনও আমাদের মধ্যে হাসির খোরাক। আর একটি স্মৃতি—সেন্টমার্টিনে গভীররাতে আমরা একসাথে চাঁদ ও সমুদ্র দেখেছিলাম। সেই শান্তিপূর্ণ মুহূর্ত আর আমাদের বন্ধুত্বের অনুভূতি আজও আমাদের মনকে উষ্ণ করে রাখে।

                বিদায়ের দিনটা এলো। হলের সামনের মাঠে দাঁড়িয়ে তিনজন শক্ত করে জড়িয়ে ধরলাম একে অপরকে। অরূপের চোখে জল, আদরের ঠোঁটে অদ্ভুত একটা চুপ, আর সাইফের চোখে সেই একই গভীরতা।

                বাস ছাড়ল। প্ল্যাটফর্মে দাঁড়িয়ে অরূপ আর সাইফকে বিদায় জানাতে এসে আদর হঠাৎ চিৎকার করে বলল, "এই শালা, ভুলে যাস না কিন্তু! দেখা হবে আবার!"

                বাসটা দূরে মিলিয়ে গেল, আর আমাদের মনেও থেকে গেল একটাই আশা—দেখা হবে আবার। বন্ধুত্বের সুতোর বাঁধন তো সহজে ছিঁড়ে না।</p>
        </div>

        <div class="row text-center mb-4" data-aos="fade-up" data-aos-duration="1000">
            <div class="col">
                <h2>Meet the Developers</h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-4 text-center mb-4" data-aos="flip-left" data-aos-duration="1000" data-aos-delay="0">
                <div class="card">
                    <img src="./resources/developer_images/arup.png" class="card-img-top" alt="Arup Roy">
                    <div class="card-body">
                        <h5 class="card-title">Arup Roy</h5>
                        <p class="card-text">
                            <strong>Student ID:</strong> 20111104 <br>
                            <strong>Bio:</strong> A passionate backend developer who loves working with PHP and databases. <br>
                            <strong>Interested Field:</strong> Web Development & Database Design.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 text-center mb-4" data-aos="flip-up" data-aos-duration="1000" data-aos-delay="200">
                <div class="card">
                    <img src="./resources/developer_images/saif.jpeg" class="card-img-top" alt="Saif Bashar">
                    <div class="card-body">
                        <h5 class="card-title">Saif Bashar</h5>
                        <p class="card-text">
                            <strong>Student ID:</strong> 20111111 <br>
                            <strong>Bio:</strong> A skilled frontend developer with a passion for creating user-friendly interfaces. <br>
                            <strong>Interested Field:</strong> UI/UX Design & Frontend Development.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 text-center mb-4" data-aos="flip-right" data-aos-duration="1000" data-aos-delay="400">
                <div class="card">
                    <img src="./resources/developer_images/ador.jpg" class="card-img-top" alt="Ashrafur Rahman Ador">
                    <div class="card-body">
                        <h5 class="card-title">Ashrafur Rahman Ador</h5>
                        <p class="card-text">
                            <strong>Student ID:</strong> 20111110 <br>
                            <strong>Bio:</strong> An all-rounder interested in both frontend and backend development. <br>
                            <strong>Interested Field:</strong> Full-Stack Development & Cloud Computing.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include('footer.php')
    ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script src="./boostrap/bootstrap.bundle.min.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 1000
        });
    </script>
</body>

</html>