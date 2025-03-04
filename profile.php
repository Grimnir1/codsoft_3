<?php 
    require 'nav.php';

    include 'database.php';

    $sessionid = $_SESSION['user'] ?? null;
    if (!$sessionid) {
        header('Location: ../login.php');
        exit();
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM users WHERE userID = $id";
        $query = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($query);

        
        $sql2 = "SELECT * FROM blogs WHERE createdBy = $id";
        $query2 = mysqli_query($conn, $sql2);
        $blogCount = mysqli_num_rows($query2);
        $blogs = mysqli_fetch_all($query2, MYSQLI_ASSOC);
    }

        $error = '';
    $errors = [
        'firstName' => '',
        'lastName' => '',
        'description' => '',
        'adress' => '',
        'about' => '',
    ];
    $firstName = $user['firstname'];
    $lastName = $user['lastname'];
    $description = $user['description'];
    $adress = $user['adress'];
    $about = $user['about'];


    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateProfileImage'])){
        $target_dir = "uploads/";
        $image = $_FILES['profileImage'];
        $imageName = $image["name"];
        $tempName = $image["tmp_name"];
        $imageSize = $image["size"];
        $imageError = $image["error"];
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowed =['jpg', 'jpeg', 'png', 'svg'];
        if(in_array($imageExt, $allowed)){
            if($imageSize < 10000000){
                $newImageName = uniqid('profile-', true) . '.' . $imageExt;
                $target_image = $target_dir . $newImageName;

                if(move_uploaded_file($tempName, $target_image)) {
                    $imagePath = $target_image;


                    
                    $sql = "UPDATE users SET profileimage = '$imagePath' WHERE userID = $sessionid";
                    $query = mysqli_query($conn, $sql);
                    if($query){
                        header('Location: profile.php?id=' . $sessionid);
                    }else{
                        echo 'Failed to upload image';
                    }

                }else{
                    echo 'Failed to upload image';
                }
            }else{
                echo 'Image size is too large';
            }
        }else{
            echo 'Image format not supported';
        }
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateProfile'])){
        $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
        $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $adress = mysqli_real_escape_string($conn, $_POST['adress']);
        $about = mysqli_real_escape_string($conn, $_POST['about']);



        if (empty($firstName)) {
            $errors['firstName'] = 'First name is required';
        }elseif (empty($lastName)) {
            $errors['lastName'] = 'Last name is required';
        }elseif (empty($description)) {
            $errors['description'] = 'Description is required';
        }elseif (empty($adress)) {
            $errors['adress'] = 'Adress is required';
        }elseif (empty($about)) {
            $errors['about'] = 'About is required';
        }else {
            $sql = "UPDATE users SET firstname = '$firstName', lastname = '$lastName', description = '$description', adress = '$adress', about = '$about' WHERE userID = $id";
            $query = mysqli_query($conn, $sql);
            if ($query) {
                header('Location: profile.php?id=' . $sessionid);
            }else {
                $error = 'Profile update failed';
            }
        }

    }

?>
    <style>
        .profile-header {
            background-color: #f8f9fa;
            padding: 2rem 0;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 0.5rem 1rem rgba(215, 205, 205, 0.15);
        }
        .post-card {
            transition: transform 0.3s;
            margin-bottom: 1.5rem;
        }
        .post-card:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            background-color: white;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .stats-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #0d6efd;
        }
        .stats-card h3 {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
    </style>

    <div class="container my-5">
        <div class="profile-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <div class="position-relative">
                            <img src="../<?php echo $user['profileimage']?>" class="profile-img" alt="Profile Image">
                            <button class="btn btn-sm btn-primary rounded-circle position-absolute" data-bs-toggle="modal" data-bs-target="#exampleModal" style="bottom: -10px; right: 50%; transform: translateX(50%); width: 32px; height: 32px;">
                                <i class="bi bi-camera"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h1 class="display-4"><?php echo $user['lastname'] . ' ' . $user['firstname']?></h1>
                        <p class="lead text-muted"><?php echo $user['description'];?></p>
                        <p><i class="fas fa-map-marker-alt me-2"></i><?php echo $user['adress'];?></p>
                        <p><i class="fas fa-calendar-alt me-2"></i>Member since <?php echo date('F j, Y', strtotime($user['dateJoined'])); ?></p>
                        <div class="d-flex gap-2 mt-3">
                            <?php if ($id == $sessionid) {?>
                                <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                                Edit Profile
                                </button>
                            <?php }else{?>
                                <a href="#" class="btn btn-outline-primary"><i class="fas fa-envelope me-2"></i>Message</a>
                                
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="shareDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="shareDropdown">
                                        <li><a class="dropdown-item" href="#"><i class="fab fa-facebook me-2"></i>Facebook</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fab fa-twitter me-2"></i>Twitter</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fab fa-linkedin me-2"></i>LinkedIn</a></li>
                                    </ul>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 order-lg-1 order-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>About Me</h5>
                    </div>
                    <div class="card-body">
                        <p><?php echo $user['about'];?></p>
                    </div>
                </div>

                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-link me-2"></i>Social Links</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-outline-primary"><i class="fab fa-twitter me-2"></i>Twitter</a>
                            <a href="#" class="btn btn-outline-primary"><i class="fab fa-github me-2"></i>GitHub</a>
                            <a href="#" class="btn btn-outline-primary"><i class="fab fa-linkedin me-2"></i>LinkedIn</a>
                            <a href="#" class="btn btn-outline-primary"><i class="fab fa-instagram me-2"></i>Instagram</a>
                        </div>
                    </div>
                </div>

                
            </div>

            <div class="col-lg-8 order-lg-2 order-1">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stats-card">
                            <i class="fas fa-file-alt"></i>
                            <h3><?php echo $blogCount;?></h3>
                            <p class="text-muted mb-0">Posts</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <i class="fas fa-comment"></i>
                            <h3>-</h3>
                            <p class="text-muted mb-0">Comments</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <i class="fas fa-heart"></i>
                            <h3>-</h3>
                            <p class="text-muted mb-0">Likes</p>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-newspaper me-2"></i>Latest Posts</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($blogs as $blog) { ?>
                                <div class="blog-card col-md-4 mt-5 align-content-center">
                                    <div class="card m-auto"  style="min-height: 30dvh;">
                                        <div class="card-body">
                                            <img src="../<?php echo $blog['imagePath']; ?>" style="width: 100%; height: 300px; object-fit: cover;" class="card-img-top " alt="...">
                                            <h5 class="p-2" style="overflow: hidden; white-space:nowrap; text-overflow: ellipsis; "class="card-title p-2"><?php echo $blog['title']; ?></h5>
                                            <small class="fw-medium p-2 mb-4"><?php echo date('F j, Y', strtotime($blog['date_created'])); ?></small>
                                            <a href="../singleBlog.php/?id=<?php echo $blog['blogID']?>" class="d-block p-2 card-link">Read more</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

               
            </div>
        </div>
    </div>


    <!-- Modal -->
    <!-- Profile image -->

    <form action="" method="post" enctype="multipart/form-data">

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Profile Photo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        <input type="file" name="profileImage" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="updateProfileImage" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>
                            <!-- edit Profile -->
    <form action="" method="post" enctype="multipart/form-data">
        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                    <input 
                            type="Text" 
                            placeholder="Firstname" 
                            name="firstName" 
                            class="form-control mt-3" 
                            value="<?php echo htmlspecialchars($firstName)?>"
                    >
                    <small class="text-danger"><?php echo $errors['firstName'] ?></small>
                    <input 
                        type="text" 
                        placeholder="Lastname" 
                        name="lastName" 
                        class="form-control mt-3" 
                        value="<?php echo htmlspecialchars($lastName)?>"
                    >
                    <small class="text-danger"><?php echo $errors['lastName'] ?></small>
                    <input 
                        type="text" 
                        placeholder="Description" 
                        name="description"
                        class="form-control mt-3" 
                        value="<?php echo htmlspecialchars($description)?>"
                    >
                    <small class="text-danger"><?php echo $errors['description'] ?></small>
                    <input 
                        type="text" 
                        placeholder="Adress" 
                        name="adress"
                        class="form-control mt-3" 
                        value="<?php echo htmlspecialchars($adress)?>"
                    >
                    <small class="text-danger"><?php echo $errors['adress'] ?></small>
                    <input 
                        type="content" 
                        placeholder="About you" 
                        name="about"
                        class="form-control mt-3" 
                        value="<?php echo htmlspecialchars($about)?>"
                    >
                    <small class="text-danger"><?php echo $errors['about'] ?></small> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="updateProfile" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </div>
        </div>
    </form>
    <?php 
    require 'footer.php';

?>