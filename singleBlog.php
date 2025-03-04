<?php require 'nav.php'; ?>
<?php
require 'database.php';
$sessionid = $_SESSION['user'] ?? null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM blogs WHERE blogID = $id";
    $query = mysqli_query($conn, $sql);
    $blog = mysqli_fetch_assoc($query);

    $sql2 = "SELECT * FROM comments WHERE comment_created_on = $id";
    $query2 = mysqli_query($conn, $sql2);
    $commentCount = mysqli_num_rows($query2);
    $comments = mysqli_fetch_all($query2, MYSQLI_ASSOC);
    




}
 function getblogAuthor($createdBy){
    require 'database.php';
    $sql = "SELECT * FROM users WHERE userID = '$createdBy'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['firstname'] . " " . $result['lastname'];
 }
 function getblogAuthorImage($createdBy){
    require 'database.php';
    $sql = "SELECT * FROM users WHERE userID = '$createdBy'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['profileimage'];
 }
 function getblogAbout($createdBy){
    require 'database.php';
    $sql = "SELECT * FROM users WHERE userID = '$createdBy'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result['about'];
 }
 $commentSubmit = isset($_POST['comment']) ?? null;
if ($commentSubmit) {
    $id = $_GET['id'];
    $comment = mysqli_real_escape_string($conn, $_POST['commentText']);
    
    $sql = "INSERT INTO `comments`(`comments`, `comment_created_by`, `comment_created_on`) VALUES ('$comment', '$sessionid', '$id')";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        header("Location: ../singleBlog.php/?id=" . $blog['blogID']);
    }
}
?>


<div class="container-fluid px-0 mb-5">
    <div class="position-relative">
        <img src="../<?php echo $blog['imagePath']; ?>" class="w-100" style="height: 700px; object-fit: cover; filter: brightness(0.8);" alt="Blog Image">
        <div class="position-absolute bottom-0 start-0 w-100 p-4 p-md-5" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
            <div class="container">
                <span class="badge bg-secondary p-3 mb-2"><?php echo $blog['category']; ?></span>
                <?php if($sessionid == $blog['createdBy']){?>
                    <a href="" class="btn btn-secondary">Edit</a>
                <?php } ?>
                <h1 class="text-white display-4 fw-bold"><?php echo $blog['title']; ?></h1>
                <div class="d-flex align-items-center text-white mt-3">
                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <?php if(getblogAuthorImage($blog['createdBy'])){ ?>
                                <img src="../<?php echo getblogAuthorImage($blog['createdBy']); ?>" class="rounded-circle" style="width: 40px; height: 40px;" alt="">
                            <?php } else{ ?>
                                <i class="bi bi-person-fill fs-2"></i>  
                            <?php } ?>                
                    </div>
                    <div class="small">
                        By <span class="fw-bold"><a href="../profile.php/?id=<?php echo $blog['createdBy']?>" style="text-decoration: none; color: white"><?php echo getblogAuthor($blog['createdBy']); ?></a></span>
                        <span class="mx-2">•</span>
                        <span><?php echo date('F j, Y', strtotime($blog['date_created'])); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container mb-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <article class="mb-5">
                <div class="fs-5 lh-lg mb-5">
                    <?php 
                    echo '<div style="white-space: pre-wrap;">';
                    echo $blog['content']; 
                    echo '</div>';
                    ?>
                </div>
            </article>
            
            <div class="card bg-light mb-5 border-0">
                <div class="card-body p-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                <i class="bi bi-person-fill fs-2"></i>
                            </div>
                        </div>
                        <div class="ms-4">
                            <h5 class="card-title">About the Author</h5>
                            <h6 class="mb-2"><a href="../profile.php/?id=<?php echo $blog['createdBy']?>" style="text-decoration: none; color: black;"><?php echo getblogAuthor($blog['createdBy']); ?></a></h6>
                            <p class="card-text text-muted"><?php echo getblogAbout($blog['createdBy']);?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex align-items-center py-3 border-top border-bottom mb-5">
                <span class="me-3 fw-bold">Share this article:</span>
                <a href="#" class="btn btn-outline-primary btn-sm rounded-circle me-2">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="#" class="btn btn-outline-info btn-sm rounded-circle me-2">
                    <i class="bi bi-twitter"></i>
                </a>
                <a href="#" class="btn btn-outline-primary btn-sm rounded-circle me-2">
                    <i class="bi bi-linkedin"></i>
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle">
                    <i class="bi bi-envelope"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4 pb-2 border-bottom">You Might Also Like</h3>
        </div>
    </div>
    <div class="row">
        <?php
        $relatedSql = "SELECT * FROM blogs WHERE category = '{$blog['category']}' AND blogID != {$blog['blogID']} LIMIT 3";
        $relatedQuery = mysqli_query($conn, $relatedSql);
        
        if (mysqli_num_rows($relatedQuery) > 0) {
            while ($related = mysqli_fetch_assoc($relatedQuery)) {
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="../<?php echo $related['imagePath']; ?>" class="card-img-top" alt="..." style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <span class="badge bg-secondary mb-2"><?php echo $related['category']; ?></span>
                    <h5 class="card-title"><?php echo $related['title']; ?></h5>
                    <p class="card-text text-muted small">By <?php echo getblogAuthor($related['createdBy']); ?></p>
                    <a href="?id=<?php echo $related['blogID']; ?>" class="btn btn-outline-primary stretched-link">Read More</a>
                </div>
            </div>
        </div>
        <?php 
            }
        } else {
        ?>
        <div class="col-12">
            <p class="text-muted">No related articles found.</p>
        </div>
        <?php } ?>
    </div>
</div>





<div class="container py-4">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
        <h5 class="mb-0">Comments</h5>
        <span class="badge bg-secondary" id="comment-count"><?php echo $commentCount;?> comment</span>
      </div>

      <div class="card-body">
        <div class="mb-4">
            <form method="post">
                <div class="mb-3">
                    <textarea class="form-control" name="commentText" rows="3" placeholder="Join the discussion..."></textarea>
                </div>
                <button type="submit" name="comment" class="btn btn-primary">Post Comment</button>
            </form>
          </div>
        </div>

        <div id="container" class=" container py-3">
            <?php foreach ($comments as $comment) { ?>
                <div class="row mb-3">
                    <div class="col-md-8 mx-auto">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0 text-primary"><?php echo htmlspecialchars(getblogAuthor($comment['comment_created_by'])); ?></h5>
                                    <small class="text-muted"><?php echo date('M j, Y', strtotime($comment['date_created'])); ?></small>
                                </div>
                                <p class="card-text"><?php echo htmlspecialchars($comment['comments']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
      </div>
    </div>
  </div>



  <style>
    .comment-avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f0f2f5;
    color: #5b5b5b;
    font-weight: 600;
    border-radius: 50%;
  }
  
  .comment-body {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
  }
  
  .comment-border-primary {
    border-left: 4px solid #0d6efd;
  }
  
  .reply-form {
    display: none;
  }
  
  .reply-form.active {
    display: block;
  }
  
  .action-button {
    font-size: 0.85rem;
  }
  </style>
<?php require 'footer.php'; ?>