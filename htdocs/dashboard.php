<?php
session_start();
include 'db_connect.php'; 

if (!isset($_SESSION['user'])) {
    header("Location: index.html");
    exit();
}

$current_user = $_SESSION['user'];

// 1. Kunin ang huling 10 records para sa graph at table
// Gagamit tayo ng user_id = 1 (o i-update mo sa session user id kung dynamic na)
$query = "SELECT bpm, intensity, timestamp FROM heart_data WHERE user_id = 1 ORDER BY timestamp DESC LIMIT 10";
$result = mysqli_query($conn, $query);

$bpms = [];
$labels = [];
$historyData = [];

while($row = mysqli_fetch_assoc($result)) {
    $bpms[] = (int)$row['bpm'];
    $labels[] = date("h:i A", strtotime($row['timestamp']));
    $historyData[] = $row; 
}

$chartBpms = array_reverse($bpms);
$chartLabels = array_reverse($labels);

// 2. Kunin ang pinakabagong BPM galing sa database para automatic ang update
$latestBpm = !empty($bpms) ? $bpms[0] : 72; 

// 3. DATABASE RECOMMENDATION LOGIC
// Dito magbabase ang "Automatic" update ng exercises pagka-redirect
$category = "Low";
if ($latestBpm >= 140) { 
    $category = "High"; 
} elseif ($latestBpm >= 100) { 
    $category = "Medium"; 
} else {
    $category = "Low";
}

// Kunin ang exercises mula sa database base sa category na na-detect
$recQuery = "SELECT * FROM exercise_recommendation WHERE category = '$category'";
$recResult = mysqli_query($conn, $recQuery);

$dbExercises = [];
while($row = mysqli_fetch_assoc($recResult)) {
    $dbExercises[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YANIG - Intensity Guide</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

    <nav>
        <div class="logo"><span>❤️</span> YANIG</div>
        <div class="user-info" style="color: white; margin-left: auto; padding-right: 20px;">
            Mabuhay, <?php echo htmlspecialchars($current_user); ?>!
        </div>
        <a href="profile.php" class="account-link">My Account</a>
    </nav>

    <div class="container">
        <header style="margin-bottom: 25px;">
            <h1 style="font-weight: 800; font-size: 32px;">Activity Dashboard</h1>
            <p style="color: #64748b;">Heart rate analysis with verified video-guided recovery.</p>
        </header>

        <section class="hero-card">
            <h2>Analyze My Current State</h2>
            <form action="save_heart_rate.php" method="POST" class="input-group">
                <div class="input-box">
                    <input type="number" id="bpmInput" name="bpm_value" value="<?php echo $latestBpm; ?>" placeholder="BPM" min="30" max="220" required>
                </div>
                <div class="input-box">
                    <select id="genderInput" name="gender">
                        <option value="male">Male Style</option>
                        <option value="female">Female Style</option>
                    </select>
                </div>
                <button type="submit" name="check_intensity" class="btn-calc">Check Intensity</button>
            </form>
        </section>

        <div class="dashboard-grid">
            <div class="card">
                <h3 style="margin-bottom: 15px;">Heart Rate Monitoring</h3>
                <div style="height: 200px;"><canvas id="hrChart"></canvas></div>
            </div>
            <div class="stats-col">
                <div class="card stat-card">
                    <div class="stat-icon reading-icon">📊</div>
                    <div>
                        <div class="stat-label">CURRENT BPM</div>
                        <div class="stat-val" id="avgBpm"><?php echo $latestBpm; ?></div>
                    </div>
                </div>
                <div class="card stat-card" id="statusCard">
                    <div class="stat-icon status-icon-box" id="statusIcon">✅</div>
                    <div>
                        <div class="stat-label">ZONE</div>
                        <div class="stat-val" id="zoneTxt"><?php 
                            if($latestBpm >= 140) echo "Peak";
                            elseif($latestBpm >= 100) echo "Aerobic";
                            else echo "Optimal";
                        ?></div>
                    </div>
                </div>
            </div>
        </div>

        <h3 style="margin-bottom: 20px;">Personalized Exercises (From Database)</h3>
        <div class="rec-grid" id="recGrid">
            <?php if (!empty($dbExercises)): ?>
                <?php foreach($dbExercises as $ex): ?>
                    <div class="card rec-card" style="cursor: pointer;" onclick="openModalWithData('<?php echo addslashes($ex['exercise_name']); ?>', '<?php echo $ex['category']; ?>', '<?php echo addslashes($ex['description']); ?>')">
                        <span class="tag"><?php echo htmlspecialchars($ex['category']); ?></span>
                        <div style="font-size:32px; margin: 10px 0;">🏃‍♂️</div>
                        <h3 style="margin:0;"><?php echo htmlspecialchars($ex['exercise_name']); ?></h3>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 5px 0;"><?php echo htmlspecialchars($ex['description']); ?></p>
                        <small><b>⏱ Duration:</b> <?php echo htmlspecialchars($ex['duration']); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #64748b;">No recommendations found in the database for this zone. Please add data to 'exercise_recommendation' table.</p>
            <?php endif; ?>
        </div>

        <section class="history-section">
            <h3 style="margin: 30px 0 15px 0;">Activity History</h3>
            <div class="card history-card">
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>BPM</th>
                            <th>Zone</th>
                            <th>Mode</th>
                        </tr>
                    </thead>
                    <tbody id="historyBody">
                        <?php foreach($historyData as $row): ?>
                        <tr>
                            <td><?php echo date("h:i A", strtotime($row['timestamp'])); ?></td>
                            <td><b><?php echo $row['bpm']; ?></b></td>
                            <td><span class="tag"><?php echo $row['intensity']; ?></span></td>
                            <td>Live</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="modal-overlay" id="exerciseModal" style="display:none;">
        <div class="modal-body">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div id="videoContainer" class="video-wrapper"></div>
            <h2 id="modalTitle" style="font-weight: 800; margin-top: 20px;"></h2>
            <span id="modalTag" class="tag"></span>
            <p id="modalDesc" style="line-height: 1.6; color: #475569; margin-top: 15px;"></p>
        </div>
    </div>

    <script src="script.js"></script>

    <script>
        const phpLabels = <?php echo json_encode($chartLabels); ?>;
        const phpBpms = <?php echo json_encode($chartBpms); ?>;
        const latestBpmFromPHP = <?php echo $latestBpm; ?>;

        const ctx = document.getElementById('hrChart').getContext('2d');
        let hrChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: phpLabels,
                datasets: [{
                    label: 'Heart Rate',
                    data: phpBpms,
                    borderColor: '#ff4d4d',
                    backgroundColor: 'rgba(255, 77, 77, 0.1)',
                    fill: true, 
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { min: 40, max: 220 } },
                plugins: { legend: { display: false } }
            }
        });

        // Ito ang mag-aayos ng kulay at icons automatic base sa BPM
        window.addEventListener('load', function() {
            if(typeof updateDashboardFromPHP === 'function') {
                updateDashboardFromPHP(latestBpmFromPHP);
            }
        });

        // Helper function para sa modal gamit ang DB data
        function openModalWithData(title, tag, desc) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalTag').innerText = tag;
            document.getElementById('modalDesc').innerText = desc;
            document.getElementById('exerciseModal').style.display = 'flex';
            // Pwede mo rin dagdagan dito ang logic para sa YouTube Video ID
        }
    </script>
</body>
</html>