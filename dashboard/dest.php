<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
    exit;
}
if ($_SESSION['role'] != 'admin') {
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
    <title>Destination</title>
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
<!-- End Sidebar -->
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
    <div class="details">
        <div class="recent_project">
            <div class="card_header">
                <h2>Destinations Information</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Image</td>
                        <td>Description</td>
                        <td>Prix</td>
                        <td>Stars</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM destinations";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $i = 1;
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>
                                <form action="dest.php" method="post" enctype="multipart/form-data" class="update-form">
                                    <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) { ?>
                                        <input type="text" name="name" minlength="3" value="<?php echo $row['name']; ?>" required>
                                    <?php } else { ?>
                                        <?php echo $row['name']; ?>
                                    <?php } ?>
                            </td>
                            <td>
                                <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) { ?>
                                    <input type="text" name="img" required>
                                <?php } else { ?>
                                    <img src="<?php echo $row['img']; ?>" alt="Image" style="width:50px;height:50px;">
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) { ?>
                                    <input type="text" name="description" value="<?php echo $row['description']; ?>" required>
                                <?php } else { ?>
                                    <?php echo $row['description']; ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) { ?>
                                    <input type="number" step="0.01" name="prix" min='0' value="<?php echo $row['prix']; ?>" required>
                                <?php } else { ?>
                                    <?php echo $row['prix'].'$'; ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) { ?>
                                    <select name="stars" required>
                                        <option value="0" <?php if($row['stars'] == 0) echo 'selected'; ?>>0</option>
                                        <option value="1" <?php if($row['stars'] == 1) echo 'selected'; ?>>1</option>
                                        <option value="2" <?php if($row['stars'] == 2) echo 'selected'; ?>>2</option>
                                        <option value="3" <?php if($row['stars'] == 3) echo 'selected'; ?>>3</option>
                                        <option value="4" <?php if($row['stars'] == 4) echo 'selected'; ?>>4</option>
                                        <option value="5" <?php if($row['stars'] == 5) echo 'selected'; ?>>5</option>
                                    </select>
                                <?php } else { ?>
                                    <?php for ($j = 0; $j < $row['stars']; $j++) { ?>
                                        <i class='bx bxs-star' style='color:#ffbb00'></i>
                                    <?php } ?>
                                    <?php for ($j = $row['stars']; $j < 5; $j++) { ?>
                                        <i class='bx bx-star' style='color:#ffbb00'></i>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                            <td class="btn-action">
                                <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) { ?>
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="update_info"><i style="color:green;font-size: 30px;" class='bx bx-check'></i></button>
                                </form>
                                <?php } else { ?>
                                    <a style="text-decoration:none;font-size: 28px;" href="?edit=<?php echo $row['id']; ?>"><i style="color:grey;" class='bx bx-pencil'></i></a>
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete"><i style="color:red;" class="bx bx-trash"></i></button>
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='7'><p style='text-align:center;'>No destinations found</p></td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="details form-add1">
        <div class="recent_project">
            <div class="card_header">
                <h2>Add Destination</h2>
            </div>
            <table class="form-add">
                <tbody>
                    <form action="dest.php" method="post" enctype="multipart/form-data">
                        <tr class="form-input">
                            <td><label for="name">Name :</label></td>
                            <td><input type="text" id="name" minlength="3" name="name" required></td>
                        </tr>
                        <tr class="form-input">
                            <td><label for="img">Image :</label></td>
                            <td><input type="text" id="img" name="img" required></td>
                        </tr>
                        <tr class="form-input">
                            <td><label for="description">Description :</label></td>
                            <td><input type="text" id="description" name="description" required></td>
                        </tr>
                        <tr class="form-input">
                            <td><label for="prix">Prix :</label></td>
                            <td><input type="number" step="0.01" id="prix" min='0' name="prix" required></td>
                        </tr>
                        <tr class="form-input">
                            <td><label for="stars">Stars :</label></td>
                            <td >
                                <select style="margin-left:-20px;" id="stars" name="stars" required>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="form-input">
                            <td colspan="2"><button type="submit" name="add">Add</button></td>
                        </tr>
                    </form>
                    <?php 
                   if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST["add"])) {
                        $name = $_POST["name"];
                        $description = $_POST["description"];
                        $prix = $_POST["prix"];
                        $stars = $_POST["stars"];
                        $img = $_POST["img"];
                      
                
                        $stmt = $conn->prepare("INSERT INTO destinations (name, img, description, prix, stars) VALUES (?, ?, ?, ?, ?)");
                        if ($stmt) {
                            $stmt->bind_param("ssssd", $name, $img, $description, $prix, $stars);
                            if ($stmt->execute()) {
                                echo "<script>window.location.pathname = 'air/dashboard/dest.php';</script>";
                                exit;
                            } else {
                                echo "<script>alert('Error: Could not execute query.');</script>";
                            }
                        } else {
                            echo "<script>alert('Error: Could not prepare statement.');</script>";
                        }
                    } 
                
                    if (isset($_POST["update_info"])) {
                        $id = $_POST["id"];
                        $name = $_POST["name"];
                        $description = $_POST["description"];
                        $prix = $_POST["prix"];
                        $stars = $_POST["stars"];
                                $img = $_POST["img"];
                                $sql = "UPDATE destinations SET name='$name', img='$img', description='$description', prix='$prix', stars='$stars' WHERE id='$id'";
                            
                        // Execute the SQL query
                        if ($conn->query($sql) === TRUE) {
                            echo "<script>window.location.pathname = 'air/dashboard/dest.php';</script>";
                            exit;
                        } else {
                            echo "<script>alert('Error: Could not update.');</script>";
                        }
                    }
                    

                        if (isset($_POST["delete"])) {
                            $id = $_POST["id"];
                            $sql = "DELETE FROM destinations WHERE id = ?";
                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param("i", $id);
                                if ($stmt->execute()) {
                                    echo "<script>window.location.pathname = 'air/dashboard/dest.php';</script>";
                                    exit;
                                } else {
                                    echo "Error deleting record: " . $conn->error;
                                }
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
        changeBtn();
    });

    function changeBtn() {
        if (sidebar.classList.contains("open")) {
            closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else {
            closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
    }

    let addAdminIcon = document.getElementById("addAdminIcon");
    let addAdminForm = document.querySelector(".form-add1");

    addAdminIcon.addEventListener("click", () => {
        if (addAdminForm.style.display === "block") {
            addAdminForm.style.display = "none";
        } else {
            addAdminForm.style.display = "block";
        }
    });
</script>
</body>
</html>
