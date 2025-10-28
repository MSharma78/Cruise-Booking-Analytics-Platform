<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relational Model Visualization</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
            color: #333;
        }
        header {
            background: #007bff;
            color: white;
            padding: 1rem 0;
            text-align: center;
        }
        .container {
            width: 90%;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1, h2 {
            text-align: center;
            color: #007bff;
        }
        canvas {
            margin: 2rem auto;
            display: block;
            max-width: 100%;
        }
        footer {
            text-align: center;
            padding: 1rem 0;
            background: #007bff;
            color: white;
            margin-top: 2rem;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Relational Model Visualization</h1>
    </header>

    <div class="container">
        <h2>Number of Bookings by Trip</h2>
        <canvas id="bookingsChart"></canvas>

        <?php
        include 'db_connect.php';

        $query = "SELECT t.TRIP_ID, COUNT(b.BOOKING_ID) as booking_count FROM EAM_TRIP t 
                  LEFT JOIN EAM_BOOKING b ON t.TRIP_ID = b.TRIP_ID 
                  GROUP BY t.TRIP_ID";
        $result = $conn->query($query);

        $trip_ids = [];
        $booking_counts = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $trip_ids[] = $row['TRIP_ID'];
                $booking_counts[] = $row['booking_count'];
            }
        } else {
            echo "<p>No data available to visualize.</p>";
        }
        ?>

        <script>
            const tripIds = <?php echo json_encode($trip_ids); ?>;
            const bookingCounts = <?php echo json_encode($booking_counts); ?>;

            const ctx = document.getElementById('bookingsChart').getContext('2d');
            const bookingsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: tripIds,
                    datasets: [{
                        label: 'Number of Bookings',
                        data: bookingCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Trip Bookings Overview'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>

    <div class="container">
        <h2>Revenue by Port</h2>
        <canvas id="revenueChart"></canvas>

        <?php
        $query = "SELECT p.PR_NAME, SUM(b.DISCOUNT) as total_revenue FROM EAM_PORT p 
                  JOIN EAM_TRIP t ON p.PORT_ID = t.START_PORT_ID 
                  JOIN EAM_BOOKING b ON t.TRIP_ID = b.TRIP_ID 
                  GROUP BY p.PR_NAME";
        $result = $conn->query($query);

        $port_names = [];
        $revenues = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $port_names[] = $row['PR_NAME'];
                $revenues[] = $row['total_revenue'];
            }
        } else {
            echo "<p>No data available to visualize.</p>";
        }
        ?>

        <script>
            const portNames = <?php echo json_encode($port_names); ?>;
            const revenues = <?php echo json_encode($revenues); ?>;

            const ctx2 = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: portNames,
                    datasets: [{
                        label: 'Total Revenue',
                        data: revenues,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Revenue Distribution by Port'
                        }
                    }
                }
            });
        </script>
    </div>

    <div class="container">
        <h2>Passenger Demographics</h2>
        <canvas id="passengerChart"></canvas>

        <?php
        $query = "SELECT P_NATIONALITY, COUNT(PASSENGER_ID) as passenger_count FROM EAM_PASSENGER 
                  GROUP BY P_NATIONALITY";
        $result = $conn->query($query);

        $nationalities = [];
        $passenger_counts = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $nationalities[] = $row['P_NATIONALITY'];
                $passenger_counts[] = $row['passenger_count'];
            }
        } else {
            echo "<p>No data available to visualize.</p>";
        }
        ?>

        <script>
            const nationalities = <?php echo json_encode($nationalities); ?>;
            const passengerCounts = <?php echo json_encode($passenger_counts); ?>;

            const ctx3 = document.getElementById('passengerChart').getContext('2d');
            const passengerChart = new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    labels: nationalities,
                    datasets: [{
                        label: 'Passenger Count',
                        data: passengerCounts,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Passenger Demographics by Nationality'
                        }
                    }
                }
            });
        </script>
    </div>

    <div class="container">
        <h2>Average Package Price by Type</h2>
        <canvas id="packageChart"></canvas>

        <?php
        $query = "SELECT PACKAGE_NAME, AVG(UNIT_PRICE) as avg_price FROM EAM_PACKAGE GROUP BY PACKAGE_NAME";
        $result = $conn->query($query);

        $package_names = [];
        $avg_prices = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $package_names[] = $row['PACKAGE_NAME'];
                $avg_prices[] = $row['avg_price'];
            }
        } else {
            echo "<p>No data available to visualize.</p>";
        }
        ?>

        <script>
            const packageNames = <?php echo json_encode($package_names); ?>;
            const avgPrices = <?php echo json_encode($avg_prices); ?>;

            const ctx4 = document.getElementById('packageChart').getContext('2d');
            const packageChart = new Chart(ctx4, {
                type: 'line',
                data: {
                    labels: packageNames,
                    datasets: [{
                        label: 'Average Price',
                        data: avgPrices,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Average Package Price by Type'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>

    <div class="container">
        <h2>Payment Methods Used</h2>
        <canvas id="paymentChart"></canvas>

        <?php
        $query = "SELECT PMT_METHOD, COUNT(PMT_ID) as method_count FROM EAM_PAYMENT 
                  GROUP BY PMT_METHOD";
        $result = $conn->query($query);

        $payment_methods = [];
        $method_counts = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $payment_methods[] = $row['PMT_METHOD'];
                $method_counts[] = $row['method_count'];
            }
        } else {
            echo "<p>No data available to visualize.</p>";
        }
        ?>

        <script>
            const paymentMethods = <?php echo json_encode($payment_methods); ?>;
            const methodCounts = <?php echo json_encode($method_counts); ?>;

            const ctx5 = document.getElementById('paymentChart').getContext('2d');
            const paymentChart = new Chart(ctx5, {
                type: 'polarArea',
                data: {
                    labels: paymentMethods,
                    datasets: [{
                        label: 'Payment Methods',
                        data: methodCounts,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Payment Methods Used'
                        }
                    }
                }
            });
        </script>
    </div>

    <div class="container">
        <h2>Activity Usage Overview</h2>
        <canvas id="activityChart"></canvas>

        <?php
        $query = "SELECT A_NAME, COUNT(SERVICE_ID) as usage_count FROM EAM_ACTIVITY 
                  JOIN EAM_CRUISE_SERVICES ON EAM_ACTIVITY.ACTIVITY_ID = EAM_CRUISE_SERVICES.ACTIVITY_ID 
                  GROUP BY A_NAME";
        $result = $conn->query($query);

        $activity_names = [];
        $activity_counts = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $activity_names[] = $row['A_NAME'];
                $activity_counts[] = $row['usage_count'];
            }
        } else {
            echo "<p>No data available to visualize.</p>";
        }
        ?>

        <script>
            const activityNames = <?php echo json_encode($activity_names); ?>;
            const activityCounts = <?php echo json_encode($activity_counts); ?>;

            const ctx6 = document.getElementById('activityChart').getContext('2d');
            const activityChart = new Chart(ctx6, {
                type: 'radar',
                data: {
                    labels: activityNames,
                    datasets: [{
                        label: 'Activity Usage',
                        data: activityCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Activity Usage Overview'
                        }
                    },
                    scales: {
                        r: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>

    <footer>
        <p>&copy; 2024 NICE Cruise Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
