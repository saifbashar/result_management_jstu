<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Result Archive System - JSTU</title>
  <link rel="stylesheet" href="./boostrap/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  <!-- AOS Library CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <?php
  include('./favicon.php')

  ?>
  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      background: linear-gradient(135deg, #e0f7fa, #b3e5fc);
      color: #263238;
      overflow-x: hidden;
    }

    /* Navbar Styling */
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

    /* Carousel Styling */
    .carousel-inner img {
      height: 500px;
      object-fit: cover;
      filter: brightness(70%);
      transition: filter 0.5s ease;
    }

    .carousel-item {
      transition: transform 0.6s ease-in-out;
    }

    .carousel-caption {
      background: rgba(0, 0, 0, 0.5);
      padding: 20px;
      border-radius: 10px;
      transition: opacity 0.5s ease;
    }

    .carousel-item.active .carousel-caption {
      opacity: 1;
    }

    .carousel-caption h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2.5em;
      font-weight: 700;
      color: #ffffff;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .carousel-caption p {
      font-size: 1.2em;
      color: #e0f7fa;
    }

    /* University Info Section */
    .university-info {
      background: #ffffff;
      border-radius: 15px;
      padding: 40px;
      margin: 40px 0;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .university-info h2 {
      font-family: 'Playfair Display', serif;
      font-size: 2em;
      color: #1976d2;
      margin-bottom: 20px;
    }

    .university-info p {
      font-size: 1.1em;
      line-height: 1.6;
      color: #37474f;
    }

    .university-info img {
      border-radius: 10px;
      max-width: 100%;
      height: auto;
      transition: transform 0.3s ease;
    }

    .university-info img:hover {
      transform: scale(1.05);
    }

    /* Roles Section */
    #roles {
      padding: 60px 0;
    }

    #roles h2 {
      font-family: 'Playfair Display', serif;
      font-size: 2.5em;
      color: #1976d2;
      margin-bottom: 40px;
      text-align: center;
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
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
      font-size: 1.5em;
      color: #0288d1;
    }

    .btn-primary {
      background: linear-gradient(90deg, #4caf50, #81c784);
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-family: 'Open Sans', sans-serif;
      font-weight: 600;
      transition: background 0.3s ease, transform 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(90deg, #81c784, #4caf50);
      transform: scale(1.05);
    }

    /* Footer Styling */
    footer {
      background: linear-gradient(90deg, #0288d1, #1976d2);
      color: #ffffff;
      padding: 20px 0;
      font-family: 'Open Sans', sans-serif;
      transition: background 0.3s ease;
    }

    @media (max-width: 768px) {
      .carousel-inner img {
        height: 300px;
      }

      .carousel-caption h1 {
        font-size: 1.8em;
      }

      .carousel-caption p {
        font-size: 1em;
      }

      .university-info {
        padding: 20px;
      }

      #roles {
        padding: 40px 0;
      }

      #roles h2 {
        font-size: 2em;
      }

      .card-img-top {
        height: 120px;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
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
            <a class="nav-link" href="./about.php">About</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Carousel -->
  <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="user/superadmin/assets/images/img1.jpg" class="d-block w-100" alt="JSTU Campus 1">
        <div class="carousel-caption d-none d-md-block" data-aos="fade-up" data-aos-duration="1000">
          <h1>Welcome to JSTU Result Archive System</h1>
          <p>Efficiently manage and access academic results</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="user/superadmin/assets/images/img2.jpg" class="d-block w-100" alt="JSTU Campus 2">
        <div class="carousel-caption d-none d-md-block" data-aos="fade-up" data-aos-duration="1000">
          <h1>Explore Academic Excellence</h1>
          <p>Your gateway to streamlined result management</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="user/superadmin/assets/images/img3.jpg" class="d-block w-100" alt="JSTU Campus 3">
        <div class="carousel-caption d-none d-md-block" data-aos="fade-up" data-aos-duration="1000">
          <h1>Empower Your Future</h1>
          <p>Access results anytime, anywhere</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <!-- University Info Section -->
  <section class="university-info" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
      <div class="row">
        <div class="col-md-6" data-aos="fade-right" data-aos-duration="1200">
          <h2>About JSTU</h2>
          <p>Jamalpur Science and Technology University (JSTU), established in 2017, is a premier public university in Bangladesh located in Melandaha Upazila, Jamalpur. Known for its commitment to innovation and academic excellence, JSTU offers diverse programs across faculties like Computer Science, Electrical Engineering, and Fisheries.</p>
          <p><strong>বাংলা তথ্য:</strong> জামালপুর বিজ্ঞান ও প্রযুক্তি বিশ্ববিদ্যালয় (জেএসটিইউ) ২০১৭ সালে প্রতিষ্ঠিত হয়েছিল। এটি বাংলাদেশের একটি শীর্ষস্থানীয় পাবলিক বিশ্ববিদ্যালয়, যা জামালপুরের মেলান্দহ উপজেলায় অবস্থিত। জেএসটিইউ বিজ্ঞান ও প্রযুক্তির উন্নয়নে গুরুত্বপূর্ণ ভূমিকা পালন করে এবং উচ্চশিক্ষার জন্য একটি আধুনিক শিক্ষাপ্রতিষ্ঠান।</p>
        </div>
        <div class="col-md-6" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">
          <img src="./resources/homepageImages/sahid_minar.jpg" alt="JSTU Campus" class="img-fluid">
        </div>
      </div>
    </div>
  </section>

  <!-- Roles Section -->
  <section id="roles" class="py-5">
    <div class="container">
      <h2 data-aos="zoom-in" data-aos-duration="1000">Choose Your Role</h2>
      <div class="row text-center">
        <div class="col-md-4">
          <div class="card shadow-sm" data-aos="flip-left" data-aos-duration="1000" data-aos-delay="0">
            <img src="./resources/loginSection/student.jpg" class="card-img-top" alt="Student">
            <div class="card-body">
              <h5 class="card-title">Student</h5>
              <p class="card-text">View your results and academic details easily.</p>
              <a href="./user/student/student_dashboard.php" class="btn btn-primary">Login as Student</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow-sm" data-aos="flip-up" data-aos-duration="1000" data-aos-delay="200">
            <img src="./resources/loginSection/coordinator.jpg" class="card-img-top" alt="Coordinator">
            <div class="card-body">
              <h5 class="card-title">Coordinator</h5>
              <p class="card-text">Add, modify, and manage results efficiently.</p>
              <a href="user/coordinator/login_as_coordinator.php" class="btn btn-primary">Login as Coordinator</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow-sm" data-aos="flip-right" data-aos-duration="1000" data-aos-delay="400">
            <img src="./resources/loginSection/super_admin.png" class="card-img-top" alt="Super Admin">
            <div class="card-body">
              <h5 class="card-title">Super Admin</h5>
              <p class="card-text">Manage users and system-wide settings.</p>
              <a href="./user/superadmin/login_as_sa.php" class="btn btn-primary">Login as Super Admin</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php
  include('./footer.php')
  ?>
  <!-- AOS Library JS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="./boostrap/bootstrap.bundle.min.js"></script>
  <script>
    // Initialize AOS
    AOS.init();
  </script>
</body>

</html>