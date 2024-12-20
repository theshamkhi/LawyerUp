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
        <div class="flex flex-col space-y-2">
            <img src="<?php echo $lawyer['PhotoURL']; ?>" alt="Lawyer Photo" class="object-cover">
            <div class="px-3 pt-4">
                <h2 class="text-xl font-semibold text-white text-center uppercase mb-4"><?php echo $lawyer['Name']; ?></h2>
                <p class="text-base text-gray-400">&#128221;  <?php echo $lawyer['Specialization']?> Specialist</p>
                <p class="text-base text-gray-400">&#128188;  <?php echo $lawyer['ExpYears']; ?> Years of experience</p>
                <p class="text-base text-gray-400">&#128231;  <?php echo $lawyer['Email']; ?></p>
                <p class="text-base text-gray-400">&#128222;  <?php echo $lawyer['PhoneNumber']; ?></p>
                <p class="text-base text-gray-400">&#127775;  <?php echo $lawyer['Rating']; ?>/5</p>
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

<div class="p-8 sm:ml-80">
    <?php
    $sql = "SELECT User.Name, User.Email, Lawyer.LawyerID, Lawyer.Specialization, Lawyer.PhotoURL, 
            Lawyer.ExpYears, Lawyer.Bio, Lawyer.Rating, Lawyer.PhoneNumber
            FROM Lawyer
            JOIN User ON Lawyer.LawyerID = User.UserID
            WHERE Lawyer.LawyerID = $lawyer_id";
    $result = $conn->query($sql);
    $user_data = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_user'])) {

            $name = $_POST['name'];
            $email = $_POST['email'];
            $specialization = $_POST['specialization'];
            $phone = $_POST['phone'];
            $bio = $_POST['bio'];

            $update_user_sql = "UPDATE User SET Name = '$name', Email = '$email' WHERE UserID = $lawyer_id";
            $conn->query($update_user_sql);

            $update_lawyer_sql = "UPDATE Lawyer SET Specialization = '$specialization', PhoneNumber = '$phone', Bio = '$bio' 
                                WHERE LawyerID = $lawyer_id";
            $conn->query($update_lawyer_sql);
        }
    }
    ?>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full my-4 mx-0 relative z-10 max-w-2xl lg:mt-0 lg:w-5/12">
            <div class="pt-10 pr-10 pb-10 pl-10 bg-white shadow-2xl rounded-xl relative z-10">
                <form method="POST" class="w-full mt-6 mr-0 mb-0 ml-0 relative space-y-8">
                    <div class="relative">
                        <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Name</p>
                        <input type="text" name="name" value="<?= htmlspecialchars($user_data['Name']) ?>" required class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                    </div>
                    <div class="relative">
                        <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Email</p>
                        <input type="email" name="email" value="<?= htmlspecialchars($user_data['Email']) ?>" required class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                    </div>
                    <div class="relative">
                        <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Specialization</p>
                        <input type="text" name="specialization" value="<?= htmlspecialchars($user_data['Specialization']) ?>" required class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                    </div>
                    <div class="relative">
                        <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Phone Number</p>
                        <input type="text" name="phone" value="<?= htmlspecialchars($user_data['PhoneNumber']) ?>" required class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                    </div>
                    <div class="relative">
                        <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Bio</p>
                        <textarea name="bio" required class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"><?= htmlspecialchars($user_data['Bio']) ?></textarea>
                    </div>
                    <div class="relative">
                        <button type="submit" name="update_user" class="w-full inline-block pt-4 pr-5 pb-4 pl-5 text-xl font-medium text-center text-white bg-indigo-500 rounded-lg transition duration-200 hover:bg-indigo-600 ease">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>



<!-- Footer -->

</body>
</html>
