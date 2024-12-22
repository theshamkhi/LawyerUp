<?php
include('../config/db.php');

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Lawyer') {
    header("Location: login.php");
    exit;
}

$lawyer_id = $_SESSION['user_id'];

$sql = "SELECT User.Name, User.Email, Lawyer.LawyerID, Lawyer.Specialization, Lawyer.PhotoURL, Lawyer.ExpYears, Lawyer.Bio, Lawyer.Rating, Lawyer.PhoneNumber
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

// Insert Availability
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'], $_POST['start_time'], $_POST['end_time'])) {
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $insert_sql = "INSERT INTO Availability (LawyerID, Date, StartTime, EndTime, Status) 
                VALUES (?, ?, ?, ?, 'Available')";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param('isss', $lawyer_id, $date, $start_time, $end_time);

    $stmt->execute();

    header("Location: LawyerDashboard.php");
    exit;
}

// Delete Availability
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_availability_id'])) {
    $delete_id = intval($_POST['delete_availability_id']);

    $delete_sql = "DELETE FROM Availability WHERE AvailabilityID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $delete_id);

    $stmt->execute();

    header("Location: LawyerDashboard.php");
    exit;
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
      <?php if ($lawyer): ?>
        <div class="flex flex-col space-y-2">
            <img src="<?php echo $lawyer['PhotoURL']; ?>" alt="Lawyer Photo" class="object-cover">
            <div class="px-3 pt-4">
                <h2 class="text-xl font-semibold text-white text-center uppercase mb-4"><a href="ModifyLawyer.php"><?php echo $lawyer['Name']; ?></a></h2>
                <p class="text-base text-gray-400">&#128221;  <?php echo $lawyer['Specialization']?> Specialist</p>
                <p class="text-base text-gray-400">&#128188;  <?php echo $lawyer['ExpYears']; ?> Years of experience</p>
                <p class="text-base text-gray-400">&#128231;  <?php echo $lawyer['Email']; ?></p>
                <p class="text-base text-gray-400">&#128222;  <?php echo $lawyer['PhoneNumber']; ?></p>
                <hr class="h-1 my-4 bg-gray-200 border-0 rounded dark:bg-gray-700">
                    <p class="text-base text-gray-300"><?php echo $lawyer['Bio']; ?></p>
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
            <a href="LawyerDashboard.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
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

<div class="p-4 sm:p-8 lg:ml-80">
    <h2 class="text-lg sm:text-2xl font-semibold text-gray-700 mb-6">Manage Availability</h2>

    <!-- Form -->
    <form method="POST" class="flex flex-wrap items-center justify-center gap-4">
        <div class="flex flex-col">
            <label for="date" class="text-gray-700 font-semibold">Date</label>
            <input type="date" name="date" required class="p-2 rounded-lg">
        </div>
        <div class="flex flex-col">
            <label for="start_time" class="text-gray-700 font-semibold">From</label>
            <input type="time" name="start_time" required class="p-2 rounded-lg">
        </div>
        <div class="flex flex-col">
            <label for="end_time" class="text-gray-700 font-semibold">To</label>
            <input type="time" name="end_time" required class="p-2 rounded-lg">
        </div>
        <div class="flex flex-col">
            <br>
            <button type="submit" class="px-4 py-2 bg-green-500 text-white font-medium rounded-lg">Add Availability</button>
        </div>
    </form>

    <!-- Availability Table -->
    <div class="overflow-auto bg-white shadow-lg rounded-lg mt-8 mb-16">
        <?php
        $availability_sql = "SELECT AvailabilityID, Date, StartTime, EndTime, Status 
                            FROM Availability 
                            WHERE LawyerID = $lawyer_id 
                            ORDER BY Date, StartTime";
        $availability_result = $conn->query($availability_sql);
        ?>
        <table class="min-w-full border-collapse text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-white">Date</th>
                    <th class="px-6 py-3 text-left font-medium text-white">From</th>
                    <th class="px-6 py-3 text-left font-medium text-white">To</th>
                    <th class="px-6 py-3 text-left font-medium text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($availability = $availability_result->fetch_assoc()): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4"><?php echo $availability['Date']; ?></td>
                        <td class="px-6 py-4"><?php echo $availability['StartTime']; ?></td>
                        <td class="px-6 py-4"><?php echo $availability['EndTime']; ?></td>
                        <td class="px-6 py-4">
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="delete_availability_id" value="<?php echo $availability['AvailabilityID']; ?>">
                                <button type="submit" class="text-xl hover:scale-105">🗑️</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>


    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['action'])) {
            $reservation_id = intval($_POST['reservation_id']);
            $action = $_POST['action'];

            if ($action === 'accept') {
                $new_status = 'Confirmed';
            } elseif ($action === 'reject') {
                $new_status = 'Rejected';
            }

            $update_sql = "UPDATE Reservation SET Status = ? WHERE ReservationID = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param('si', $new_status, $reservation_id);

            if ($stmt->execute()) {
                echo "<p class='text-green-500 my-4'>Reservation status updated successfully.</p>";
            } else {
                echo "<p class='text-red-500 my-4'>Failed to update reservation status.</p>";
            }

        }

        $reservation_sql = "SELECT User.Name AS Name, Reservation.ReservationDate, Reservation.ReservationID, Reservation.Status
                            FROM Reservation
                            JOIN User ON Reservation.ClientID = User.UserID
                            WHERE Reservation.LawyerID = $lawyer_id";

        $reservation_result = $conn->query($reservation_sql);
    ?>
    <!-- Reservations Section -->
    <h2 class="text-lg sm:text-2xl font-semibold text-gray-700 mb-6">Reservations (<?php echo $reservation_result->num_rows; ?>)</h2>
    <?php if ($reservation_result->num_rows > 0) : ?>
        <div class="overflow-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full border-collapse text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-white">Client</th>
                        <th class="px-6 py-3 text-left font-medium text-white">Reservation Date</th>
                        <th class="px-6 py-3 text-left font-medium text-white">Status</th>
                        <th class="px-6 py-3 text-left font-medium text-white">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reservation = $reservation_result->fetch_assoc()) : ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo $reservation['Name']; ?></td>
                            <td class="px-6 py-4"><?php echo $reservation['ReservationDate']; ?></td>
                            <td class="px-6 py-4"><?php echo $reservation['Status']; ?></td>
                            <td class="px-6 py-4">
                                <form method="POST" class="flex space-x-2">
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




<script>
  AOS.init();
</script>

</body>
</html>
