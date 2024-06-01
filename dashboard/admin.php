<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
    exit;
}
if ( $_SESSION['role'] != 'admin' ) {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="main.css">
    <!-- box icon -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<div class="sidebar" style="background-color: #1b9c9a;">
    <div style="margin-top:50px;" class="logo_details">
        <i class='bx bx-home'></i>
        <div style="margin-left:10px;" class="logo_name">
            Air Panel
        </div>
    </div>
    <ul style="margin-top:50px;">
    <li >
        <a href="dashboard.php" <?php if(basename($_SERVER['PHP_SELF']) == 'dashboard.php') echo 'class="active"'; ?>>
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Dashboard</span>
        </a>
    </li>
    <li style="margin-top:20px;">
        <a href="admin.php" <?php if(basename($_SERVER['PHP_SELF']) == 'admin.php') echo 'class="active"'; ?>>
            <i class='bx bx-user'></i>
            <span class="links_name">Admin</span>
        </a>
    </li>
    <li style="margin-top:20px;">
        <a href="dest.php" <?php if(basename($_SERVER['PHP_SELF']) == 'dest.php') echo 'class="active"'; ?>>
        <i class='bx bxs-city'></i>
          <span class="links_name">Destination</span>
        </a>
    </li>
    <li style="margin-top:20px;">
        <a href="reservation.php" <?php if(basename($_SERVER['PHP_SELF']) == 'reservation.php') echo 'class="active"'; ?>>
            <i class='bx bx-calendar'></i>
            <span class="links_name">Reservation</span>
        </a>
    </li>
</ul>
</div>
<!-- End Sideber -->
<section class="home_section">
    <div class="topbar">
        <div class="toggle">
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <div>
            <a style="text-decoration:none; color:black;" href="Client.php" class="number"> <i style="font-size:28px; font-weight:bold;" class='bx bx-arrow-back'></i></a>
            <a style="text-decoration:none; color:black;" href="../logout.php" class="number"> <i style="margin-left:25px;font-size:28px; font-weight:bold;" class='bx bx-log-out'></i></a>
        </div>
    </div>
    <!-- End Top Bar -->
    <div  class="details" >
        <div class="recent_project">
            <div class="card_header">
                <h2>Admins Information</h2>
            </div>
            <table>
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Name</td>
                    <td>Email</td>
                    <?php if(isset($_GET['edit']) ) { ?>
                        <td>Password</td>
                    <?php } ?>
                 
                        <td></td>
                  
                </tr>
            </thead>
            <tbody>
                <?php
              
                $sql = "SELECT * FROM admin";
                $result = $conn->query($sql);

                // Check if there are any results
                if ($result->num_rows > 0) {
                    // Initialize a counter variable
                    $i = 1;
                    // Fetch each row as an associative array
                    while ($row = $result->fetch_assoc()) {
                        // Output  information in the table rows
                        ?>
                       <tr>
    <td><?php echo $i; ?></td>
    <td>
        <form action="admin.php" method="post" class="update-form">
            <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) { ?>
                <input type="text" name="name" minlength="3" value="<?php echo $row['name']; ?>" required>
            <?php } else { ?>
                <?php echo $row['name']; ?>
            <?php } ?>
    </td>
    <td>
        <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) { ?>
            <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
        <?php } else { ?>
            <?php echo $row['email']; ?>
        <?php } ?>
    </td>
    
    <td><?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) { ?>
                                    <input type="password" minlength="6" name="password"  required>
                                <?php } ?></td>
   
        <td class="btn-action">
            <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) { ?>
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="update_info"><i  style="color:green;font-size: 30px;" class='bx bx-check'></i></button>
            </form>
            <?php } else { ?>
                <a style="text-decoration:none;font-size: 28px;" href="?edit=<?php echo $row['id']; ?>"><i style="color:grey;" class='bx bx-pencil'></i></a>
                <form  method="post">
    <input type="hidden" name="table" value="admin">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    <input type="hidden" name="link" value="admin">
    <button type="submit" name="delete"><i style="color:red;" class="bx bx-trash"></i></button>
</form>
            <?php } ?>
        </td>
</tr>
                        <?php
                        // Increment the counter variable
                        $i++;
                    }
                } else {
                    // If there are no admin in the database
                    echo "<tr><td colspan='7'><p style='text-align:center;'>No admin found</p></td></tr>";

                }
                
                  
                ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="details form-add1"> 
        <div class="recent_project">
        <div class="card_header">
                <h2>Add Admin</h2>
            </div>
            <table class="form-add">
            <tbody>
                <form action="admin.php" method="post">
                    <tr class="form-input">
                        <td>
                            <label for="name">Name :</label>
                        </td>
                        <td>
                            <input type="text" id="name" minlength="3" name="name" required>
                        </td>
                    </tr>
                    <tr class="form-input">
                        <td>
                            <label for="email">Email :</label>
                        </td>
                        <td>
                            <input type="email" id="email" name="email" required>
                        </td>
                    </tr>
                    <tr class="form-input">
                        <td>
                            <label for="password">Password :</label>
                        </td>
                        <td>
                            <input type="password" name="password" minlength="6" required>
                        </td>
                    </tr>
                   
                    <tr class="form-input">
                        <td colspan="2">
                            <button type="submit" name="add">Add</button>
                        </td>
                    </tr>
                </form>
                <?php 
                  if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST["add"])) {
                      
                        // Check if all required fields are provided
                        if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["password"]) ) {
                            // Get form data
                            $name = $_POST["name"];
                            $email = $_POST["email"];
                            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                    
                            // Check if email already exists
                            $email_check_query = "SELECT * FROM admin WHERE email=?";
                            $stmt = $conn->prepare($email_check_query);
                            $stmt->bind_param("s", $email);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {
                                // Email already exists, set error message
                                echo"<script>alert('Email already exists');</script>";
                            } else {
                               
                                        // Prepare and bind the SQL statement to insert new admin
                                        $stmt = $conn->prepare("INSERT INTO admin (name, email, password) VALUES (?, ?, ?)");
                                        if ($stmt) {
                                            // Bind parameters
                                            $stmt->bind_param("sss", $name, $email, $password);
                    
                                            // Execute statement
                                            if ($stmt->execute()) {
                                                echo "<script>window.location.pathname = 'air/dashboard/admin.php';</script>";
                                                exit;
                                            } else {
                                                // Redirect to the page with error message
                                                header("location: admin.php?error=1");
                                                exit;
                                            }
                                        } else {
                                            // Error preparing statement
                                           "Error preparing statement: " . $conn->error;
                                        }
                                    }
                                
                            
                        } else {
                            // Not all required fields provided
                          echo "<script>alert('All fields are required'); </script>";
                        }
                    
            }
            if (isset($_POST["update_info"])) {
               // Get the values from the form
               $id = $_POST["id"];
               $name = $_POST["name"];
               $email = $_POST["email"];
               
               
               // Check if password field is set
               if(isset($_POST["password"])) {
                   $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                   // Update query with password
                   $sql = "UPDATE admin SET name='$name', email='$email', password='$password' WHERE id='$id'";
               } else {
                   // Update query without password
                   $sql = "UPDATE admin SET name='$name', email='$email' WHERE id='$id'";
               }
               if ($conn->query($sql) === TRUE) {
                   // Redirect to the  page
                   echo "<script>window.location.pathname = 'air/dashboard/admin.php';</script>";
                   
                   exit;
               } else {
                   // Redirect to the page with error message
                   header("location: admin.php?error=1");
                   exit;
               }
                                   
           }
           if (isset($_POST["delete"])) {
            $id = $_POST["id"];
            $link = $_POST["link"];
        
            // Check if the admin is trying to delete their own account
            if ($_SESSION['user_id'] == $id) {
                echo "<script>alert('You cannot delete your own account.'); window.location.pathname = 'air/dashboard/$link.php';</script>";
                exit;
            }
        
            $sql = "DELETE FROM admin WHERE id = ?";
        
            if ($stmt = $conn->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("i", $id);
        
                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Redirect to the appropriate page after deletion
                    echo "<script>window.location.pathname = 'air/dashboard/$link.php';</script>";
                    exit;
                } else {
                    // If deletion fails, display an error message
                    echo "Error deleting record: " . $conn->error;
                }
        
                // Close statement
                $stmt->close();
            }
        }
           
        }
                $conn->close();
                ?>
            </tbody>
        </table>
         </div>
    </div>
 
    <div class="add-icon">
        <i class='bx bx-user-plus' id="addAdminIcon"></i>
    </div>

</section>
<script>
    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");
  

    closeBtn.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        // call function
        changeBtn();
    });

    function changeBtn() {
        if(sidebar.classList.contains("open")) {
            closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else {
            closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
    };
   
    let addAdminIcon = document.getElementById("addAdminIcon");
    let addAdminForm = document.querySelector(".form-add1");

    addAdminIcon.addEventListener("click", () => {
        // Toggle the display property of the form
        if (addAdminForm.style.display === "block") {
            addAdminForm.style.display = "none";
        } else {
            addAdminForm.style.display = "block";
        }
    });

</script>
</body>
</html>
