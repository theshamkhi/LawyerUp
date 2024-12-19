<?php
include('../config/db.php');
session_start();
$lawyer_id = $_SESSION['user_id'];

$sql = "SELECT User.Name, Lawyer.PhotoURL, Lawyer.Specialization, Lawyer.ExpYears, Lawyer.Bio
        FROM Lawyer
        JOIN User ON Lawyer.LawyerID = User.UserID
        WHERE Lawyer.LawyerID = $lawyer_id";
$lawyer_result = $conn->query($sql);

if ($lawyer_result->num_rows > 0) {
    $lawyer = $lawyer_result->fetch_assoc();
} else {
    $lawyer = null;
    $error_message = "Lawyer profile not found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lawyer Dashboard</title>
    <link rel="icon" href="../assets/media/court.png"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-gray-100">

<!-- Navbar -->

<!-- Main -->
<div class="container mx-auto p-6">
    <?php if ($lawyer): ?>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
            <div class="flex items-center p-6">
                <img src="<?php echo $lawyer['PhotoURL']; ?>" alt="Lawyer Photo" class="w-32 h-32 rounded-full object-cover mr-6">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900"><?php echo $lawyer['Name']; ?></h1>
                    <p class="text-xl font-semibold text-blue-600"><?php echo $lawyer['Specialization']; ?></p>
                    <p class="text-gray-600 mt-2">Experience: <?php echo $lawyer['ExpYears']; ?> years</p>
                    <p class="text-gray-600 mt-2"><?php echo $lawyer['Bio']; ?></p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-red-100 text-red-800 p-4 rounded-md mb-6">
            <strong><?php echo $error_message; ?></strong>
        </div>
    <?php endif; ?>

    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Reservations</h2>

    <?php
    $reservation_sql = "SELECT User.Name AS Name, Reservation.ReservationDate, Reservation.ReservationID
                        FROM Reservation
                        JOIN User ON Reservation.ClientID = User.UserID
                        WHERE Reservation.LawyerID = $lawyer_id";

    $reservation_result = $conn->query($reservation_sql);
    ?>

    <?php if ($reservation_result->num_rows > 0) : ?>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse bg-white shadow-lg rounded-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Client</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Reservation Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reservation = $reservation_result->fetch_assoc()) : ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm"><?php echo $reservation['Name']; ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo $reservation['ReservationDate']; ?></td>
                            <td class="px-6 py-4">
                                <form method="POST" action="reservations.php" class="flex space-x-2">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['ReservationID']; ?>">
                                    <button name="action" value="accept" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Accept</button>
                                    <button name="action" value="reject" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <p class="text-gray-600">You have no upcoming reservations.</p>
    <?php endif; ?>
</div>

<!-- Footer -->

</body>
</html>
