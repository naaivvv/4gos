<?php
session_start();
include_once('db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - 2025 PIC18F4550 | ESP32 - 4GOS</title>
    <script src="chart.umd.min.js"></script>
    <link href="src/output.css" rel="stylesheet">
    <style>
        /* Custom dropdown animation */
        #dropdownMenu {
            transition: all 0.3s ease;
        }
        table tbody tr:first-child #temp_tb {
            font-weight: bold;
            font-size:x-large;
            color:#00acc1;
        }
        table tbody tr:first-child #voltage_tb {
            font-weight: bold;
            font-size:x-large;
            color:#ff6384cc;
        }
        table tbody tr:first-child #current_tb {
            font-weight: bold;
            font-size:x-large;
            color:#ffce56cc;
        }
        .switch {position: relative; display: inline-block; width: 60px; height: 34px;}
        .switch input {opacity: 0; width: 0; height: 0;}
        .slider {position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e2e8f0; transition: .4s;}
        .slider:before {position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s;}
        input:checked + .slider {background-color: #00acc1;}
        input:not(:checked) + .slider {background-color: #e0e0e0;}
        input:checked + .slider:before {transform: translateX(26px);}
        .slider.round {border-radius: 34px;}
        .slider.round:before {border-radius: 50%;}
        .slider::after {content: ""; position: absolute; width: 18px; height: 18px; left: 8px; bottom: 8px; background: url('data:image/svg+xml,%3Csvg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" version="1.1" fill="%23292524"%3E%3Cg id="SVGRepo_bgCarrier" stroke-width="0"%3E%3C/g%3E%3Cg id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"%3E%3C/g%3E%3Cg id="SVGRepo_iconCarrier"%3E %3Cg style="fill:none;stroke:%23292524;stroke-width:12px;stroke-linecap:round;stroke-linejoin:round;"%3E %3Cpath d="m 50,10 0,35"%3E%3C/path%3E %3Cpath d="M 26,20 C -3,48 16,90 51,90 79,90 89,67 89,52 89,37 81,26 74,20"%3E%3C/path%3E %3C/g%3E %3C/g%3E%3C/svg%3E') no-repeat center; background-size: contain; transition: .4s;}
        input:checked + .slider::after {transform: translateX(26px);}
        .lg\:grid-cols-3 {
            @media (width >= 64rem /* 1024px */) {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        .lg\:col-span-3 {
            @media (width >= 64rem /* 1024px */) {
                grid-column: span 3 / span 3;
            }
        }
        .lg\:col-span-2 {
            @media (width >= 64rem /* 1024px */) {
                grid-column: span 2 / span 2;
            }
            .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .h-8 {
            height: calc(var(--spacing) * 8) /* 2rem = 32px */;
        }
        .w-8 {
            width: calc(var(--spacing) * 8) /* 2rem = 32px */;
        }
        .max-w-sm {
            max-width: var(--container-sm) /* 24rem = 384px */;
        }
    }
    .px-6 {
        padding-inline: calc(var(--spacing) * 6) /* 1.5rem = 24px */;
    }
    .mt-6 {
        margin-top: calc(var(--spacing) * 6) /* 1.5rem = 24px */;
    }
    .mr-4 {
        margin-right: calc(var(--spacing) * 4) /* 1rem = 16px */;
    }
    .lg\:text-right {
        @media (width >= 64rem /* 1024px */) {
            text-align: right;
        }
    }
    .bg-cyan-500 {
        background-color: var(--color-cyan-500) /* oklch(0.715 0.143 215.221) */;
    }
    .hover\:bg-cyan-600 {
        &:hover {
            @media (hover: hover) {
                background-color: var(--color-cyan-600) /* oklch(0.609 0.126 221.723) */;
            }
        }
    }
    .w-12 {
        width: calc(var(--spacing) * 12) /* 3rem = 48px */;
    }
    .h-12 {
        height: calc(var(--spacing) * 12) /* 3rem = 48px */;
    }
    .w-32 {
        width: calc(var(--spacing) * 32) /* 8rem = 128px */;
    }

    .h-32 {
        height: calc(var(--spacing) * 32) /* 8rem = 128px */;
    }

    .md\:w-40 {
        @media (width >= 48rem /* 768px */) {
            width: calc(var(--spacing) * 40) /* 10rem = 160px */;
        }
    }

    .md\:h-40 {
        @media (width >= 48rem /* 768px */) {
            height: calc(var(--spacing) * 40) /* 10rem = 160px */;
        }
    }

    .md\:mt-0 {
        @media (width >= 48rem /* 768px */) {
            margin-top: calc(var(--spacing) * 0) /* 0rem = 0px */;
        }
    }
    .text-4xl {
        font-size: var(--text-4xl) /* 2.25rem = 36px */;
        line-height: var(--tw-leading, var(--text-4xl--line-height) /* calc(2.5 / 2.25) ≈ 1.1111 */);
    }
    .text-stone-400 {
        color: var(--color-stone-400) /* oklch(0.709 0.01 56.259) = #a6a09b */;
    }
    </style>
</head>
<body class="bg-stone-100 text-stone-800">

    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-md sticky top-0 z-10">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php" class="text-xl font-semibold text-stone-700 hover:text-stone-900 flex justify-center items-center gap-2">
            <svg fill="#000000" viewBox="0 0 24 24" id="processor" data-name="Flat Line" xmlns="http://www.w3.org/2000/svg" class="icon flat-line h-8 w-8 inline"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path id="secondary" d="M17,6H7A1,1,0,0,0,6,7V17a1,1,0,0,0,1,1H17a1,1,0,0,0,1-1V7A1,1,0,0,0,17,6Zm-3,8H10V10h4Z" style="fill: #2ca9bc; stroke-width: 2;"></path><path id="primary" d="M12,6V3m4,3V4M8,6V4m10,8h3m-3,4h2M18,8h2M12,18v3M8,18v2m8-2v2M6,12H3M6,8H4m2,8H4m14,1V7a1,1,0,0,0-1-1H7A1,1,0,0,0,6,7V17a1,1,0,0,0,1,1H17A1,1,0,0,0,18,17Zm-4-7H10v4h4Z" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path></g></svg>
                PIC18F4550 | ESP32 - 4GOS
            </a>
            <div class="relative">
                <button class="flex items-center text-stone-600 hover:text-stone-800 focus:outline-none" onclick="toggleDropdown()">
                    <span class="mr-2"><?php echo $_SESSION['username']; ?></span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white border border-stone-200 rounded-md shadow-lg hidden">
                    <a href="update_user.php" class="block px-4 py-2 text-sm text-stone-600 hover:bg-stone-100">Settings</a>
                    <a href="logout.php" class="block px-4 py-2 text-sm text-stone-600 hover:bg-stone-100">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-stone-50 py-12 shadow-sm">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-semibold text-stone-800">PIC18F4550-Based 4-Gang Outlet System</h2>
            <p class="text-stone-600 mt-2">with ESP32 Wi-Fi Control, Real-Time LCD Display, and <br> Temperature Monitoring</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Status -->
        <div class="bg-white shadow-sm rounded-md p-4 overflow-x-auto col-span-1 lg:col-span-3">
            <h3 class="text-xl font-semibold text-stone-800 flex items-center gap-2 mb-4">
                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M7.706 0.290 C 7.484 0.362,7.356 0.490,7.294 0.699 C 7.259 0.816,7.253 1.088,7.253 2.508 C 7.253 4.389,7.251 4.365,7.443 4.557 C 7.700 4.813,8.300 4.813,8.557 4.557 C 8.749 4.365,8.747 4.389,8.747 2.508 C 8.747 0.688,8.744 0.656,8.596 0.480 C 8.472 0.333,8.339 0.284,8.040 0.276 C 7.893 0.272,7.743 0.278,7.706 0.290 M2.753 2.266 C 2.595 2.338,2.362 2.566,2.281 2.728 C 2.197 2.897,2.193 3.085,2.269 3.253 C 2.343 3.418,4.667 5.750,4.850 5.843 C 5.109 5.976,5.375 5.911,5.643 5.649 C 5.907 5.391,5.977 5.111,5.843 4.850 C 5.750 4.667,3.418 2.343,3.253 2.269 C 3.101 2.200,2.901 2.199,2.753 2.266 M12.853 2.282 C 12.730 2.339,12.520 2.536,11.518 3.541 C 10.597 4.464,10.316 4.762,10.271 4.860 C 10.195 5.025,10.196 5.216,10.272 5.378 C 10.342 5.528,10.572 5.764,10.727 5.845 C 10.884 5.927,11.117 5.926,11.280 5.843 C 11.447 5.757,13.757 3.447,13.843 3.280 C 13.926 3.118,13.927 2.884,13.846 2.729 C 13.764 2.572,13.552 2.364,13.392 2.283 C 13.213 2.192,13.048 2.192,12.853 2.282 M0.699 7.292 C 0.404 7.385,0.258 7.620,0.258 7.999 C 0.259 8.386,0.403 8.618,0.698 8.706 C 0.816 8.741,1.079 8.747,2.508 8.747 C 3.997 8.747,4.196 8.742,4.318 8.702 C 4.498 8.644,4.644 8.498,4.702 8.318 C 4.788 8.053,4.745 7.677,4.608 7.491 C 4.578 7.451,4.492 7.384,4.417 7.343 L 4.280 7.267 2.547 7.261 C 1.152 7.257,0.791 7.263,0.699 7.292 M11.745 7.278 C 11.622 7.308,11.452 7.411,11.392 7.492 C 11.255 7.677,11.212 8.053,11.298 8.318 C 11.356 8.498,11.502 8.644,11.682 8.702 C 11.804 8.742,12.003 8.747,13.492 8.747 C 14.921 8.747,15.184 8.741,15.302 8.706 C 15.597 8.618,15.741 8.386,15.742 7.999 C 15.742 7.614,15.595 7.383,15.290 7.291 C 15.187 7.260,14.864 7.254,13.496 7.256 C 12.578 7.258,11.790 7.268,11.745 7.278 M4.853 10.282 C 4.730 10.339,4.520 10.536,3.518 11.541 C 2.597 12.464,2.316 12.762,2.271 12.860 C 2.195 13.025,2.196 13.216,2.272 13.378 C 2.342 13.528,2.572 13.764,2.727 13.845 C 2.884 13.927,3.117 13.926,3.280 13.843 C 3.447 13.757,5.757 11.447,5.843 11.280 C 5.926 11.118,5.927 10.884,5.846 10.729 C 5.764 10.572,5.552 10.364,5.392 10.283 C 5.213 10.192,5.048 10.192,4.853 10.282 M10.753 10.266 C 10.595 10.338,10.362 10.566,10.281 10.728 C 10.197 10.897,10.193 11.085,10.269 11.253 C 10.343 11.418,12.667 13.750,12.850 13.843 C 13.109 13.976,13.375 13.911,13.643 13.649 C 13.907 13.391,13.977 13.111,13.843 12.850 C 13.750 12.667,11.418 10.343,11.253 10.269 C 11.101 10.200,10.901 10.199,10.753 10.266 M7.745 11.277 C 7.620 11.309,7.451 11.412,7.392 11.492 C 7.254 11.678,7.253 11.691,7.253 13.489 C 7.253 14.921,7.259 15.184,7.294 15.302 C 7.382 15.597,7.615 15.741,8.000 15.741 C 8.385 15.741,8.618 15.597,8.706 15.302 C 8.768 15.090,8.767 11.875,8.704 11.690 C 8.644 11.514,8.575 11.430,8.420 11.346 C 8.310 11.286,8.246 11.271,8.057 11.264 C 7.930 11.259,7.790 11.265,7.745 11.277 " stroke="none" fill-rule="evenodd" fill="#292524"></path></g></svg>
                Status
            </h3>       
            <?php include("socket_status.php");?>      
        </div>

        <!-- Controller -->
        <div class="bg-white shadow-sm rounded-md p-4 col-span-1 lg:col-span-2">
            <h3 class="text-xl font-semibold text-stone-800 flex items-center gap-2 mb-6">
            <svg version="1.1" id="Icons" class="h-8 w-8 inline" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve" fill="#292524"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:none;stroke:#292524;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;} </style> <path class="st0" d="M23,15c-1.2,0-2.4,0.4-3.3,1h-7.4c-0.9-0.6-2.1-1-3.3-1c-3.3,0-6,2.7-6,6s2.7,6,6,6c1.2,0,2.4-0.4,3.3-1h7.4 c0.9,0.6,2.1,1,3.3,1c3.3,0,6-2.7,6-6S26.3,15,23,15z"></path> <line class="st0" x1="9" y1="19" x2="9" y2="23"></line> <line class="st0" x1="7" y1="21" x2="11" y2="21"></line> <line class="st0" x1="23" y1="19" x2="23" y2="19"></line> <line class="st0" x1="21" y1="21" x2="21" y2="21"></line> <line class="st0" x1="25" y1="21" x2="25" y2="21"></line> <line class="st0" x1="23" y1="23" x2="23" y2="23"></line> <g> <path class="st0" d="M21.9,5c-1.9-0.7-3.9-1-5.9-1s-4.1,0.4-5.9,1"></path> </g> <g> <path class="st0" d="M20.3,8.7C18.9,8.3,17.5,8,16,8s-2.9,0.3-4.3,0.7"></path> </g> <g> <path class="st0" d="M18.6,12.5C17.8,12.2,16.9,12,16,12s-1.8,0.2-2.6,0.5"></path> </g> </g></svg>
                4-Gang Outlet Controller
            </h3>
            <?php include("controller.php");?> 
        </div>
    </div>



        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 pt-8">
            <!-- Sensor Data Table -->
            <div class="bg-white shadow-sm rounded-md p-4 overflow-x-auto col-span-1 lg:col-span-2">
            <h3 class="text-xl font-semibold text-stone-800 flex items-center gap-2 mb-6">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 inline"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g clip-path="url(#clip0_429_11075)"> <path d="M5.63606 18.3639C9.15077 21.8786 14.8493 21.8786 18.364 18.3639C21.8787 14.8492 21.8787 9.1507 18.364 5.63598C14.8493 2.12126 9.15077 2.12126 5.63606 5.63598C3.87757 7.39447 2.99889 9.6996 3.00002 12.0044L3 13.9999" stroke="#292524" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M1 11.9999L3 13.9999L5 11.9999" stroke="#292524" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M11 7.99994L11 12.9999L16 12.9999" stroke="#292524" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path> </g> <defs> <clipPath id="clip0_429_11075"> <rect width="24" height="24" fill="white"></rect> </clipPath> </defs> </g></svg>
            Sensor Data History
            </h3>
                <table class="min-w-full text-xs text-stone-700 text-center table-fixed">
                    <thead class="bg-stone-50">
                        <tr>
                            <th class="py-3 px-4 font-semibold">Sensor Name</th>
                            <th class="py-3 px-4 font-semibold">Temperature (°C)</th>
                            <th class="py-3 px-4 font-semibold">Voltage (V)</th>
                            <th class="py-3 px-4 font-semibold">Current (A)</th>
                        </tr>
                    </thead>
                    <tbody id="sensorData" class="divide-y divide-stone-200">
                        <!-- Dynamic Content -->
                    </tbody>
                </table>
            </div>

            <!-- Chart -->
            <div class="bg-white shadow-sm rounded-md p-4 col-span-1 lg:col-span-3">
            <h3 class="text-xl font-semibold text-stone-800 flex items-center gap-2 mb-6">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 inline"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 5V19C4 19.5523 4.44772 20 5 20H19" stroke="#292524" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M18 9L13 13.9999L10.5 11.4998L7 14.9998" stroke="#292524" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
            Sensor Graphs
            </h3>
                <div class="line-chart">
                <canvas id="chartCanvasTemp"></canvas>
                <canvas id="chartCanvasVoltage"></canvas>
                <canvas id="chartCanvasCurrent"></canvas>

                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white py-4 shadow-inner">
        <div class="container mx-auto px-4 text-center text-stone-600 text-sm">
            &copy; 2025 PIC18F4550 | ESP32 - 4GOS. All rights reserved.
        </div>
    </footer>

    <script src="index.js"></script>

</body>
</html>