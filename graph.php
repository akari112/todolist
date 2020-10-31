<?php 
session_start();
require('.db.php');
require('function.php');
header('X-FRAME-OPTIONS:DENY');
date_default_timezone_set("Asia/Tokyo");

first();
$id = $_SESSION['id'];
// ユーザー情報
if($id) {
  $users = $db->prepare('SELECT * FROM user WHERE id=?');
  $users->execute(array($id));
  $user = $users->fetch();
}

$graphs = $db->prepare("SELECT points, day FROM graph WHERE user=? ORDER BY day ASC LIMIT 30");
$graphs->bindParam(1,$id,PDO::PARAM_INT);
$graphs->execute();
$graph = $graphs->fetchAll();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <link rel="stylesheet" href="main.css"/>
	<title>ToDoList</title>
</head>
<body>
<header>
  <div class="points">
    <p class="head_p"><i class="fas fa-coins"></i><?php echo $user['points'];?>p</p>
  </div>
  <h1>Todolist</h1>
  <div class="big">
    <a class="graph_icon" href="graph.php"><i class="fas fa-chart-line"></i></a>
    <p><a href="change.php">変更</a></p>
    <p class="head_log"><a href="logout.php">ログアウト</a></p>
  </div>
  <div class="mini">
    <a class="graph_icon" href="graph.php"><i class="fas fa-chart-line"></i></a>
    <a class="graph_icon" href="change.php"><i class="fas fa-exchange-alt"></i></a>
    <a class="graph_icon" href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</header>

<div class="ran">
  <a class="" href="daily.php">日課</a>
  <a class="" href="main.php">ToDoリスト</a>
  <a class="" href="reward.php">ご褒美</a>
</div>

<div class="main">
  <div class="days">
    <h1>30Days Graph</h1>
    <canvas id="myChart"></canvas>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script>
var data_array = <?php echo json_encode($graph); ?>;

name_array = [];
count_array = [];
for(key in data_array){
  name_array.push(data_array[key][0]);
  count_array.push(data_array[key][1]);
}

var ctx = document.getElementById("myChart");
  var myBarChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: count_array,
      datasets: [
        {
          label: 'ポイント',
          data: name_array,
          backgroundColor: '#7fcfff',
          borderColor: '#7fcfff',
          fill: false,
          lineTension: 0,
          pointBackgroundColor: '#7fcfff',
          pointHoverRadius: 3
        }
      ]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            suggestedMax: 50,
            suggestedMin: 0,
          }
        }]
      },
    }
  });

</script>
<script src="main.js"></script>
</body>
</html>
