<?php 
include('db.php');
   session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Register</title>
</head>
<body>
      <div class="container">
        <div class="box form-box">

        <?php 
        
         if(isset($_POST['submit'])){
            // Sanitize input
            $username = mysqli_real_escape_string($con, trim($_POST['username']));
            $email = mysqli_real_escape_string($con, trim($_POST['email']));
            $subject = mysqli_real_escape_string($con, trim($_POST['subject']));
            $password = mysqli_real_escape_string($con, trim($_POST['password']));

         //verifying the unique email using prepared statement
         $verify_stmt = mysqli_prepare($con, "SELECT Email FROM users WHERE Email=?");
         mysqli_stmt_bind_param($verify_stmt, "s", $email);
         mysqli_stmt_execute($verify_stmt);
         $verify_result = mysqli_stmt_get_result($verify_stmt);
         
         if (mysqli_num_rows($verify_result) != 0) {
             echo "<div class='message'>
                       <p>This email is used, Try another one please!</p>
                   </div> <br>";
             echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
             mysqli_stmt_close($verify_stmt);
         } else {
             mysqli_stmt_close($verify_stmt);
             
             // Use prepared statement for insert
             $insert_stmt = mysqli_prepare($con, "INSERT INTO users (Username, Email, subject, Password) VALUES (?, ?, ?, ?)");
             mysqli_stmt_bind_param($insert_stmt, "ssss", $username, $email, $subject, $password);
             
             if (mysqli_stmt_execute($insert_stmt)) {
                 mysqli_stmt_close($insert_stmt);
                 echo "<div class='message'>
                           <p>Registration successful!</p>
                       </div> <br>";
                 echo "<a href='index.php'><button class='btn'>Login Now</button>";
             } else {
                 die('Error inserting record: ' . mysqli_error($con));
             }
         }
         

         }else{
         
        ?>

            <header>Sign Up</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="subjectS">subject</label>
                    <input type="text" name="subject" id="subject" >
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                    
                    <input type="submit" class="btn" name="submit" value="Register" required>
                </div>
                <div class="links">
                    Already a member? <a href="index.php">Sign In</a>
                </div>
            </form>
        </div>
        <?php } ?>
      </div>
</body>
</html>







