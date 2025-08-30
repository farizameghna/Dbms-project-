<?php
include 'db.php';
function fetch_rows($conn, $table) {
    $res = mysqli_query($conn, "SELECT * FROM `$table` ORDER BY id DESC");
    $rows = [];
    while($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    return $rows;
}
$livestock = fetch_rows($conn, 'livestock');
$processing = fetch_rows($conn, 'processing');
$storage = fetch_rows($conn, 'storage');
$stock = fetch_rows($conn, 'stock');
$fifo = fetch_rows($conn, 'fifo');
$sensor = fetch_rows($conn, 'sensor');
$yields = fetch_rows($conn, 'yields');
$recall = fetch_rows($conn, 'recall');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Meat Inventory Full Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6f9; margin: 0;}
    header { background: #9b1c1c; color: white; padding: 1rem; text-align: center;}
    nav { display: flex; flex-wrap: wrap; justify-content: center; background: #b63232;}
    nav a { padding: 0.8rem 1rem; color: white; text-decoration: none; font-weight: bold; transition: background 0.3s; cursor: pointer;}
    nav a:hover { background: #9b1c1c;}
    .charts { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; padding: 1rem;}
    .chart-container { background: white; padding: 1rem; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);}
    canvas { max-height: 180px;}
    h2 { color: #9b1c1c; text-align: center; font-size: 16px; margin: 10px 0;}
    .tab-content { display: none; padding: 1rem 2rem;}
    .active { display: block;}
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 2rem;}
    th, td { padding: 0.8rem; border: 1px solid #ddd; text-align: center;}
    th { background-color: #b63232; color: white;}
    tr:nth-child(even) { background-color: #f9f9f9;}
    button { margin: 0.2rem; padding: 0.4rem 0.6rem; cursor: pointer;}
    form.inline { display: inline-block; margin:0; }
    .add-form { margin: 0.5rem 0; background: #fff; padding: 0.6rem; border-radius: 6px; box-shadow: 0 1px 4px rgba(0,0,0,0.06);}
    input[type="text"], input[type="date"], input[type="number"] { padding: 6px; margin-right:6px; }
  </style>
</head>
<body>

<header>
  <h1>Meat Inventory Dashboard</h1>
  <p>Tracking from Processing to Distribution</p>
</header>

<nav>
  <a onclick="showTab('dashboard')">Dashboard</a>
  <a onclick="showTab('livestock')">Livestock</a>
  <a onclick="showTab('processing')">Processing</a>
  <a onclick="showTab('storage')">Storage</a>
  <a onclick="showTab('stock')">Stock</a>
  <a onclick="showTab('fifo')">FIFO/FEFO</a>
  <a onclick="showTab('sensor')">Sensors</a>
  <a onclick="showTab('yields')">Yields</a>
  <a onclick="showTab('recall')">Trace & Recall</a>
</nav>

<!-- Dashboard -->
<div id="dashboard" class="tab-content active">
  <div class="charts">
    <div class="chart-container">
      <h2>Meat Product Types</h2>
      <canvas id="meatPieChart"></canvas>
    </div>
    <div class="chart-container">
      <h2>Stock & Spoilage Trend</h2>
      <canvas id="stockLineChart"></canvas>
    </div>
  </div>
</div>

<!-- Livestock -->
<div id="livestock" class="tab-content">
  <h2>Livestock Intake</h2>

  <div class="add-form">
    <form action="save.php" method="post">
      <input type="hidden" name="table" value="livestock">
      <input type="text" name="animal" placeholder="Animal" required>
      <input type="date" name="date" required>
      <input type="number" name="qty" placeholder="Qty" required>
      <input type="text" name="farm" placeholder="Farm">
      <input type="text" name="status" placeholder="Status">
      <button type="submit">Add</button>
    </form>
  </div>

  <table id="livestockTable">
    <thead>
      <tr><th>Animal</th><th>Date</th><th>Qty</th><th>Farm</th><th>Status</th><th>Action</th></tr>
    </thead>
    <tbody>
<?php foreach($livestock as $row): ?>
      <tr>
        <td><?=htmlspecialchars($row['animal'])?></td>
        <td><?=htmlspecialchars($row['date'])?></td>
        <td><?=htmlspecialchars($row['qty'])?></td>
        <td><?=htmlspecialchars($row['farm'])?></td>
        <td><?=htmlspecialchars($row['status'])?></td>
        <td>
          <form class="inline" action="edit.php" method="get"><input type="hidden" name="table" value="livestock"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Edit</button></form>
          <form class="inline" action="delete.php" method="post" onsubmit="return confirm('Delete this row?')"><input type="hidden" name="table" value="livestock"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Delete</button></form>
        </td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Processing -->
<div id="processing" class="tab-content">
  <h2>Processing</h2>
  <div class="add-form">
    <form action="save.php" method="post">
      <input type="hidden" name="table" value="processing">
      <input type="text" name="batch" placeholder="Batch" required>
      <input type="text" name="animal" placeholder="Animal">
      <input type="text" name="cut" placeholder="Cut">
      <input type="date" name="date">
      <input type="text" name="operator" placeholder="Operator">
      <input type="text" name="status" placeholder="Status">
      <button type="submit">Add</button>
    </form>
  </div>
  <table id="processingTable">
    <thead>
      <tr><th>Batch</th><th>Animal</th><th>Cut</th><th>Date</th><th>Operator</th><th>Status</th><th>Action</th></tr>
    </thead>
    <tbody>
<?php foreach($processing as $row): ?>
      <tr>
        <td><?=htmlspecialchars($row['batch'])?></td>
        <td><?=htmlspecialchars($row['animal'])?></td>
        <td><?=htmlspecialchars($row['cut'])?></td>
        <td><?=htmlspecialchars($row['date'])?></td>
        <td><?=htmlspecialchars($row['operator'])?></td>
        <td><?=htmlspecialchars($row['status'])?></td>
        <td>
          <form class="inline" action="edit.php" method="get"><input type="hidden" name="table" value="processing"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Edit</button></form>
          <form class="inline" action="delete.php" method="post" onsubmit="return confirm('Delete this row?')"><input type="hidden" name="table" value="processing"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Delete</button></form>
        </td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Storage -->
<div id="storage" class="tab-content">
  <h2>Storage Conditions</h2>
  <div class="add-form">
    <form action="save.php" method="post">
      <input type="hidden" name="table" value="storage">
      <input type="text" name="unit" placeholder="Unit" required>
      <input type="number" step="0.1" name="temp" placeholder="Temp">
      <input type="number" step="0.1" name="humidity" placeholder="Humidity">
      <input type="text" name="status" placeholder="Status">
      <button type="submit">Add</button>
    </form>
  </div>
  <table id="storageTable">
    <thead>
      <tr><th>Unit</th><th>Temp (°C)</th><th>Humidity (%)</th><th>Status</th><th>Action</th></tr>
    </thead>
    <tbody>
<?php foreach($storage as $row): ?>
      <tr>
        <td><?=htmlspecialchars($row['unit'])?></td>
        <td><?=htmlspecialchars($row['temp'])?></td>
        <td><?=htmlspecialchars($row['humidity'])?></td>
        <td><?=htmlspecialchars($row['status'])?></td>
        <td>
          <form class="inline" action="edit.php" method="get"><input type="hidden" name="table" value="storage"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Edit</button></form>
          <form class="inline" action="delete.php" method="post" onsubmit="return confirm('Delete this row?')"><input type="hidden" name="table" value="storage"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Delete</button></form>
        </td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Stock -->
<div id="stock" class="tab-content">
  <h2>Stock Levels</h2>
  <div class="add-form">
    <form action="save.php" method="post">
      <input type="hidden" name="table" value="stock">
      <input type="text" name="product" placeholder="Product" required>
      <input type="text" name="batch" placeholder="Batch">
      <input type="number" step="0.01" name="qty" placeholder="Qty (kg)">
      <input type="text" name="unit" placeholder="Unit">
      <input type="date" name="expiry" placeholder="Expiry">
      <button type="submit">Add</button>
    </form>
  </div>
  <table id="stockTable">
    <thead>
      <tr><th>Product</th><th>Batch</th><th>Qty (kg)</th><th>Unit</th><th>Expiry</th><th>Action</th></tr>
    </thead>
    <tbody>
<?php foreach($stock as $row): ?>
      <tr>
        <td><?=htmlspecialchars($row['product'])?></td>
        <td><?=htmlspecialchars($row['batch'])?></td>
        <td><?=htmlspecialchars($row['qty'])?></td>
        <td><?=htmlspecialchars($row['unit'])?></td>
        <td><?=htmlspecialchars($row['expiry'])?></td>
        <td>
          <form class="inline" action="edit.php" method="get"><input type="hidden" name="table" value="stock"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Edit</button></form>
          <form class="inline" action="delete.php" method="post" onsubmit="return confirm('Delete this row?')"><input type="hidden" name="table" value="stock"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Delete</button></form>
        </td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- FIFO -->
<div id="fifo" class="tab-content">
  <h2>FIFO / FEFO Rotation</h2>
  <div class="add-form">
    <form action="save.php" method="post">
      <input type="hidden" name="table" value="fifo">
      <input type="text" name="product" placeholder="Product" required>
      <input type="text" name="batch" placeholder="Batch">
      <input type="date" name="entry_date" placeholder="Entry Date">
      <input type="date" name="expiry" placeholder="Expiry">
      <input type="text" name="rotation" placeholder="Rotation (FIFO/FEFO)">
      <button type="submit">Add</button>
    </form>
  </div>
  <table id="fifoTable">
    <thead>
      <tr><th>Product</th><th>Batch</th><th>Entry Date</th><th>Expiry</th><th>Rotation</th><th>Action</th></tr>
    </thead>
    <tbody>
<?php foreach($fifo as $row): ?>
      <tr>
        <td><?=htmlspecialchars($row['product'])?></td>
        <td><?=htmlspecialchars($row['batch'])?></td>
        <td><?=htmlspecialchars($row['entry_date'])?></td>
        <td><?=htmlspecialchars($row['expiry'])?></td>
        <td><?=htmlspecialchars($row['rotation'])?></td>
        <td>
          <form class="inline" action="edit.php" method="get"><input type="hidden" name="table" value="fifo"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Edit</button></form>
          <form class="inline" action="delete.php" method="post" onsubmit="return confirm('Delete this row?')"><input type="hidden" name="table" value="fifo"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Delete</button></form>
        </td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Sensor -->
<div id="sensor" class="tab-content">
  <h2>Sensor Data</h2>
  <div class="add-form">
    <form action="save.php" method="post">
      <input type="hidden" name="table" value="sensor">
      <input type="text" name="sensor" placeholder="Sensor ID" required>
      <input type="text" name="location" placeholder="Location">
      <input type="text" name="temp" placeholder="Temp">
      <input type="text" name="humidity" placeholder="Humidity">
      <input type="datetime-local" name="last_update" placeholder="Last Update">
      <button type="submit">Add</button>
    </form>
  </div>
  <table id="sensorTable">
    <thead>
      <tr><th>Sensor</th><th>Location</th><th>Temp</th><th>Humidity</th><th>Last Update</th><th>Action</th></tr>
    </thead>
    <tbody>
<?php foreach($sensor as $row): ?>
      <tr>
        <td><?=htmlspecialchars($row['sensor'])?></td>
        <td><?=htmlspecialchars($row['location'])?></td>
        <td><?=htmlspecialchars($row['temp'])?></td>
        <td><?=htmlspecialchars($row['humidity'])?></td>
        <td><?=htmlspecialchars($row['last_update'])?></td>
        <td>
          <form class="inline" action="edit.php" method="get"><input type="hidden" name="table" value="sensor"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Edit</button></form>
          <form class="inline" action="delete.php" method="post" onsubmit="return confirm('Delete this row?')"><input type="hidden" name="table" value="sensor"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Delete</button></form>
        </td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Yields -->
<div id="yields" class="tab-content">
  <h2>Yield Data</h2>
  <div class="add-form">
    <form action="save.php" method="post">
      <input type="hidden" name="table" value="yields">
      <input type="text" name="batch" placeholder="Batch" required>
      <input type="text" name="total_weight" placeholder="Total Weight">
      <input type="text" name="meat" placeholder="Meat">
      <input type="text" name="trimmings" placeholder="Trimmings">
      <input type="text" name="offal" placeholder="Offal">
      <button type="submit">Add</button>
    </form>
  </div>
  <table id="yieldsTable">
    <thead>
      <tr><th>Batch</th><th>Total Weight</th><th>Meat</th><th>Trimmings</th><th>Offal</th><th>Action</th></tr>
    </thead>
    <tbody>
<?php foreach($yields as $row): ?>
      <tr>
        <td><?=htmlspecialchars($row['batch'])?></td>
        <td><?=htmlspecialchars($row['total_weight'])?></td>
        <td><?=htmlspecialchars($row['meat'])?></td>
        <td><?=htmlspecialchars($row['trimmings'])?></td>
        <td><?=htmlspecialchars($row['offal'])?></td>
        <td>
          <form class="inline" action="edit.php" method="get"><input type="hidden" name="table" value="yields"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Edit</button></form>
          <form class="inline" action="delete.php" method="post" onsubmit="return confirm('Delete this row?')"><input type="hidden" name="table" value="yields"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Delete</button></form>
        </td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Recall -->
<div id="recall" class="tab-content">
  <h2>Trace & Recall</h2>
  <div class="add-form">
    <form action="save.php" method="post">
      <input type="hidden" name="table" value="recall">
      <input type="text" name="batch" placeholder="Batch" required>
      <input type="text" name="issue" placeholder="Issue">
      <input type="date" name="date" placeholder="Date">
      <input type="text" name="status" placeholder="Status">
      <button type="submit">Add</button>
    </form>
  </div>
  <table id="recallTable">
    <thead>
      <tr><th>Batch</th><th>Issue</th><th>Date</th><th>Status</th><th>Action</th></tr>
    </thead>
    <tbody>
<?php foreach($recall as $row): ?>
      <tr>
        <td><?=htmlspecialchars($row['batch'])?></td>
        <td><?=htmlspecialchars($row['issue'])?></td>
        <td><?=htmlspecialchars($row['date'])?></td>
        <td><?=htmlspecialchars($row['status'])?></td>
        <td>
          <form class="inline" action="edit.php" method="get"><input type="hidden" name="table" value="recall"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Edit</button></form>
          <form class="inline" action="delete.php" method="post" onsubmit="return confirm('Delete this row?')"><input type="hidden" name="table" value="recall"><input type="hidden" name="id" value="<?=$row['id']?>"><button>Delete</button></form>
        </td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- JS for Tab, Charts -->
<script>
  function showTab(id) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.getElementById(id).classList.add('active');
  }

  // Charts - basic demo using data from server-side counts
  const pieCtx = document.getElementById('meatPieChart').getContext('2d');
  // counts from PHP (simple)
  const counts = {
    beef: <?=count($stock)?>,
    poultry: 30,
    pork: 25,
    lamb: 10
  };
  new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: ['Beef', 'Poultry', 'Pork', 'Lamb'],
      datasets: [{ data: [counts.beef, counts.poultry, counts.pork, counts.lamb] }]
    }
  });

  const lineCtx = document.getElementById('stockLineChart').getContext('2d');
  new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
      datasets: [{ label: 'Stock (kg)', data: [800,750,720,690,710], fill:false }, { label:'Spoiled (kg)', data:[20,25,30,28,22], fill:false }]
    }
  });
</script>

</body>
</html>
