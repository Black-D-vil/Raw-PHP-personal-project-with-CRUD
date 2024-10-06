<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/logo/title-icon.jpg">
    <title>Create Gallery</title>
    <!-- Reference Local Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style/style.css">
</head>

<body>


    <div class="container">
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']); // Clear the error after displaying it
        }
        ?>

        <!-- Header Section -->
        <header class="container-fluid p-3 bg-secondary text-white d-flex align-items-center"
            style="background-color: #0b3c66 !important;">
            <div class="d-flex align-items-center">
                <div class="logo">
                    <img src="assets/logo/logo.png" alt="Logo" class="img-fluid" width="100">
                </div>
                <div class="ms-3">
                    <h2 class="mb-0">Station Headquarter's</h2>
                </div>
            </div>
            <div class="ms-auto">
                <form id="logoutForm" action="logout.php" method="POST">
                    <button type="submit" class="btn btn-light">Logout</button>
                </form>
            </div>

        </header>


        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar Section -->
                <div class="col-md-3 bg-secondary sidebar p-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="gallery.php">Gallery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">Documents</a>
                        </li>
                    </ul>
                </div>

                <!-- Main Content Section -->
                <div class="col-md-9 p-5 main-content text-center">
                    <h1 class="display-4">ASADUZZAMAN <span class="text-primary">HRIDOY</span></h1>
                    <p class="lead">For Your Every Moment</p>
                    <button class="btn btn-primary">Get Started &rarr;</button>

                    <div class="row mt-5">
                        <?php
                        include 'db_connection.php';

                        // Pagination variables
                        $items_per_page = 6; // Number of items per page
                        $total_items_query = "SELECT COUNT(*) as total FROM gallery";
                        $total_items_result = $conn->query($total_items_query);
                        $total_items = $total_items_result->fetch_assoc()['total'];

                        $total_pages = ceil($total_items / $items_per_page); // Calculate total pages
                        $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Get current page number
                        $current_page = max(1, min($total_pages, $current_page)); // Ensure current page is valid
                        $offset = ($current_page - 1) * $items_per_page; // Calculate offset for SQL query
                        
                        // Fetch gallery items for the current page
                        $gallery_items_query = "SELECT * FROM gallery LIMIT $offset, $items_per_page";
                        $gallery_items = $conn->query($gallery_items_query);

                        if ($gallery_items->num_rows > 0) {
                            while ($row = $gallery_items->fetch_assoc()) {
                                echo '<div class="col-4 mb-3">'; // Added margin bottom for better spacing
                                echo '<img src="' . $row['image_file'] . '" class="img-thumbnail rounded" alt="' . htmlspecialchars($row['image_name']) . '" width="150">';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No images found in the gallery.</p>';
                        }
                        ?>
                    </div>

                    <!-- Pagination Links -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mt-4">
                            <li class="page-item <?php if ($current_page == 1)
                                echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>"
                                    tabindex="-1">Previous</a>
                            </li>
                            <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                                <li class="page-item <?php echo ($page === $current_page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php if ($current_page == $total_pages)
                                echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>

            <!-- Footer Section -->
            <div class="row" style="background-color: black;">
                <div class="col-md-12 text-center py-2 bg-black text-white">
                    <p>Copyright &copy; 2024 Asaduzzaman Hridoy</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Reference Local Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!-- Add your JavaScript for active link management here -->
    <script>
        // Get all nav links
        const navLinks = document.querySelectorAll('.nav-link');

        // Add click event to each link
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                // Remove active class from all links
                navLinks.forEach(nav => nav.classList.remove('active'));
                // Add active class to the clicked link
                this.classList.add('active');
            });
        });
    </script>

</body>

</html>