<?php
session_start(); // Start the session

// Database connection
include 'db_connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle create (add new image)
if (isset($_POST['add_image'])) {
    $image_name = $_POST['image_name'];
    $image_file = $_FILES['image_file']['name'];
    $target_dir = "uploads/"; // Make sure this directory exists
    $target_file = $target_dir . basename($image_file);

    // Check if uploads directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // Create directory if it doesn't exist
    }

    // Upload the image file
    if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO gallery (image_name, image_file) VALUES ('$image_name', '$target_file')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "New image added successfully!";
        } else {
            $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Sorry, there was an error uploading your file. ";
    }
}

// Handle update (edit image details)
if (isset($_POST['update_image'])) {
    $id = $_POST['id'];
    $image_name = $_POST['update_image_name'];
    $image_file = $_FILES['update_image_file']['name'];
    $target_dir = "uploads/";
    $sql = "UPDATE gallery SET image_name='$image_name' WHERE id=$id"; // Default to update name only

    // Check if a new file is uploaded
    if (!empty($image_file)) {
        $target_file = $target_dir . basename($image_file);
        if (move_uploaded_file($_FILES['update_image_file']['tmp_name'], $target_file)) {
            // Update the image with the new file
            $sql = "UPDATE gallery SET image_name='$image_name', image_file='$target_file' WHERE id=$id";
        } else {
            $_SESSION['error'] = "Error uploading new image.";
        }
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Image updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating record: " . $conn->error;
    }
}

// Handle read (fetch all images for pagination)
$items_per_page = 5; // Number of items per page
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

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM gallery WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Image deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting record: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/logo/title-icon.jpg">
    <title>Gallery</title>
    <!-- Include Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style/style.css">
</head>

<body>

    <div class="container">
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']); // Clear the message after displaying it
        }
        ?>

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
                    <h1 class="display-4">Create <span class="text-primary">Gallery.</span></h1>

                    <!-- Add new image form -->
                    <form action="gallery.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="image_name">Image Name:</label>
                            <input type="text" name="image_name" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="image_file">Image File:</label>
                            <input type="file" name="image_file" required class="form-control">
                        </div>
                        <button type="submit" name="add_image" class="btn btn-primary">Add Image</button>
                    </form>

                    <!-- Display gallery images -->
                    <h3 class="mt-4">Gallery Items</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image Name</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $gallery_items->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['image_name']); ?></td>
                                    <td><img src="<?php echo htmlspecialchars($row['image_file']); ?>" width="100" alt="">
                                    </td>
                                    <td>
                                        <!-- Update Button -->
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#updateModal<?php echo $row['id']; ?>">
                                            Update
                                        </button>
                                        <!-- Delete Button -->
                                        <a href="gallery.php?delete=<?php echo $row['id']; ?>"
                                            class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>

                                <!-- Update Modal -->
                                <div class="modal fade" id="updateModal<?php echo $row['id']; ?>" tabindex="-1"
                                    aria-labelledby="updateModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateModalLabel<?php echo $row['id']; ?>">
                                                    Update Image</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display current image -->
                                                <div class="mb-3 text-center">
                                                    <img src="<?php echo htmlspecialchars($row['image_file']); ?>"
                                                        alt="Current Image" class="img-fluid"
                                                        style="max-height: 100px; max-width: 100%;">
                                                </div>
                                                <form action="gallery.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <div class="form-group">
                                                        <label for="update_image_name">Image Name:</label>
                                                        <input type="text" name="update_image_name" required
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($row['image_name']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="update_image_file">Image File:</label>
                                                        <input type="file" name="update_image_file" class="form-control">
                                                    </div>
                                                    <button type="submit" name="update_image" class="btn btn-primary">Update
                                                        Image</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <!-- Previous Button -->
                            <li class="page-item <?php echo ($current_page == 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="gallery.php?page=<?php echo max(1, $current_page - 1); ?>"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                                <li class="page-item <?php echo ($page === $current_page) ? 'active' : ''; ?>">
                                    <a class="page-link"
                                        href="gallery.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next Button -->
                            <li class="page-item <?php echo ($current_page == $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                    href="gallery.php?page=<?php echo min($total_pages, $current_page + 1); ?>"
                                    aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
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