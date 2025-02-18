<?php
session_start();

include_once('db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['user_id'];
$success_message = $error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST["current_password"];
    $new_username = $_POST["new_username"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate that new password matches confirm password
    if ($new_password !== $confirm_password) {
        $error_message = "New password and confirm password do not match.";
    } else {
        // Fetch the current password for the logged-in user
        $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify current password
            if (password_verify($current_password, $user['password'])) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Update the username and password
                $update_stmt = $conn->prepare("UPDATE user SET username = ?, password = ? WHERE id = ?");
                $update_stmt->bind_param("ssi", $new_username, $hashed_password, $user_id);

                if ($update_stmt->execute()) {
                    $success_message = "Username and password updated successfully!";
                } else {
                    $error_message = "Failed to update username and password. Please try again.";
                }
                $update_stmt->close();
            } else {
                $error_message = "Current password is incorrect.";
            }
        } else {
            $error_message = "User not found.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Username and Password - 2025 PIC18F4550 | ESP32 - 4GOS</title>
    <script>
        // Function to toggle the dropdown visibility
        function toggleDropdown() {
            const dropdown = document.getElementById("dropdownMenu");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }
    </script>
    <style>
    .bg-cyan-600 {
        background-color: var(--color-cyan-600) /* oklch(0.609 0.126 221.723) */;
    }
    .hover\:bg-cyan-700 {
        &:hover {
            @media (hover: hover) {
                background-color: var(--color-cyan-700) /* oklch(0.52 0.105 223.128) */;
            }
        }
    }
    </style>
    <link href="src/output.css" rel="stylesheet">
</head>
<body class="bg-stone-100 min-h-screen flex flex-col">

    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-md sticky top-0 z-10">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php" class="text-xl font-semibold text-stone-700 hover:text-stone-900 flex justify-center items-center gap-2">
            <svg fill="#000000" viewBox="0 0 24 24" id="processor" data-name="Flat Line" xmlns="http://www.w3.org/2000/svg" class="icon flat-line h-8 w-8 inline"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path id="secondary" d="M17,6H7A1,1,0,0,0,6,7V17a1,1,0,0,0,1,1H17a1,1,0,0,0,1-1V7A1,1,0,0,0,17,6Zm-3,8H10V10h4Z" style="fill: #2ca9bc; stroke-width: 2;"></path><path id="primary" d="M12,6V3m4,3V4M8,6V4m10,8h3m-3,4h2M18,8h2M12,18v3M8,18v2m8-2v2M6,12H3M6,8H4m2,8H4m14,1V7a1,1,0,0,0-1-1H7A1,1,0,0,0,6,7V17a1,1,0,0,0,1,1H17A1,1,0,0,0,18,17Zm-4-7H10v4h4Z" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path></g></svg>
                PIC18F4550 | ESP32 - 4GOS
            </a>
            <div class="relative">
                <button class="flex items-center space-x-2 text-stone-600 hover:text-stone-800 focus:outline-none" onclick="toggleDropdown()">
                    <span class="text-sm font-medium"> <?php echo $_SESSION['username']; ?> </span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <!-- Dropdown menu, hidden by default -->
                <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white border border-stone-200 shadow-lg rounded-md hidden">
                    <a href="update_user.php" class="block px-4 py-2 text-sm text-stone-700 hover:bg-stone-100">Settings</a>
                    <a href="logout.php" class="block px-4 py-2 text-sm text-stone-700 hover:bg-stone-100">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Update Form -->
    <main class="flex-grow">
        <div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-md shadow-md">
            <h2 class="text-2xl font-semibold text-stone-800 mb-4">Update Username and Password</h2>

            <!-- Display success or error messages -->
            <?php if (!empty($success_message)): ?>
                <div class="mb-4 p-4 bg-green-100 text-green-800 border border-green-300 rounded-md">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-800 border border-red-300 rounded-md">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="update_user.php" method="POST" class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-stone-700">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required
                        class="mt-1 block w-full px-3 py-2 border border-stone-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-stone-200">
                </div>

                <div>
                    <label for="new_username" class="block text-sm font-medium text-stone-700">New Username</label>
                    <input type="text" id="new_username" name="new_username" required
                        class="mt-1 block w-full px-3 py-2 border border-stone-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-stone-200">
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-stone-700">New Password</label>
                    <input type="password" id="new_password" name="new_password" required
                        class="mt-1 block w-full px-3 py-2 border border-stone-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-stone-200">
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-stone-700">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                        class="mt-1 block w-full px-3 py-2 border border-stone-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-stone-200">
                </div>

                <button type="submit"
                    class="w-full bg-cyan-600 text-white py-2 px-4 rounded-md shadow hover:bg-cyan-700 focus:outline-none focus:ring focus:ring-stone-200">
                    Update
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white mt-10 py-4 shadow-inner">
        <div class="container mx-auto text-center">
            <p class="text-sm text-stone-500">&copy; 2025 PIC18F4550 | ESP32 - 4GOS. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
