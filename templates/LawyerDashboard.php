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
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</head>
<body class="bg-gray-100">

<!-- Navbar -->


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
      <?php if ($lawyer): ?>
        <div class="flex flex-col">
            <img src="<?php echo $lawyer['PhotoURL']; ?>" alt="Lawyer Photo" class="object-cover">
            <div class="px-3 py-4">
                <h2 class="text-xl font-semibold text-white text-center uppercase mb-4"><?php echo $lawyer['Name']; ?></h2>
                <p class="text-base text-gray-100 mb-4"><?php echo $lawyer['Specialization']?> Specialist</p>
                <hr class="h-1 my-4 bg-gray-200 border-0 rounded dark:bg-gray-700">
                    <p class="text-base text-gray-300">&#10077; <?php echo $lawyer['Bio']; ?> &#10078;</p>
                <hr class="h-1 my-4 bg-gray-200 border-0 rounded dark:bg-gray-700">
            </div>
        </div>
      <?php else: ?>
         <div class="bg-red-100 text-red-800 p-4 rounded-md mb-6">
            <strong><?php echo $error_message; ?></strong>
         </div>
      <?php endif; ?>

      <ul class="space-y-2 font-medium px-3 py-2">
         <li>
            <a href="ClientDashboard.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
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
    <?php
        $reservation_sql = "SELECT User.Name AS Name, Reservation.ReservationDate, Reservation.ReservationID
                            FROM Reservation
                            JOIN User ON Reservation.ClientID = User.UserID
                            WHERE Reservation.LawyerID = $lawyer_id";

        $reservation_result = $conn->query($reservation_sql);
    ?>
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Reservations</h2>
    <?php if ($reservation_result->num_rows > 0) : ?>
        <div class="flex items-center justify-center overflow-x-auto">
            <table class="min-w-full table-auto border-collapse bg-white shadow-lg">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-white">Client</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-white">Reservation Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-white">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reservation = $reservation_result->fetch_assoc()) : ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm"><?php echo $reservation['Name']; ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo $reservation['ReservationDate']; ?></td>
                            <td class="px-6 py-4">
                                <form method="POST" action="#" class="flex space-x-2">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['ReservationID']; ?>">
                                    <button name="action" value="accept" class="text-xl hover:scale-105">✅</button>
                                    <button name="action" value="reject" class="text-xl hover:scale-105">❌</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <p class="text-gray-700">You have no upcoming reservations.</p>
    <?php endif; ?>
</div>

<!-- Footer -->

</body>
</html>
