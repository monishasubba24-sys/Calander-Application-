<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
if(!isAdmin()) die("Access denied");

// Fetch all events
$events = $pdo->query("SELECT e.id, e.title, e.start_time, e.end_time, u.username 
                       FROM events e JOIN users u ON e.user_id = u.id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Interval Tree Visualizer</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/interval_tree.js"></script>
</head>
<body>
<div class="container mt-4">
<h2>Interval Tree Visualizer</h2>
<canvas id="treeChart" height="400"></canvas>

<script>
// Build interval tree and prepare chart data
const tree = new IntervalTree();
const events = <?php echo json_encode($events); ?>;

events.forEach(ev => {
    tree.insert(new Date(ev.start_time).getTime(), new Date(ev.end_time).getTime(), ev);
});

// Visualize overlaps
const labels = events.map(e => e.title);
const data = events.map(e => tree.search(new Date(e.start_time).getTime(), new Date(e.end_time).getTime()).length);

const ctx = document.getElementById('treeChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Number of overlapping events',
            data: data,
            backgroundColor: 'rgba(54, 162, 235, 0.6)'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true, precision:0 }
        }
    }
});
</script>
<a href="index.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
