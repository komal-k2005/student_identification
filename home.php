<?php  
include("db.php");
   session_start();
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }
?>
<!DOCTYPE html>
<!-- Coding By CodingNepal - codingnepalweb.com -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="style1.css">
        
    <!-- ===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
     <title>Student Identification</title>
     <style>
        body{
            background-color: white;
           background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            margin: auto;
        width: 60%;
        padding: 300px;
        font-family: 'Avenir', sans-serif;
        color: white;
            

        }
        
        </style>
</head>
<body>

    <nav>
        <div class="nav-bar">
            <i class='bx bx-menu sidebarOpen' ></i>
            <span class="logo navLogo"><a href="#">Student Identification</a></span>
            <div class="menu">
                <div class="logo-toggle">
                    <span class="logo"><a href="#">Student Identification</a></span>
                    <i class='bx bx-x siderbarClose'></i>
                </div>
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Staff</a></li>
                    <li><a href="index1.php">Student</a></li>
                    <li><a href='editprofile.php?Id=$res_id'>Change Profile</a></li>
                    <li><a href="php/logout.php"> <!--<button class="btn">Log Out</button>--> Log Out </a></li>
                </ul>
            </div>
            <div class="nav">
        <div class="logo">
            <p><a href="home.php"></a> </p>
        </div>

        <div class="right-links">

            <?php 
            
            $id = $_SESSION['id'];
            $query = mysqli_query($con,"SELECT*FROM users WHERE Id=$id"); 

            while($result = mysqli_fetch_assoc($query)){
                $res_Uname = $result['Username'];
                $res_Email = $result['Email'];
                $res_subject = $result['subject'];
                $res_id = $result['Id'];
            }
            
            ?>

            

        </div>
    </div>
   
    </nav>
 
    <script>
    const body = document.querySelector("body"),
      nav = document.querySelector("nav"),
      sidebarOpen = document.querySelector(".sidebarOpen"),
      siderbarClose = document.querySelector(".siderbarClose");
      
      //   js code to toggle sidebar
sidebarOpen.addEventListener("click" , () =>{
    nav.classList.add("active");
});
body.addEventListener("click" , e =>{
    let clickedElm = e.target;
    if(!clickedElm.classList.contains("sidebarOpen") && !clickedElm.classList.contains("menu")){
        nav.classList.remove("active");
    }
});
</script>
    
   
<div style="text-align: center;">
    <img src="logo.jpg" width="250px"/>

</div>
<!--<div class="text">   
    <h3>Student Identification</h3>
    </div>-->
        
</body>
</html>

