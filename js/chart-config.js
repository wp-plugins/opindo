google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
  var jsonData = document.getElementById('opindo-answer-data').value;

  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Answer');
  data.addColumn('number', 'Percentage');
  data.addRows(JSON.parse(jsonData));

  var options = {
    legend: 'none',
    title: '',
    pieHole: 0.6,
    colors: ['#5c6cff', '#434b6c', '#696f89', '#8e93a7', '#b4b7c4']
  };

  var chart = new google.visualization.PieChart(document.getElementById('opindo-piechart'));
  chart.draw(data, options);
}