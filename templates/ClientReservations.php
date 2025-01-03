<?php
include('../config/db.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Client') {
    header("Location: login.php");
    exit();
}

$logged_in_user_id = $_SESSION['user_id'];

$user_sql = "SELECT * FROM User WHERE UserID = $logged_in_user_id";
$user_result = $conn->query($user_sql);

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    $user = null;
}

$sql = "SELECT User.Name, User.Email, Lawyer.LawyerID, Lawyer.Specialization, Lawyer.PhotoURL, Lawyer.ExpYears, Lawyer.Bio, Lawyer.Rating, Lawyer.PhoneNumber
        FROM Lawyer
        JOIN User ON Lawyer.LawyerID = User.UserID";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link rel="icon" href="../assets/media/court.png"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <!-- AOS Animation CDN -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body class="bg-gray-100">

<!-- Main -->
<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
   <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-80 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full overflow-y-auto bg-gray-50 dark:bg-gray-800">
    <!-- Sidebar Menu -->
    <div class="flex flex-col">
        <img src="https://images.rawpixel.com/image_social_landscape/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDI0LTA4L2thdGV2NjQ0N19waG90b19vZl93b29kZW5fZ2F2ZWxfaW5fdGhlX2NvdXJ0X2dhdmVsX3BsYWNlX29uX3RoZV83MmVhZDZjNS1lNGIxLTRlZDctYWIzNC03NThiMDVmZmY3YjRfMS5qcGc.jpg" alt="Lawyer Photo" class="object-cover">
        <div class="px-3 py-4">
            <h2 class="text-3xl font-semibold text-center text-white mb-6">LawyerUp</h2>
            <hr class="h-1 bg-gray-500 border-0 rounded dark:bg-gray-400">
        </div>
    </div>

      <ul class="space-y-2 font-medium px-3 pb-4">
        <li>
            <?php if ($user): ?>
                <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <img class="flex-shrink-0 w-8 h-8" src="../assets/media/user.png">
                    <span class="flex-1 ms-3 whitespace-nowrap"><?php echo $user['Name']; ?> &#128994;</span>
                </a>
            <?php else: ?>
                <p class="text-base text-red-500">User not found.</p>
            <?php endif; ?>
        </li>
        <li>
            <a href="ClientDashboard.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                  <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="ClientReservations.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Reservations</span>
            </a>
        </li>
        <li>
            <a href="logout.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Log Out</span>
            </a>
        </li>
      </ul>
   </div>
</aside>

<div class="p-8 sm:ml-80">
    <h2 class="text-4xl font-semibold text-gray-700 mb-6">Booked Consultations</h2>
    <?php
        $reservation_sql = "
        SELECT Reservation.LawyerID, Reservation.ReservationDate, Reservation.ReservationID, Reservation.Status, User.Name AS LawyerName
        FROM Reservation
        JOIN User ON Reservation.LawyerID = User.UserID
        WHERE Reservation.ClientID = $logged_in_user_id";

        $reservation_result = $conn->query($reservation_sql);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['action'])) {
            $reservation_id = intval($_POST['reservation_id']);
            $action = $_POST['action'];

            if ($action === 'cancel') {
                $update_sql = "DELETE FROM Reservation WHERE ReservationID = $reservation_id";
                
                if ($conn->query($update_sql) === TRUE) {
                    echo "<p class='text-green-500 my-4'>Reservation cancelled successfully.</p>";
                } else {
                    echo "<p class='text-red-500 my-4'>Error cancelling reservation: " . $conn->error . "</p>";
                }
            }
        }
    ?>
    <?php if ($reservation_result->num_rows > 0) : ?>
        <div class="overflow-auto bg-white shadow-lg rounded-lg" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
            <table class="min-w-full table-auto border-collapse text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-white">Lawyer</th>
                        <th class="px-6 py-3 text-left font-medium text-white">Reservation Date</th>
                        <th class="px-6 py-3 text-left font-medium text-white">Status</th>
                        <th class="px-6 py-3 text-left font-medium text-white">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reservation = $reservation_result->fetch_assoc()) : ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo $reservation['LawyerName']; ?></td>
                            <td class="px-6 py-4"><?php echo $reservation['ReservationDate']; ?></td>
                            <td class="px-6 py-4"><?php echo $reservation['Status']; ?></td>
                            <td class="px-6 py-4">
                                <form method="POST" class="flex space-x-2">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['ReservationID']; ?>">
                                    <button name="action" value="cancel" class="text-xl hover:scale-105">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <p class="text-gray-700">You haven't Booked any Consultations.</p>
    <?php endif; ?>
</div>


<script>
  AOS.init();
</script>

</body>
</html>
