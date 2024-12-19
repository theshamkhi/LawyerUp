<?php
include('../config/db.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Client') {
    header("Location: login.php");
    exit();
}

$logged_in_user_id = $_SESSION['user_id'];

$user_sql = "SELECT * FROM User WHERE UserID = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $logged_in_user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    $user = null;
}

// Handeling the Booking form
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
    <title>LawyerUp - Book a Consultation</title>
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
    <div class="flex flex-col">
        <img src="https://images.rawpixel.com/image_social_landscape/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDI0LTA4L2thdGV2NjQ0N19waG90b19vZl93b29kZW5fZ2F2ZWxfaW5fdGhlX2NvdXJ0X2dhdmVsX3BsYWNlX29uX3RoZV83MmVhZDZjNS1lNGIxLTRlZDctYWIzNC03NThiMDVmZmY3YjRfMS5qcGc.jpg" alt="Lawyer Photo" class="object-cover">
        <div class="px-3 py-4">
            <h2 class="text-3xl font-semibold text-center text-white mb-6">LawyerUp</h2>
            <hr class="h-1 bg-gray-500 border-0 rounded dark:bg-gray-400">
        </div>
    </div>

      <ul class="space-y-2 font-medium px-3 py-4">
        <li>
        <?php if ($user): ?>
                <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap"><?php echo htmlspecialchars($user['Name']); ?></span>
                </a>
            <?php else: ?>
                <p class="text-base text-red-500">User not found.</p>
            <?php endif; ?>
        </li>
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


<div class="flex-1 ml-0 sm:ml-80 p-8">
    <h2 class="text-4xl font-semibold text-gray-800 mb-10">Find a Lawyer & Book a Consultation</h2>

    <?php if (isset($success_message)) : ?>
        <div class="bg-green-100 text-green-800 p-4 rounded-md mb-6">
            <strong><?php echo $success_message; ?></strong>
        </div>
    <?php elseif (isset($error_message)) : ?>
        <div class="bg-red-100 text-red-800 p-4 rounded-md mb-6">
            <strong><?php echo $error_message; ?></strong>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" style="align-items: start;">
        <?php while ($lawyer = $result->fetch_assoc()) : ?>
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="<?php echo $lawyer['PhotoURL']; ?>" alt="Lawyer Photo" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-4xl mb-4 font-semibold text-gray-900"><?php echo $lawyer['Name']; ?></h3>
                    <p class="text-xl font-semibold text-gray-700">&#127891; <?php echo $lawyer['Specialization']; ?></p>
                    <p class="text-xl font-semibold text-gray-700">&#128231; <?php echo $lawyer['Email']; ?></p>
                    <p class="text-xl font-semibold text-gray-700">&#128222; <?php echo $lawyer['PhoneNumber']; ?></p>
                    <p class="text-xl font-semibold text-gray-700">&#127775; <?php echo $lawyer['Rating']; ?>/5</p>
                    <p class="text-xl font-semibold text-gray-700">&#128188; <?php echo $lawyer['ExpYears']; ?> years of experience</p>
                    <hr class="h-1 my-4 bg-gray-50 border-0 rounded dark:bg-gray-800">
                    <p class="text-xl font-semibold text-gray-700">&#10077; <?php echo $lawyer['Bio']; ?> &#10077;</p>
                    <hr class="h-1 my-4 bg-gray-50 border-0 rounded dark:bg-gray-800">

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
