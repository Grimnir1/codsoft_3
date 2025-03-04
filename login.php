<?php require 'nav.php';?>
<?php
    include("database.php");


    $email = $password = '';
    $error ='';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if(empty($email)){
            $error = '
                 <div class="alert text-center alert-danger alert-dismissible fade show" role="alert">
                                    Please enter your mail
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
            ';
            $email = $password = '';
        }elseif(empty($password)){
            $error = '
                 <div class="alert text-center alert-danger alert-dismissible fade show" role="alert">
                                    Please enter your password
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
            ';
            $email = $password = '';
            
        }else{
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $query  = mysqli_query($conn, $sql);

            if(mysqli_num_rows($query) > 0){
                $result = mysqli_fetch_assoc($query);
                $dbPassword = $result['password'];

                if(password_verify($password, $dbPassword)){

                    $_SESSION['user'] = $result['userID'];
                    $_SESSION['dateJoined'] = $result['dateJoined'];
                    $_SESSION['username'] = $result['lastname'] . " " . $result['firstname'];

                    header('Location: index.php');
                    exit();
                }else{
                    $error = '
                    <div class="alert text-center alert-danger alert-dismissible fade show" role="alert">
                                        Invalid Credentials
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                    ';
                    $email = $password = '';

                }
            }else{
                $error = '
                 <div class="alert text-center alert-danger alert-dismissible fade show" role="alert">
                                    Invalid Credentials
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
            ';
            }
        }
    };
    
?>

    <section class="container-fluid align-content-center " style="min-height: 80vh;">
        <div class="container col-md-4 border bg-light p-5 shadow-lg rounded mt-5">
            <?php echo $error?>
            <h2 class="text-center">Login</h2>
            <div>
                <form action="" method="POST">
                    <input 
                        type="character" 
                        placeholder="Email" 
                        name="email" 
                        class="form-control mt-3" 
                        value="<?php echo htmlspecialchars($email)?>"
                    >
                    <input 
                        type="password" 
                        placeholder="Password" 
                        name="password" 
                        class="form-control mt-3" 
                        value="<?php echo htmlspecialchars($password)?>"
                    >
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mt-5">
                            <a href="signup.php" class="mt-3">Don't have an account? Register</a>
                        </div>
                        <div class=" ms-auto align-self-end">
                            <button type="submit" class="btn btn-primary mt-3 ms-auto">Login</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>
</body>
</html>