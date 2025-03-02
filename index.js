
        function fetchSensorData() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    updateTable(data);
                }
            };
            xhr.open("GET", "fetch_data.php", true);
            xhr.send();
        }

        let lastStates = { s1: null, s2: null, s3: null, s4: null };
let isUpdating = false; // Lock flag to prevent conflicts

function sendState(pin, state) {
    const toggleSwitch = event.target;
    const previousState = !state;

    isUpdating = true; // Lock updates temporarily

    // 1️⃣ Insert new state into database first
    const socketStates = {
        s1: document.querySelector("#socket-3").checked ? 1 : 0,
        s2: document.querySelector("#socket-2").checked ? 1 : 0,
        s3: document.querySelector("#socket-1").checked ? 1 : 0,
        s4: document.querySelector("#socket-0").checked ? 1 : 0,
    };

    fetch("insert_socket_states.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(socketStates),
    })
    .then(response => response.json())
    .then(data => {
        console.log("Database update response:", data);

        if (data.error) {
            alert("Database update failed: " + data.error);
            toggleSwitch.checked = previousState; // Revert toggle on failure
            isUpdating = false;
            return;
        }

        // 2️⃣ After successful DB insert, send request to ESP32
        return fetch(`http://192.168.100.128/control?pin=${pin}&state=${state}`);
    })
    .then(response => {
        if (response && !response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        return response ? response.text() : null;
    })
    .then(feedback => {
        console.log("ESP32 response:", feedback);
        setTimeout(() => { isUpdating = false; }, 2000); // Unlock after 2 seconds
    })
    .catch(error => {
        console.error("Error:", error);
        alert(`Failed to update outlet. ${error.message}`);
        setTimeout(() => { toggleSwitch.checked = previousState; }, 100);
        isUpdating = false;
    });
}

// Fetch latest states, but prevent overwriting if manual toggle is active
function fetchLatestStates() {
    if (isUpdating) return;

    fetch("fetch_latest_states.php")
        .then(response => response.json())
        .then(data => {
            if (JSON.stringify(lastStates) !== JSON.stringify(data)) {
                lastStates = data;

                // Ensure the correct mapping of database values to HTML elements
                document.querySelector("#socket-3").checked = Boolean(Number(data.s1));
                document.querySelector("#socket-2").checked = Boolean(Number(data.s2));
                document.querySelector("#socket-1").checked = Boolean(Number(data.s3));
                document.querySelector("#socket-0").checked = Boolean(Number(data.s4));
            }
        })
        .catch(error => console.error("Error fetching states:", error));
}    
                        
        function updateTable(data) {
            var sensorDataTable = document.getElementById("sensorData");
            sensorDataTable.innerHTML = "";

            data.forEach(item => {
                var row = document.createElement("tr");
                row.innerHTML = `
                    <td class='py-2 px-4'>${item.sensor_name}</td>
                    <td class='py-2 px-4' id='temp_tb'>${item.temp}</td>
                    <td class='py-2 px-4' id='voltage_tb'>${item.voltage}</td>
                    <td class='py-2 px-4' id='current_tb'>${item.current}</td>
                `;
                sensorDataTable.appendChild(row);
            });
        }

function fetchChartData() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);
            var sensorTitle = data.length > 0 ? data[0].sensor_name : "Unknown Sensor";
            updateCharts(data, sensorTitle);
        }
    };
    xhr.open("GET", "fetch_chart_data.php", true);
    xhr.send();
}

// Declare global chart instances
var tempChart = null;
var voltageChart = null;
var currentChart = null;

function updateCharts(data, sensorTitle) {
    var labels = data.map(item => item.created_at);
    var tempData = data.map(item => item.temp);
    var voltageData = data.map(item => item.voltage);
    var currentData = data.map(item => item.current);

    tempChart = createChart('chartCanvasTemp', 'Temperature (°C)', labels, tempData, 'rgba(75, 192, 192, 1)', 'rgba(75, 192, 192, 0)', tempChart);
    voltageChart = createChart('chartCanvasVoltage', 'Voltage (V)', labels, voltageData, 'rgba(255, 99, 132, 1)', 'rgba(255, 99, 132, 0)', voltageChart);
    currentChart = createChart('chartCanvasCurrent', 'Current (A)', labels, currentData, 'rgba(255, 206, 86, 1)', 'rgba(255, 206, 86, 0)', currentChart);
}

function createChart(canvasId, label, labels, data, borderColor, fadeColor, existingChart) {
    // ✅ Properly destroy existing chart before creating a new one
    if (existingChart) {
        existingChart.destroy();
    }

    var ctx = document.getElementById(canvasId).getContext('2d');
    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, borderColor);
    gradient.addColorStop(1, fadeColor);

    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                borderColor: borderColor,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: borderColor,
                hoverBorderColor: 'rgba(0, 128, 255, 1)',
                hoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1500,
                easing: 'easeOutQuart',
                animations: {
                    opacity: { from: 0, to: 1, easing: 'easeOutQuart', duration: 1500 },
                    y: { easing: 'easeOutQuart', duration: 1500 }
                }
            },
            interaction: { mode: 'index', intersect: false },
            plugins: {
                title: {
                    display: true,
                    text: label,
                    font: { size: 18, weight: 'bold' },
                    padding: { top: 10, bottom: 20 }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    bodyFont: { size: 14 },
                    titleFont: { size: 16 },
                    padding: 10,
                    callbacks: { label: function (context) { return `${label}: ${context.raw}`; } }
                },
                legend: { display: true, labels: { usePointStyle: true, font: { size: 14 } } }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Timestamp', font: { size: 14, weight: 'bold' } },
                    grid: { display: false }
                },
                y: {
                    title: { display: true, text: label, font: { size: 14, weight: 'bold' } },
                    grid: { color: 'rgba(200, 200, 200, 0.5)' },
                    ticks: { beginAtZero: true }
                }
            }
        }
    });
}



        let currentTemp = 0;
        function fetchStatus() {
            // Send an AJAX request to fetch the latest status
            fetch('fetch_status.php')
                .then(response => response.json())
                .then(data => {
                    const temperatureElement = document.querySelector('.temp-status');
                    const fanElement = document.querySelector('.fan-status');
                    const kwhElement = document.querySelector('.kwh-status');
                    const voltageElement = document.querySelector('.voltage-status');
                    const currentElement = document.querySelector('.current-status');

                    if (data.status === "Connected") {
                        // Update temperature with dynamic color
                        currentTemp = data.temp;
                        temperatureElement.innerHTML = `<span style="color: ${
                            currentTemp >= 30 ? '#ef4444' : // Red for temp >= 30
                            currentTemp >= 20 ? '#f97316' : // Orange for temp 20-29
                            currentTemp >= 10 ? '#3b82f6' : // Blue for temp 10-19
                            '#60a5fa' // Lighter blue for temp < 10
                        }">${currentTemp} °C</span>`;

                        updateThermometer(currentTemp);

                        // Update fan status with dynamic color
                        const fanStatus = data.is_fan_on == 1 ? 'ON' : 'OFF';
                        fanElement.innerHTML = `
                            <svg class="h-6 w-6 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect x="2" y="2" width="20" height="20" rx="1" stroke="#292524" stroke-width="2"></rect> <rect x="2" y="2" width="20" height="20" rx="10" stroke="#292524" stroke-width="2"></rect> <path d="M14.9999 9.50009C15.4999 9.16675 15.8999 7.80009 15.4999 7.00009C15.0999 6.20009 13.3334 5.66674 13.0001 5.50007M14 10.5001C15.5001 8.5001 14.0001 7.00006 11.5 5.5001C9.95405 4.57263 13.4999 4.00009 16.0001 6.00009C17.8751 7.49994 17 8.50009 14 11.5001V10.5001Z" stroke="#292524" stroke-width="2"></path> <path d="M8.98522 14.402C8.48522 14.7353 8.08522 16.102 8.48522 16.902C8.88522 17.702 10.6517 18.2354 10.9851 18.402M9.98516 13.402C8.48509 15.402 9.98504 16.902 12.4852 18.402C14.0311 19.3295 10.4852 19.902 7.98502 17.902C6.11005 16.4022 6.98516 15.402 9.98516 12.402V13.402Z" stroke="#292524" stroke-width="2"></path> <path d="M9.54159 8.94372C9.20826 8.44372 7.84159 8.04372 7.04159 8.44372C6.24159 8.84372 5.70824 10.6102 5.54157 10.9435M10.5416 9.94366C8.54161 8.44359 7.04156 9.94354 5.54161 12.4437C4.61413 13.9896 4.04159 10.4437 6.04159 7.94352C7.54145 6.06854 8.5416 6.94366 11.5416 9.94366H10.5416Z" stroke="#292524" stroke-width="2"></path> <path d="M14.4436 14.9584C14.7769 15.4584 16.1436 15.8584 16.9436 15.4584C17.7436 15.0584 18.2769 13.2919 18.4436 12.9586M13.4436 13.9584C15.4436 15.4585 16.9436 13.9586 18.4436 11.4584C19.371 9.91249 19.9436 13.4584 17.9436 15.9586C16.4437 17.8336 15.4436 16.9584 12.4436 13.9584H13.4436Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5 5C4.32843 5 5 4.32843 5 3.5C5 2.67157 4.32843 2 3.5 2C2.67157 2 2 2.67157 2 3.5C2 4.32843 2.67157 5 3.5 5Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5 22C4.32843 22 5 21.3284 5 20.5C5 19.6716 4.32843 19 3.5 19C2.67157 19 2 19.6716 2 20.5C2 21.3284 2.67157 22 3.5 22Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 22C21.3284 22 22 21.3284 22 20.5C22 19.6716 21.3284 19 20.5 19C19.6716 19 19 19.6716 19 20.5C19 21.3284 19.6716 22 20.5 22Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 5C21.3284 5 22 4.32843 22 3.5C22 2.67157 21.3284 2 20.5 2C19.6716 2 19 2.67157 19 3.5C19 4.32843 19.6716 5 20.5 5Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12C10 13.1046 10.8954 14 12 14Z" stroke="#292524" stroke-width="2"></path> </g></svg>
                            <span style="color: ${
                                data.is_fan_on == 1 ? '#22c55e' : '#ef4444'
                            }">${fanStatus}</span>`;
                            kwhElement.textContent = `${parseFloat(data.energy).toFixed(4)} kWh`;
                            voltageElement.textContent = `${parseFloat(data.voltage).toFixed(2)} V`;
                            currentElement.textContent = `${parseFloat(data.current).toFixed(2)} A`;
                    } else {
                        // If ESP32 is disconnected, show "--" but keep the SVG
                        temperatureElement.innerHTML = "--°C";
                        fanElement.innerHTML = `
                            <svg class="h-6 w-6 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect x="2" y="2" width="20" height="20" rx="1" stroke="#292524" stroke-width="2"></rect> <rect x="2" y="2" width="20" height="20" rx="10" stroke="#292524" stroke-width="2"></rect> <path d="M14.9999 9.50009C15.4999 9.16675 15.8999 7.80009 15.4999 7.00009C15.0999 6.20009 13.3334 5.66674 13.0001 5.50007M14 10.5001C15.5001 8.5001 14.0001 7.00006 11.5 5.5001C9.95405 4.57263 13.4999 4.00009 16.0001 6.00009C17.8751 7.49994 17 8.50009 14 11.5001V10.5001Z" stroke="#292524" stroke-width="2"></path> <path d="M8.98522 14.402C8.48522 14.7353 8.08522 16.102 8.48522 16.902C8.88522 17.702 10.6517 18.2354 10.9851 18.402M9.98516 13.402C8.48509 15.402 9.98504 16.902 12.4852 18.402C14.0311 19.3295 10.4852 19.902 7.98502 17.902C6.11005 16.4022 6.98516 15.402 9.98516 12.402V13.402Z" stroke="#292524" stroke-width="2"></path> <path d="M9.54159 8.94372C9.20826 8.44372 7.84159 8.04372 7.04159 8.44372C6.24159 8.84372 5.70824 10.6102 5.54157 10.9435M10.5416 9.94366C8.54161 8.44359 7.04156 9.94354 5.54161 12.4437C4.61413 13.9896 4.04159 10.4437 6.04159 7.94352C7.54145 6.06854 8.5416 6.94366 11.5416 9.94366H10.5416Z" stroke="#292524" stroke-width="2"></path> <path d="M14.4436 14.9584C14.7769 15.4584 16.1436 15.8584 16.9436 15.4584C17.7436 15.0584 18.2769 13.2919 18.4436 12.9586M13.4436 13.9584C15.4436 15.4585 16.9436 13.9586 18.4436 11.4584C19.371 9.91249 19.9436 13.4584 17.9436 15.9586C16.4437 17.8336 15.4436 16.9584 12.4436 13.9584H13.4436Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5 5C4.32843 5 5 4.32843 5 3.5C5 2.67157 4.32843 2 3.5 2C2.67157 2 2 2.67157 2 3.5C2 4.32843 2.67157 5 3.5 5Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5 22C4.32843 22 5 21.3284 5 20.5C5 19.6716 4.32843 19 3.5 19C2.67157 19 2 19.6716 2 20.5C2 21.3284 2.67157 22 3.5 22Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 22C21.3284 22 22 21.3284 22 20.5C22 19.6716 21.3284 19 20.5 19C19.6716 19 19 19.6716 19 20.5C19 21.3284 19.6716 22 20.5 22Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 5C21.3284 5 22 4.32843 22 3.5C22 2.67157 21.3284 2 20.5 2C19.6716 2 19 2.67157 19 3.5C19 4.32843 19.6716 5 20.5 5Z" stroke="#292524" stroke-width="2"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12C10 13.1046 10.8954 14 12 14Z" stroke="#292524" stroke-width="2"></path> </g></svg>
                            <span>--</span>`;
                            kwhElement.textContent = "---- kWh";
                            voltageElement.textContent = "-- V";
                            currentElement.textContent = "-- A";
                    }
                })
                .catch(error => console.error('Error fetching status:', error));
        }

        // Function to update thermometer UI
        function updateThermometer(tempValue) {
            const thermometerFill = document.querySelector('.thermometer-fill');
            const temperatureText = document.querySelector('.temperature-value');

            let fillWidth = Math.min(100, Math.max(0, (tempValue / 50) * 100));

            thermometerFill.style.width = `${fillWidth}%`;
            temperatureText.textContent = `${tempValue}°C`;
            temperatureText.style.opacity = fillWidth < 10 ? "0" : "1";

            generateRuler();
        }

        function generateRuler() {
            const ruler = document.querySelector('.thermometer-ruler');
            ruler.innerHTML = '';

            for (let i = 0; i <= 50; i += 10) {
                let mark = document.createElement('span');
                mark.textContent = i;
                mark.style.left = `${(i / 50) * 100}%`;
                ruler.appendChild(mark);
            }
        }

        function fetchESP32Status() {
            fetch('fetch_esp32.php')
                .then(response => response.json())
                .then(data => {
                    const statusElement = document.getElementById('esp32-status');
                    const ssidElement = document.getElementById('wifi-ssid');
                    const ipElement = document.getElementById('esp32-ip');
        
                    if (data.status === "Connected") {
                        statusElement.innerText = "Connected";
                        statusElement.style.color = '#22c55e'; // Green

                        // Show the actual SSID and IP from the database when connected
                        ssidElement.innerText = data.ssid;
                        ipElement.innerText = data.ip;
        
                    } else {
                        statusElement.innerText = "Disconnected";
                        statusElement.style.color = '#ef4444'; // Red;

                        // Hide SSID and IP
                        ssidElement.innerText = "--";
                        ipElement.innerText = "--";
                    }
                })
                .catch(error => console.error('Error fetching ESP32 status:', error));
        }

        let socketChart; // Store chart instance

        function fetchSocketStats() {
            fetch("fetch_socket_stats.php")
                .then(response => response.json())
                .then(data => {
                    updateChart2(data);
                })
                .catch(error => console.error("Error fetching socket data:", error));
        }

        function createDoughnutChart(data) {
            const ctx = document.getElementById('socketChart').getContext('2d');
        
            // Calculate total usage
            const totalUsage = data.s1 + data.s2 + data.s3 + data.s4;
        
            socketChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ["Socket 1", "Socket 2", "Socket 3", "Socket 4"],
                    datasets: [{
                        data: [
                            data.s1.toFixed(2),
                            data.s2.toFixed(2),
                            data.s3.toFixed(2),
                            data.s4.toFixed(2)
                        ],
                        backgroundColor: [
                            "rgba(255, 99, 132, 0.8)",   // Soft Red
                            "rgba(54, 162, 235, 0.8)",   // Soft Blue
                            "rgba(255, 206, 86, 0.8)",   // Soft Yellow
                            "rgba(75, 192, 192, 0.8)"    // Soft Teal
                        ],
                        hoverBackgroundColor: [
                            "rgba(255, 99, 132, 1)",
                            "rgba(54, 162, 235, 1)",
                            "rgba(255, 206, 86, 1)",
                            "rgba(75, 192, 192, 1)"
                        ],
                        borderWidth: 3,
                        borderColor: "#ffffff",
                        cutout: "75%" // Makes the donut thinner for a modern look
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: 10
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: "#292524",
                                font: {
                                    size: 14,
                                    weight: "bold"
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: "#1F2937",
                            titleFont: {
                                size: 14,
                                weight: "bold"
                            },
                            bodyFont: {
                                size: 12
                            },
                            bodyColor: "#FFFFFF",
                            titleColor: "#FACC15",
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(tooltipItem) {
                                    let value = tooltipItem.raw.toFixed(2);
                                    return `${tooltipItem.label}: ${value}%`;
                                }
                            }
                        }
                    }
                },             
            });
        }
        

        function updateChart2(data) {
            if (socketChart) {
                socketChart.data.datasets[0].data = [data.s1, data.s2, data.s3, data.s4];
                socketChart.update(); // Refresh chart data
            } else {
                createDoughnutChart(data);
            }
        }
        
        function toggleDropdown() {
            const dropdown = document.getElementById("dropdownMenu");
            dropdown.classList.toggle("hidden");
        }

        window.onload = function() {
            fetchSensorData();
            fetchChartData();
            fetchStatus();
            fetchESP32Status();
            fetchSocketStats();
        };

        setInterval(fetchLatestStates, 3000);
        setInterval(fetchSensorData, 3000);
        setInterval(fetchChartData, 3000);
        setInterval(fetchStatus, 3000);
        setInterval(fetchESP32Status, 3000);
        setInterval(fetchSocketStats, 3000);