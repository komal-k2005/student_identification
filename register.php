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
            $username = $_POST['username'];
            $email = $_POST['email'];
            $subject = $_POST['subject'];
            $password = $_POST['password'];

         //verifying the unique email

         $verify_query = mysqli_query($con, "SELECT Email FROM users WHERE Email='$email'");
         if (!$verify_query) {
             die('Error executing query: ' . mysqli_error($con));
         }
         
         if (mysqli_num_rows($verify_query) != 0) {
             echo "<div class='message'>
                       <p>This email is used, Try another one please!</p>
                   </div> <br>";
             echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
         } else {
             $insert_query = mysqli_query($con, "INSERT INTO users (Username, Email, subject, Password) VALUES ('$username','$email','$subject','$password')");
             if (!$insert_query) {
                 die('Error inserting record: ' . mysqli_error($con));
             }
         
             echo "<div class='message'>
                       <p>Registration successful!</p>
                   </div> <br>";
             echo "<a href='index.php'><button class='btn'>Login Now</button>";
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







