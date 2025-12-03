<?php 
   session_start();

   include("db.php");
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
    exit();
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
    <title>Change Profile</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="box form-box">
            <?php 
               if(isset($_POST['submit'])){
                $username = mysqli_real_escape_string($con, trim($_POST['username']));
                $email = mysqli_real_escape_string($con, trim($_POST['email']));
                $staff_id = mysqli_real_escape_string($con, trim($_POST['staff_id']));

                $id = intval($_SESSION['id']);

                // Use prepared statement to prevent SQL injection
                // Handle empty staff_id - set to empty string if not provided
                if(empty($staff_id)) {
                    $staff_id = '';
                }
                $stmt = mysqli_prepare($con, "UPDATE users SET Username=?, Email=?, staff_id=? WHERE Id=?");
                if($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $staff_id, $id);
                    if(mysqli_stmt_execute($stmt)){
                        mysqli_stmt_close($stmt);
                        echo "<div class='alert alert-success'>
                        <p>✅ Profile Updated Successfully!</p>
                    </div> <br>";
                      echo "<a href='home.php'><button class='btn btn-primary'>Go Home</button></a>";
                    } else {
                        echo "<div class='alert alert-danger'>
                        <p>❌ Error: " . mysqli_error($con) . "</p>
                    </div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>
                    <p>❌ Error preparing query: " . mysqli_error($con) . "</p>
                </div>";
                }
               }else{

                $id = intval($_SESSION['id']);
                $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE Id=?");
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $query = mysqli_stmt_get_result($stmt);

                if($result = mysqli_fetch_assoc($query)){
                    $res_Uname = htmlspecialchars($result['Username'], ENT_QUOTES, 'UTF-8');
                    $res_Email = htmlspecialchars($result['Email'], ENT_QUOTES, 'UTF-8');
                    $staff_id = htmlspecialchars($result['staff_id'], ENT_QUOTES, 'UTF-8');
                }
                mysqli_stmt_close($stmt);

            ?>
            <header class="mb-3">
                <h2><i class="fas fa-user-circle"></i> My Profile</h2>
                <p class="text-muted small">View and update your account information</p>
            </header>
            
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle"></i> <strong>Logged in as:</strong> <?php echo htmlspecialchars($res_Uname, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            
            <form action="" method="post">
                <div class="field input">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($res_Uname, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="username" required>
                </div>

                <div class="field input">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($res_Email, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="email" required>
                </div>
                
                <div class="field input">
                    <label for="staff_id"><i class="fas fa-id-badge"></i> Staff ID <small class="text-muted">(Optional)</small></label>
                    <input type="text" name="staff_id" id="staff_id" class="form-control" value="<?php echo htmlspecialchars($staff_id, ENT_QUOTES, 'UTF-8'); ?>" maxlength="4" pattern="\d{4}" autocomplete="off" placeholder="Enter 4-digit Staff ID">
                    <small class="text-muted">Leave empty if you are not a staff member</small>
                </div>
                
                <div class="field mt-3">
                    <input type="submit" class="btn btn-primary w-100" name="submit" value="Update Profile">
                </div>
                
            </form>
        </div>
        <?php } ?>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>