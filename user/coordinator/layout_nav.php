<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion" id="sidenavAccordion" style="background: linear-gradient(180deg, #0288d1, #1976d2);">
        <div class="sb-sidenav-menu">
            <div class="nav" style="font-family: 'Playfair Display', serif;">

                <div class="sb-sidenav-menu-heading" style="color: #ffffff; font-size: 1.2em; font-weight: 700; text-transform: uppercase; border-bottom: 1px solid rgba(255, 255, 255, 0.2); padding-bottom: 5px; margin-bottom: 10px;">Core</div>
                <a class="nav-link" href="./coordinator_dashboard.php" style="color: #ffffff; font-size: 1.1em; padding: 12px 20px; transition: background 0.3s ease;">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt" style="color: #81d4fa;"></i></div>
                    Dashboard
                </a>


                <!-- <div class="sb-sidenav-menu-heading" style="color: #ffffff; font-size: 1.2em; font-weight: 700; text-transform: uppercase; border-bottom: 1px solid rgba(255, 255, 255, 0.2); padding-bottom: 5px; margin-bottom: 10px;">User</div> -->

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts" style="color: #ffffff; font-size: 1.1em; padding: 12px 20px; transition: background 0.3s ease;">
                    <div class="sb-nav-link-icon"><i class="fas fa-users" style="color: #81d4fa;"></i></div>
                    Students
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down" style="color: #ffffff;"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav" style="background: rgba(255, 255, 255, 0.1); border-radius: 8px; margin: 0 10px;">
                        <a class="nav-link" href="results_view.php" style="color: #e0f7fa; font-size: 1em; padding: 10px 30px;">Results</a>
                        <a class="nav-link" href="result_add.php" style="color: #e0f7fa; font-size: 1em; padding: 10px 30px;">Add Result</a>
                    </nav>
                </div>
            </div>
        </div>


        <div class="sb-sidenav-footer" style="background: rgba(0, 0, 0, 0.2); color: #ffffff; font-family: 'Playfair Display', serif; padding: 15px 20px;">
            <div class="small" style="color: #e0f7fa;">Logged in as:</div>
            <span style="color: #ff8a80; font-weight: 600;">
                <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Unknown'; ?>
            </span>
        </div>
    </nav>
</div>


<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">