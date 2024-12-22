<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LawyerUp</title>
    <link rel="icon" href="../assets/media/court.png"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/style.css">
    <!-- AOS Animation CDN -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body>

<?php
include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $conn->real_escape_string($_POST['name']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $conn->real_escape_string($_POST['role']);

    $sql = "INSERT INTO User (Name, Username, Email, Password, Role) VALUES ('$name', '$username', '$email', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        $lastUserID = $conn->insert_id;

        if ($role === 'Lawyer') {
            
            $specialization = $conn->real_escape_string($_POST['specialization']);
            $photoURL = $conn->real_escape_string($_POST['photoURL']);
            $rating = (float)$_POST['rating'];
            $experience = (int)$_POST['experience'];
            $bio = $conn->real_escape_string($_POST['bio']);
            $phoneNumber = $conn->real_escape_string($_POST['phoneNumber']);

            $lawyerSql = "INSERT INTO Lawyer (LawyerID, Specialization, PhotoURL, Rating, ExpYears, Bio, PhoneNumber) 
                          VALUES ('$lastUserID', '$specialization', '$photoURL', '$rating', '$experience', '$bio', '$phoneNumber')";

            if ($conn->query($lawyerSql) === TRUE) {
                header("Location: login.php");
            } else {
                echo "Error inserting into Lawyer table: " . $conn->error;
            }
        } else {
            header("Location: login.php");
        }
    } else {
        echo "Error inserting into User table: " . $conn->error;
    }
}
?>

<div class="flex flex-col items-center justify-between pt-0 pr-10 pb-0 pl-10 mt-14 mr-auto mb-0 ml-auto max-w-7xl xl:px-5 lg:flex-row">
    <div class="flex flex-col items-center w-full pt-5 pr-10 pb-20 pl-10 lg:pt-20 lg:flex-row">
        <div class="w-full bg-cover relative max-w-md lg:max-w-2xl lg:w-7/12">
            <div class="flex flex-col items-center justify-center w-full h-full relative lg:pr-10" data-aos="fade-right" data-aos-easing="ease-in-sine" data-aos-duration="800">
                <h1 class="text-9xl text-white font-bold" style="text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.75);">
                    Objection! Your Honor
                </h1>
            </div>
        </div>
        <div class="w-full mt-20 mr-0 mb-0 ml-0 relative z-10 max-w-2xl lg:mt-0 lg:w-5/12">
            <div class="flex flex-col items-start justify-start pt-10 pr-10 pb-10 pl-10 bg-white shadow-2xl rounded-xl relative z-10">

                <form method="POST" class="w-full mt-6 mr-0 mb-0 ml-0 relative space-y-8">
                    <!-- Name -->
                    <div class="relative">
                        <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Name</p>
                        <input type="text" name="name" placeholder="Saul Goodman" required class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                    </div>

                    <!-- Username -->
                    <div class="relative">
                        <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Username</p>
                        <input type="text" name="username" placeholder="Saul123" required class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                    </div>

                    <!-- Email -->
                    <div class="relative">
                        <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Email</p>
                        <input type="email" name="email" placeholder="Saul123@gmail.com" required class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                    </div>

                    <!-- Role -->
                    <div class="relative">
                        <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Role</p>
                        <select name="role" id="role" class="border focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md">
                            <option value="Client" class="text-gray-400">Client</option>
                            <option value="Lawyer" class="text-gray-400">Lawyer</option>
                        </select>
                    </div>

                    <!-- Lawyer-Specific Fields -->
                    <div id="lawyerFields" class="hidden w-full mt-6 mr-0 mb-0 ml-0 relative space-y-8">
                        <div class="relative">
                            <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Specialization</p>
                            <input type="text" name="specialization" placeholder="e.g., Criminal Law" class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                        </div>
                        <div class="relative">
                            <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Photo URL</p>
                            <input type="url" name="photoURL" placeholder="e.g., https://example.com/photo.jpg" class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                        </div>
                        <div class="relative">
                            <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Rating (1-5)</p>
                            <input type="number" name="rating" min="1" max="5" step="0.1" placeholder="e.g., 4.5" class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                        </div>
                        <div class="relative">
                            <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Experience (Years)</p>
                            <input type="number" name="experience" placeholder="e.g., 5" class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                        </div>
                        <div class="relative">
                            <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Bio</p>
                            <textarea name="bio" placeholder="Brief bio" class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"></textarea>
                        </div>
                        <div class="relative">
                            <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Phone Number</p>
                            <input type="tel" name="phoneNumber" placeholder="e.g., 555-123-4567" class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <p class="bg-white pt-0 pr-2 pb-0 pl-2 -mt-3 mr-0 mb-0 ml-2 font-medium text-gray-600 absolute">Password</p>
                        <input type="password" name="password" placeholder="•••••••" required class="border placeholder-gray-400 focus:outline-none focus:border-black w-full pt-4 pr-4 pb-4 pl-4 mt-2 mr-0 mb-0 ml-0 text-base block bg-white border-gray-300 rounded-md"/>
                    </div>

                    <div class="relative">
                        <button type="submit" class="w-full inline-block pt-4 pr-5 pb-4 pl-5 text-xl font-medium text-center text-white bg-indigo-500
                        rounded-lg transition duration-200 hover:bg-indigo-600 ease">Register</button>
                    </div>

                    <div class="relative">
                        <p class="text-center font-medium text-gray-600">Already have an account, <a href="login.php" class="text-indigo-600 font-bold">Login</a></p>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/script.js"></script>

<script>
  AOS.init();
</script>

</body>
</html>
