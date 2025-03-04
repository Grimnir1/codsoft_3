<?php require 'nav.php'?>
<?php 

if(!$id){
    header('Location: login.php');
    exit();
}
require('database.php');
    $title = '';
    $author = '';
    $category = '';
    $content = '';
    $alert = '';
    $message = '<div class="alert text-center alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Post saved successfully
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    $message2 = '<div class="alert text-center alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> Post was not saved
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';


    $errors = [
        'title' => '',
        'author' => '',
        'category' => '',
        'content' => '',
        'image' => ''
    ];
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $author = mysqli_real_escape_string($conn, $_SESSION['username']);
        $authorID = mysqli_real_escape_string($conn, $_SESSION['user']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $content = mysqli_real_escape_string($conn, $_POST['content']);
        $target_dir = "uploads/";
        $image = $_FILES['image'];
        $imageName = $image["name"];
        $tempName = $image["tmp_name"];
        $imageSize = $image["size"];
        $imageError = $image["error"];
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowed =['jpg', 'jpeg', 'png', 'svg'];

        if (empty($title)) {
            $errors['title'] = 'Title is required';
        }elseif (empty($category)) {
            $errors['category'] = 'Category is required';
        }elseif (empty($content)) {
            $errors['content'] = 'Content is required';
        }else{

            if($imageError === 0){
                if ( $imageSize < 10000000) {
                    $newImageName = time() . "." . $imageExt;
                    // $newImageName = uniqid('', true) . "." . $imageExt;
                    $target_image = $target_dir . $newImageName;
                    if (in_array($imageExt, $allowed)) {
                    

                        if(move_uploaded_file($tempName, $target_image)) {
                            $imagePath = $target_image;

                            $sql = "INSERT INTO `blogs`(`title`, `category`, , `content` , `imagePath`, `createdBy`)
                        VALUES ('$title', '$category', '$content', '$imagePath', '$authorID')
                        ";
                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            $alert = $message;
                        } else {
                            $alert = $message2;
                        }
                        $title = '';
                        $author = '';
                        $category = '';
                        $content = '';

                            

                        } else {
                            $alert = '<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                                Error Uploading Image
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                        }
                }else {
                    $alert = '<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                                File type not supported. Please upload JPG, JPEG, PNG or SVG.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                    $errors['image'] = 'File type not supported. Please upload JPG, JPEG, PNG or SVG.';
                }
                }
                else {
                    $alert = '<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                                File is too large. Maximum size is 10MB.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                    $errors['image'] = 'File is too large. Maximum size is 10MB.';
                }
                
                
            }else if($imageError !== 4){ // Error 4 means no file was uploaded, which might be intentional
                $alert = '<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                            Error uploading image. Please try again.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                $errors['image'] = 'Error uploading image. Please try again.';
            
        };
        
        

            
        }
    };
?>  
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h3 class="text-center mb-0">Create a New Blog Post</h3>
                    </div>
                    <div class="card-body">
                        <?php echo $alert ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Post Title</label>
                                <input 
                                    type="text" 
                                    id="title"
                                    placeholder="Enter a descriptive title" 
                                    name="title" 
                                    class="form-control <?php echo $errors['title'] ? 'is-invalid' : ''; ?>" 
                                    value="<?php echo htmlspecialchars($title)?>"
                                >
                                <?php if($errors['title']): ?>
                                    <div class="invalid-feedback"><?php echo $errors['title']?></div>
                                <?php endif; ?>
                            </div>
                            
                            
                            
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select 
                                    name="category" 
                                    id="category" 
                                    class="form-select <?php echo $errors['category'] ? 'is-invalid' : ''; ?>"
                                >
                                    <option value="">Select Category</option>
                                    <option value="Programming">Programming</option>
                                    <option value="Mindset">Web Development</option>
                                    <option value="Gaming">Gaming</option>
                                    <option value="Education">Education</option>
                                    <option value="Health">Health</option>
                                    <option value="Lifestyle">Lifestyle</option>
                                    <option value="Travel">Travel</option>
                                    <option value="Fashion">Fashion</option>
                                    <option value="Food">Food</option>
                                    <option value="Music">Music</option>
                                    <option value="Sports">Sports</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Business">Business</option>
                                    <option value="Politics">Politics</option>
                                    <option value="Science">Science</option>
                                    <option value="Technology">Technology</option>
                                    <option value="Anime">Anime</option>
                                    <option value="Movies">Movies</option>
                                    <option value="Books">Books</option>

                                </select>
                                <?php if($errors['category']): ?>
                                    <div class="invalid-feedback"><?php echo $errors['category']?></div>
                                <?php endif; ?>
                            </div>
                
                            <div class="mb-3">
                                <label for="content" class="form-label">Post Content</label>
                                <textarea   
                                    name="content"    
                                    id="content"  
                                    placeholder="Write your blog post content here..."    
                                    class="form-control <?php echo $errors['content'] ? 'is-invalid' : ''; ?>"
                                    rows="6"
                                ><?php echo htmlspecialchars($content)?></textarea>
                                <?php if($errors['content']): ?>
                                    <div class="invalid-feedback"><?php echo $errors['content']?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Featured Image</label>
                                <input 
                                    type="file" 
                                    name="image" 
                                    id="image"
                                    class="form-control <?php echo $errors['image'] ? 'is-invalid' : ''; ?>" 
                                >
                                <div class="form-text">Supported formats: JPG, JPEG, PNG, SVG. Max size: 10MB.</div>
                                <?php if($errors['image']): ?>
                                    <div class="invalid-feedback"><?php echo $errors['image']?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-secondary">Publish Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // window.location.href ='https://awafim.tv'
    </script>
<?php require 'footer.php'?>