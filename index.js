var ctx = document.getElementById('chartCanvas').getContext('2d');
        var myChart;

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

        function sendState(pin, state) {
            const toggleSwitch = event.target; // Get the exact toggle switch being interacted with
            const previousState = !state; // Store the previous state before request
        
            // Send state change to the server
            fetch(`http://192.168.0.128/control?pin=${pin}&state=${state}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(feedback => {
                    console.log(feedback); // Log feedback from server
        
                    // Update the socket status UI only if the request succeeds
                    const socketStatus = document.querySelector(`#socket-${pin}-status`);
                    if (state === 1) {
                        socketStatus.innerHTML = 'ON';
                        socketStatus.style.color = '#22c55e'; // Green for ON
                    } else {
                        socketStatus.innerHTML = 'OFF';
                        socketStatus.style.color = '#ef4444'; // Red for OFF
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(`Failed to update outlet. ${error.message}`);
        
                    // Ensure the toggle switch properly reverts after the alert
                    setTimeout(() => {
                        toggleSwitch.checked = previousState; // Revert toggle switch state
                    }, 100);
                });
        }
                            

        function updateTable(data) {
            var sensorDataTable = document.getElementById("sensorData");
            sensorDataTable.innerHTML = "";

            data.forEach(item => {
                var row = document.createElement("tr");
                row.innerHTML = `
                    <td class='py-2 px-4'>${item.sensor_name}</td>
                    <td class='py-2 px-4'>${item.temp}</td>
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
                    updateChart(data, sensorTitle);
                }
            };
            xhr.open("GET", "fetch_chart_data.php", true);
            xhr.send();
        }

        function updateChart(data, sensorTitle) {
            var labels = data.map(item => item.id);
            var tempData = data.map(item => item.temp);

            // If the chart already exists, destroy it before creating a new one
            if (window.myChart) {
                window.myChart.destroy();
            }

            // Get the canvas context
            var ctx = document.getElementById('chartCanvas').getContext('2d');

            // Create a gradient for the line
            var gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(75, 192, 192, 1)'); // Starting color
            gradient.addColorStop(1, 'rgba(75, 192, 192, 0)'); // Fading color

            // Create the chart
            window.myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Temperature (°C)',
                        data: tempData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: gradient,
                        fill: true, // Fill area under the curve
                        tension: 0.4, // Make the line more wavy
                        pointRadius: 4, // Highlight points
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                        hoverBorderColor: 'rgba(0, 128, 255, 1)', // Add hover interaction for points
                        hoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1500, // Smooth fade duration
                        easing: 'easeOutQuart', // Easing for smooth transitions
                        animations: {
                            opacity: {
                                from: 0, // Fade in from 0 opacity
                                to: 1,
                                easing: 'easeOutQuart',
                                duration: 1500
                            },
                            y: {
                                easing: 'easeOutQuart',
                                duration: 1500
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false // Highlight nearest data point
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: sensorTitle,
                            font: {
                                size: 18,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: 'rgba(0,0,0,0.7)', // Dark tooltip background
                            bodyFont: {
                                size: 14
                            },
                            titleFont: {
                                size: 16
                            },
                            padding: 10,
                            callbacks: {
                                label: function (context) {
                                    return `Temp: ${context.raw}°C`; // Customize tooltip text
                                }
                            }
                        },
                        legend: {
                            display: true,
                            labels: {
                                usePointStyle: true, // Use circular point styles
                                font: {
                                    size: 14
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Sensor ID',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                display: false // Remove x-axis grid lines
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Temperature (°C)',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: 'rgba(200, 200, 200, 0.5)' // Light gray grid lines
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }
                    }
                }
            });
        }

        function fetchStatus() {
            // Send an AJAX request to fetch the latest status
            fetch('fetch_status.php')
                .then(response => response.json())
                .then(data => {
                    const temperatureElement = document.querySelector('.temp-status');
                    const fanElement = document.querySelector('.fan-status');
        
                    if (data.status === "Connected") {
                        // Update temperature with dynamic color
                        const tempValue = data.temp;
                        temperatureElement.innerHTML = `Temperature: <span style="color: ${
                            tempValue >= 30 ? '#ef4444' : // Red for temp >= 30
                            tempValue >= 20 ? '#f97316' : // Orange for temp 20-29
                            tempValue >= 10 ? '#3b82f6' : // Blue for temp 10-19
                            '#60a5fa' // Lighter blue for temp < 10
                        }">${tempValue} °C</span>`;
        
                        // Update fan status with dynamic color
                        const fanStatus = data.is_fan_on == 1 ? 'ON' : 'OFF';
                        fanElement.innerHTML = `Fan: <span style="color: ${
                            data.is_fan_on == 1 ? '#22c55e' : '#ef4444'
                        }">${fanStatus}</span>`;
                    } else {
                        // If ESP32 is disconnected, show placeholder values
                        temperatureElement.innerHTML = "Temperature: --";
                        fanElement.innerHTML = "Fan: --";
                    }
                })
                .catch(error => console.error('Error fetching status:', error));
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
             
        
        function toggleDropdown() {
            const dropdown = document.getElementById("dropdownMenu");
            dropdown.classList.toggle("hidden");
        }

        window.onload = function() {
            fetchSensorData();
            fetchChartData();
            fetchStatus();
            fetchESP32Status();
        };

        setInterval(fetchSensorData, 5000);
        setInterval(fetchChartData, 5000);
        setInterval(fetchStatus, 5000);
        setInterval(fetchESP32Status, 5000);