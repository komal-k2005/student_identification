<?php
      session_start();
      // Check if this is staff logout
      if(isset($_GET['staff']) && $_GET['staff'] == '1') {
          // Only destroy staff session variables
          unset($_SESSION['staff_authenticated']);
          unset($_SESSION['staff_id']);
          unset($_SESSION['staff_name']);
          unset($_SESSION['staff_email']);
          header("Location: ../index.php");
      } else {
          // Regular logout - destroy all sessions
          session_destroy();
          header("Location: ../index.php");
      }
?>