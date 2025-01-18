<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Result Archieve System</title>
  <link rel="stylesheet" href="./boostrap/bootstrap.min.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="./index.php">Result Archive System</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="#">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./about.php">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./contact.php">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>


  <header class="bg-primary text-white text-center py-5">
    <div class="container">
      <h1>Welcome to the Result Archive System</h1>
      <p class="lead">Easily manage, view, and access academic results</p>
      <a href="#roles" class="btn btn-light btn-lg mt-3">Get Started</a>
    </div>
  </header>


  <section id="roles" class="py-5">
    <div class="container">
      <div class="row text-center">
        <h2 class="mb-4">Choose Your Role</h2>
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Student</h5>
              <p class="card-text">View your results and academic details easily.</p>
              <a href="#" class="btn btn-primary">Login as Student</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Coordinator</h5>
              <p class="card-text">Add, modify, and manage results efficiently.</p>
              <a href="#" class="btn btn-primary">Login as Coordinator</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Super Admin</h5>
              <p class="card-text">Manage users and system-wide settings.</p>
              <a href="#" class="btn btn-primary">Login as Super Admin</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <footer class="bg-dark text-white text-center py-3">
    <p class="mb-0">&copy; 2025 Result Archive System. All Rights Reserved.</p>
  </footer>


  <script src="./boostrap/bootstrap.bundle.min.js"></script>

</body>

</html>