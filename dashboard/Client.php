<?php
session_start();


if ($_SESSION['role'] != 'client' && $_SESSION['role'] != 'admin' ) {
    header("Location: ../index.php");
    exit();
}
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
    exit;
}
include '../db.php';
$userid = $_SESSION['user_id'];
    $tablename = $_SESSION['role'];
    
    $sqla = "SELECT name FROM $tablename WHERE id = '$userid'";

    $resulta = $conn->query($sqla);

if ($resulta->num_rows > 0) {
        // If a row is found, fetch the name
        $row = $resulta->fetch_assoc();
        $name = $row["name"];
        
        // Get the first 3 letters of the name
        $nameuser = substr($name, 0, 15);
        
 }

 if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST["add"])) {
    $client_id = $_SESSION['user_id'];;
    $destination_id = $_POST['destination_id'];
    $reservation_date = $_POST['reservation_date'];
    $sql = "INSERT INTO reservation (client_id, destination_id, reservation_date, status) VALUES (?, ?, ?, 0)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iis", $client_id, $destination_id, $reservation_date);
        if ($stmt->execute()) {
            echo "<script>alert('Reservation added successfully!')</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $stmt->close();
    }
}
if(isset($_POST["add-contact"])){

    // Retrieve form data
    $name = $_POST["name"];
    $subject = $_POST["subject"];
    $email = $_POST["email"];
    $message = $_POST["message"];
    $phone = $_POST["phone"];
    $date = $_POST["date"];
    $details = $_POST["details"];
    $address = $_POST["address"];

    // Insert data into the contacts table
    $sql = "INSERT INTO contacts (name, subject, email, message, phone, date, details, address) 
            VALUES ('$name', '$subject', '$email', '$message', '$phone', '$date', '$details', '$address')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message sent successfully!')</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Client.css">
    
    <title>Air travel</title>
</head>
<style>
    .btn-reservation {
    color:white;
    font-size: 14px;
    padding: 10px 25px;
    transition:0.5s;
    border:none;
    font-weight: bolder;
    background-color: #1b9c9a;
    border-radius: 8px;
    -webkit-border-radius:8px;
    -moz-border-radius: 8px;
    -ms-border-radius: 8px;
    -o-border-radius: 8px;
    -webkit-border-radius: 8px;
}
.btn-reservation:hover{
    background-color: #0b8684;
}


#contact {
    padding:0 10%;
    margin-bottom: 50px;
    color: white;
    height: 100vh;
}
#contact form {
    border: 5px solid #41dfdc;
  box-shadow: 0 0 20px #1b9c9a;
  border-radius: 25px;
    background-color:transparent;
    margin: auto;
    display: flex;
    flex-direction: column;
    padding: 20px;
    color: white;
}
.left-right {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;

}
.left-right .left , .left-right .right {
    display: flex;
    flex-direction: column;
    width: 49%;
}
#contact form label {
    font-size: 14px;
    padding: 10px 0;
    font-weight: 600
}
#contact form input {
    background-color: transparent;
    padding:8px;
    outline: 0;
    border: 3px solid rgb(100, 214, 209) ;
    color:white;
}
textarea {
    color:white;
    background-color: transparent;
    height: 150px;
    resize: none;
    outline: 0;
    width: 100%;
    padding: 10px;
    border: 3px solid rgb(100, 214, 209) ;
}


</style>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">
            <a href="#"> <span>Air</span> travel</a>
        </div>
        <ul class="menu">
            <li><a href="#">Acceuil</a></li>
            <li><a href="#a-propos">à propos</a></li>
            <li><a href="#popular-destination">Destination</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <a href="../logout.php" style="color:white;"  class="btn-reservation">Logout</a>
    </header>

    <!-- Home Section -->
    <section id="home">
        <h2 >Bonjour, <span style="text-transform: capitalize; color:#41dfdc;"><?php echo $nameuser ?></span></h2>
       <h4>Nous suivre voyagez en Sècuritè</h4>
        <a href="#reservation" style="color:white;" class="btn-reservation">Rèserver Maintenant</a>
    </section>

    <!-- About Section -->
    <section id="a-propos">
        <h1 class="title">à propos</h1>
        <div class="img-desc">
            <div class="left">
                    <image src="../img/giv.jpg"></image>
            </div>
        <div class="right">
            <h3>Nous voyageons pour d'autre ètats, d'autres vies, d'autres àmes</h3>
        </div>
        </div>
    </section>

    <!-- Reservation Section -->
    <section id="reservation">
    <h1 class="title">Réserver une destination</h1>
    <form action="Client.php" id="thisform" method="POST">
        <div class="field">
            <label for="destination">Destination</label>
            <select id="destination" name="destination_id" required>
                <?php
                    // Retrieve destination information from the database
                    $sql = "SELECT * FROM destinations";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <div class="field">
            <label for="reservation_date">Date de réservation</label>
            <input type="date" id="reservation_date" name="reservation_date" placeholder="Date" required min="<?php echo date('Y-m-d'); ?>">
        </div>
        <button type="submit" style="background-color: #1b9c9a; border:none;cursor: pointer;color:white;" name="add">Réserver</button>
    </form>
</section>

<section id="popular-destination">
    <h1 class="title">Destinations populaires</h1>
    <div class="content">
        <?php
            // Retrieve destination information from the database
            $sql = "SELECT * FROM destinations";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<div class='destination-card'>";
                    echo "<img src='" . $row["img"] . "' width='100' alt='Destination Image'>";
                    echo "<div class='destination-details'>";
                    echo "<h3 class='destination-title'>" . $row["name"] . "</h3>";
                    echo "<p class='destination-description'>" . $row["description"] . "</p>";
                    echo "<div class='destination-info'>";
                    echo "<div style='color:gold;font-size:26px;' class='star-ratings'>";
                    // Display star ratings based on the number of stars
                    for ($i = 0; $i < $row["stars"]; $i++) {
                        echo "<span class='star'>&#9733;</span>";
                    }
                    // Adjust star ratings for specific conditions
                    if ($row["stars"] == 3) {
                        // Add 2 black stars
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                    } elseif ($row["stars"] == 2) {
                        // Add 3 black stars
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                    } elseif ($row["stars"] == 4) {
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                    } elseif ($row["stars"] == 1) {
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                    } elseif ($row["stars"] == 0) {
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                        echo "<span class='star' style='color: black;'>&#9733;</span>";
                    }
                    echo "</div>";
                    echo "<h2 class='destination-price'>" . $row["prix"] . " $</h2>";
                    echo "</div>";
                   
                    echo "<input type='hidden' name='destination_id' value='" . $row["id"] . "'>";
                    echo "<button class='reserve-button' onclick='submitForm(" . $row["id"] . ")'>Réserver</button>";
                   
                    echo "</div>";
                    echo "</div>";
                }
            }
        ?>
    </div>
</section>



    <!-- Contact Section -->
    <section id="contact">
        <h1 class="title">Contact</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="left-right">
        <div class="left">
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name" minlength="3" required><br><br>
            <label for="subject">Objet:</label>
            <input type="text" id="subject" name="subject" required><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="message">Message:</label><br>
            <textarea id="message" name="message" rows="4" required></textarea><br><br>
            <button style="margin-top:10px;width:150px;align-self:center;cursor:pointer; background-color: #1b9c9a;" class="btn-reservation" name="add-contact" type="submit">Envoyer</button>
            </div>
            <div class="right">
            <label for="phone">Numèro:</label>
            <input type="tel" id="phone" pattern="[0-9]{10}" name="phone" required><br><br>
            <label for="date">Date:</label>
            <input type="date" name="date" id="date" placeholder="Date" required min="1900-01-01" max="<?php echo date('Y-m-d'); ?>"><br><br>
            <label for="details">Autres détails:</label>
            <input type="text" id="details" name="details"><br><br>
            <label for="address">Adresse:</label>
            <input type="text" id="address" name="address" required><br><br> 
            
            </div>
            </div>
           
        </form>
    </section>

<script>
    function submitForm(destinationId) {
        
        document.querySelector("#destination").value = destinationId;
        document.getElementById("reservation").scrollIntoView();
    }
</script>
</body>
</html>

