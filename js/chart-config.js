google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
  var elementExists  = document.getElementById('opindo-answer-data');
  if ( elementExists ) {
    var jsonData = document.getElementById('opindo-answer-data').value;

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Answer');
    data.addColumn('number', 'Percentage');
    data.addColumn('number', 'Count');
    data.addRows(JSON.parse(jsonData));

    var colors = ['#5c6cff', '#434b6c', '#696f89', '#8e93a7', '#b4b7c4'];

    var options = {
      legend: 'none',
      title: '',
      pieHole: 0.6,
      colors: colors,
      chartArea: {left:40,top:20,width:'180',height:'160'},
      height: 190,
      width: 220
    };

    var chart = new google.visualization.PieChart(document.getElementById('opindo-piechart'));
    chart.draw(data, options);

    var total = 0;
    for (var i = 0; i < data.getNumberOfRows(); i++) {
        total += data.getValue(i, 1);
    }

    var legend = document.getElementById("opindo-legend");
    var legItem = [];
    var colors = colors;
    for (var i = 0; i < data.getNumberOfRows(); i++) {
        var label = data.getValue(i, 0);
        var value = data.getValue(i, 1);

        // This will create legend list for the display
        legItem[i] = document.createElement('li');
        legItem[i].innerHTML = label + ': ' + value + '%<div style="background:' + colors[i] + ';float:right;height: 10px;width: 10px;margin: 2px 0 2px 6px;"></div>';

        legend.appendChild(legItem[i]);
    }
  }
}