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

// Function to get the count from a table
function getCount($conn, $tableName, $columnAlias) {
    $sql = "SELECT COUNT(*) AS $columnAlias FROM $tableName";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row[$columnAlias];
    }
    return 0;
}

$totalClient = getCount($conn, 'client', 'total_Client');
$totalAdmin = getCount($conn, 'admin', 'total_admin');
$totalDest = getCount($conn, 'destinations', 'total_dest');
$totalReservation = getCount($conn, 'reservation', 'total_reservation');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
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
                <h2>Reservations Information</h2>
            </div>
            <table>
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Client</td>
                    <td>Destination</td>
                    <td>Date</td>
                    <td>Status</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql_dest = "SELECT id, name FROM destinations;";
            $result_dest = $conn->query($sql_dest);
            $dest = array();
            if ($result_dest->num_rows > 0) {
                while ($row_dest = $result_dest->fetch_assoc()) {
                    $dest[$row_dest['id']] = $row_dest['name'];
                }
            }

            $sql_client = "SELECT id, name FROM client;";
            $result_client = $conn->query($sql_client);
            $client = array();
            if ($result_client->num_rows > 0) {
                while ($row_client = $result_client->fetch_assoc()) {
                    $client[$row_client['id']] = $row_client['name'];
                }
            }

            $query_select_res = "SELECT * FROM reservation;";
            $result_select_res = $conn->query($query_select_res);

            if ($result_select_res->num_rows > 0) {
                $i = 1;
                while ($row = $result_select_res->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $i; ?></td> 
                <td><?php echo isset($client[$row['client_id']]) ? $client[$row['client_id']] : "Not Assigned"; ?></td>
                <td><?php echo isset($dest[$row['destination_id']]) ? $dest[$row['destination_id']] : "Unknown"; ?></td>
                <td><?php echo $row['reservation_date']; ?></td>
                <td>
                    <?php
                    if ($row['status'] == 0) {
                        echo "<p style='color:#FE9705;'>Pending</p>";
                    } else {
                        echo "<p style='color:#3AC430;'>Done</p>";
                    }
                    ?>
                </td>
                <?php if (  (isset($_GET['edit']) && $_GET['edit'] == $row['id']) ) { ?>
                    <td class="btn-action">
                        <form action="reservation.php" method="post">
                            <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                            <?php if ($row['status'] == 0) { ?>
                                <button id="btn-stat" type="submit" style="background:#3AC430;" name="mark_done">Mark Done</button>
                            <?php } else { ?>
                                <button id="btn-stat" type="submit" style="background:#FE9705;" name="mark_pending">Mark Pending</button>
                            <?php } ?>
                        </form>
                    </td>
                <?php } else { ?>
                    <td class="btn-action">
                        <a style="text-decoration:none;font-size: 28px;" href="?edit=<?php echo $row['id']; ?>"><i style="color:grey;" class='bx bx-pencil'></i></a>
                        <form   method="post">
                            <input type="hidden" name="table" value="reservation">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="link" value="appointment">
                            <button type="submit" name="delete"><i style="color:red;" class="bx bx-trash"></i></button>
                        </form>
                    </td>
                <?php } ?>
            </tr>
            <?php
                $i++;
                }
            } else {
                echo "<tr><td colspan='7'><p style='text-align:center;'>No reservation found</p></td></tr>";
            }
            ?>
            </tbody>
            </table>
        </div>
    </div>

    <div class="add-icon">
        <i class='bx bx-calendar-plus' id="addAdminIcon"></i>
    </div>

    <div class="details form-add1"> 
        <div class="recent_project">
        <div class="card_header">
                <h2>Add Reservation</h2>
            </div>
            <table class="form-add">
            <tbody>
                <form action="reservation.php" method="post">
                    <tr class="form-input">
                        <td>
                        <label for="destination">Destination</label>              
                        </td>
                        <td>
                        <select id="destination" name="destination_id" required>
                <?php
                    $sql = "SELECT * FROM destinations";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                        }
                    }
                ?>
                        </select>
                        </td>
                    </tr>
                    <tr class="form-input">
                        <td>
                        <label for="client">Client :</label> 
                        </td>
                        <td>
                        <select id="client" name="client_id" required>
                            <option value="NULL">Not Assigned</option>
                <?php
                    $sqls = "SELECT * FROM client";
                    $resultq = $conn->query($sqls);
                    if ($resultq->num_rows > 0) {
                        while ($row = $resultq->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                        }
                    }
                ?>
                        </select>
                        </td>
                    </tr>
                    <tr class="form-input">
                        <td>
                        <label for="reservation_date">Reservation Date</label>
                        </td>
                        <td>
                        <input style="margin-left:0px;" type="date" id="reservation_date" name="reservation_date" placeholder="Date" required min="<?php echo date('Y-m-d'); ?>">
                        </td>
                    </tr>
                    <tr class="form-input">
                        <td colspan="2">
                            <button type="submit" name="add">Add</button>
                        </td>
                    </tr>
                </form>
                <?php
                if (isset($_POST["mark_done"])) {
                    $appointment_id = $_POST["appointment_id"];
                    $sql_update_status = "UPDATE reservation SET status = 1 WHERE id = $appointment_id";
                    if ($conn->query($sql_update_status) === TRUE) {
                        echo "<script>window.location.pathname = 'air/dashboard/reservation.php';</script>";
                    }
                }

                if (isset($_POST["mark_pending"])) {
                    $appointment_id = $_POST["appointment_id"];
                    $sql_update_status = "UPDATE reservation SET status = 0 WHERE id = $appointment_id";
                    if ($conn->query($sql_update_status) === TRUE) {
                        echo "<script>window.location.pathname = 'air/dashboard/reservation.php';</script>";
                    }
                }

                if (isset($_POST["add"])) {
                    $client_id = $_POST['client_id'] == 'NULL' ? NULL : $_POST['client_id'];
                    $destination_id = $_POST['destination_id'];
                    $reservation_date = $_POST['reservation_date'];
                    $sql = "INSERT INTO reservation (client_id, destination_id, reservation_date, status) VALUES (?, ?, ?, 0)";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("iis", $client_id, $destination_id, $reservation_date);
                        if ($stmt->execute()) {
                            echo "<script>window.location.pathname = 'air/dashboard/reservation.php';</script>";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                        $stmt->close();
                    }
                }

                if (isset($_POST["delete"])) {
                    $id = $_POST["id"];
                    $sql = "DELETE FROM reservation WHERE id = ?";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("i", $id);
                        if ($stmt->execute()) {
                            echo "<script>window.location.pathname = 'air/dashboard/reservation.php';</script>";
                            header("location: air/dashboard/reservation.php");
                            exit;
                        } else {
                            echo "Error deleting record: " . $conn->error;
                        }
                        $stmt->close();
                    }
                }
                $conn->close();
                ?>
            </tbody>
            </table>
        </div>
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
        if(sidebar.classList.contains("open")) {
            closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else {
            closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
    };

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
