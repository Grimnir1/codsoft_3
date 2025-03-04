<?php
    require 'nav.php';
    require 'database.php';

    $search = $_POST['search'] ?? null;


    $search_term = '%'. $search . '%';
    $sql = "SELECT * FROM blogs WHERE title LIKE '$search_term' OR content LIKE '$search_term'";
    $query = mysqli_query($conn, $sql);
    $blogPosts = mysqli_fetch_all($query, MYSQLI_ASSOC);  

    
    function getblogAuthor($createdBy){
        require 'database.php';
        $sql = "SELECT * FROM users WHERE userID = '$createdBy'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        return $result['firstname'] . " " . $result['lastname'];
    }



// Option 2: Using direct mysqli_query (less secure, not recommended)
// $search_term = '%subtle%';

// $al = "SELECT * FROM blogs WHERE title LIKE '$search_term' OR content LIKE '$search_term'";
// $alquery = mysqli_query($conn, $al);
// $search = mysqli_fetch_assoc($alquery);

// echo '<pre>';
// print_r($search);
// echo '</pre>';
 
 ?>

<section class="container-fluid text-light d-flex align-items-center justify-content-center" style="min-height: 70dvh; background-image: url('laptop-notebook-writing-vintage-tone-from-view-wooden-background_482257-35211.jpg'); background-size: cover; background-position: center;">
    <div class="glass-overlay d-flex rounded container align-items-center justify-content-center" style="min-height: 50dvh;">
        <div class="container text-center">
            <h3 class="p-3">The Muse: Your Creative Sanctuary</h3>
            <p class="p-3">The Muse is a vibrant blog website designed to inspire and empower creators, thinkers, and dreamers. Featuring thought-provoking articles, practical tips, and personal stories, it’s a space where ideas come to life. Whether you’re an artist, writer, or simply someone seeking inspiration, The Muse is your go-to destination for creativity and growth. Explore, connect, and let your imagination soar! ✨</p>
        </div>
    </div>
</section>

<form action="" method="post" class="container mt-5">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Search for blogs..." name="search">
        <button class="btn btn-secondary" type="submit">Search</button>
    </div>

</form>

<section class="container" style="min-height: 40dvh;">
    <div class="row">
        <?php foreach ($blogPosts as $blog) { ?>
            <div class="blog-card col-md-4 mt-5 align-content-center">
                <div class="card m-auto"  style="min-height: 30dvh;">
                    <div class="card-body">
                        <img src="<?php echo $blog['imagePath']; ?>" style="width: 100%; height: 300px; object-fit: cover;" class="card-img-top " alt="...">
                        <h5 class="p-2" style="overflow: hidden; white-space:nowrap; text-overflow: ellipsis;" class="card-title p-2"><?php echo $blog['title']; ?></h5>
                        <h6 class="card-subtitle p-2 mb-2 text-body-secondary">By <?php echo getblogAuthor($blog['createdBy']) ?></h6>
                        <p style="overflow: hidden; white-space:nowrap; text-overflow: ellipsis;" class="card-text p-2 mb-1"><?php echo $blog['content'] ?></p>
                        <small class="fw-medium p-2 mb-4"><?php echo date('F j, Y', strtotime($blog['date_created'])); ?></small>
                        <a href="singleBlog.php/?id=<?php echo $blog['blogID']?>" class="d-block p-2 card-link">Read more</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

    
    <?php require 'footer.php'?>

    