<?php
include('../config/db.php');
session_start();

// Ensure the user is logged in as a client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Client') {
    header("Location: login.php");
    exit();
}

// Handle reservation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lawyer_id = $_POST['lawyer_id'];
    $reservation_date = $_POST['reservation_date'];
    $client_id = $_SESSION['user_id'];

    $sql = "INSERT INTO Reservation (LawyerID, ClientID, ReservationDate) 
            VALUES ('$lawyer_id', '$client_id', '$reservation_date')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Reservation successfully made!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

// Fetch lawyer data and their associated user names
$sql = "SELECT User.Name, Lawyer.LawyerID, Lawyer.Specialization, Lawyer.PhotoURL, Lawyer.ExpYears, Lawyer.Bio
        FROM Lawyer
        JOIN User ON Lawyer.LawyerID = User.UserID";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LawyerUp - Book a Consultation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-100">

<!-- Navbar -->


<!-- Main Content -->
<div class="container mx-auto p-6">
    <h2 class="text-4xl font-semibold text-center text-gray-800 mb-10">Find a Lawyer & Book a Consultation</h2>

    <?php if (isset($success_message)) : ?>
        <div class="bg-green-100 text-green-800 p-4 rounded-md mb-6">
            <strong><?php echo $success_message; ?></strong>
        </div>
    <?php elseif (isset($error_message)) : ?>
        <div class="bg-red-100 text-red-800 p-4 rounded-md mb-6">
            <strong><?php echo $error_message; ?></strong>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php while ($lawyer = $result->fetch_assoc()) : ?>
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="<?php echo $lawyer['PhotoURL']; ?>" alt="Lawyer Photo" class="w-full h-48 object-cover">
                <div class="p-6">

                    <h3 class="text-4xl mb-4 font-semibold text-gray-900"><?php echo $lawyer['Name']; ?></h3>
                    <p class="text-2xl font-semibold text-gray-700"><?php echo $lawyer['Specialization']; ?></p>
                    <p class="text-gray-600 mt-2">Experience: <?php echo $lawyer['ExpYears']; ?> years</p>
                    <p class="text-gray-600 mt-2"><?php echo $lawyer['Bio']; ?></p>
                    
                    <!-- Book Consultation Form -->
                    <form method="POST" action="" class="mt-4">
                        <input type="hidden" name="lawyer_id" value="<?php echo $lawyer['LawyerID']; ?>">
                        <div class="flex items-center space-x-4">
                            <input type="datetime-local" name="reservation_date" required class="p-2 border border-gray-300 rounded-md w-full">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Book</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Footer -->

</body>
</html>
