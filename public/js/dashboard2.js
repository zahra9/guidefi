(function($) {
  'use strict'; 
  $(function() {
    //Orders Chart Azure
    if ($("#orders-chart-azure").length) {
      var orderChartCanvas = $("#orders-chart-azure").get(0).getContext("2d");

      var gradient1 = orderChartCanvas.createLinearGradient(0, 0, 0, 350);
      gradient1.addColorStop(0, 'rgba(24, 135, 157, 0.7)');
      gradient1.addColorStop(1, 'rgba(0, 0, 0, 0)');

      var currentChart = new Chart(orderChartCanvas, {
        type: 'horizontalBar',
        data: {
          labels: ["CKD", "Non CKD", "ESRD", "Total"],
          datasets: [{
              label: 'Collectables',
              data: [1110, 1190, 1105, 2990],
              backgroundColor: gradient1,
              borderColor: "rgba(24, 135, 157, 0.8)",
              borderWidth: 2,
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          layout: {
            padding: {
              left: 0,
              right: 0,
              top: 20,
              bottom: 0
            }
          },
          scales: {
            yAxes: [{
              gridLines: {
                drawBorder: false,
                color: "rgba(56, 184, 242, 0.05)"
              },
              ticks: {
                fontColor: '#9a9a9a',
                stepSize: 100,
              }
            }],
            xAxes: [{

              ticks: {
                fontColor: '#9a9a9a',
                beginAtZero: false,
                  min: 100,
                  max: 3000
              },
              gridLines: {
                display: false,
                color: "rgba(56, 184, 242, 0.05)"
              },
              barPercentage: 0.6
            }]
          },
          legend: {
            display: true
          },
          elements: {
            point: {
              radius: 0
            }
          }
        }
      });
    }
      // Trafic Chart
      if ($("#traffic-chart").length) {
          var ctx = document.getElementById('traffic-chart').getContext("2d");

          var gradientStrokeGreen = ctx.createLinearGradient(0, 0, 0, 181);
          gradientStrokeGreen.addColorStop(0, '#3add99');
          gradientStrokeGreen.addColorStop(1, '#3add99');
          var gradientLegendGreen = '#3add99';

          var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 150);
          gradientStrokeBlue.addColorStop(0, '#04b4f0');
          gradientStrokeBlue.addColorStop(1, '#04b4f0');
          var gradientLegendBlue = '#04b4f0';

          var gradientStrokePurple = ctx.createLinearGradient(0, 0, 0, 181);
          gradientStrokePurple.addColorStop(0, '#744af4');
          gradientStrokePurple.addColorStop(1, '#744af4');
          var gradientLegendPurple = '#744af4';

          var trafficChartData = {
              datasets: [{
                  data: [40, 40, 20],
                  backgroundColor: [
                    gradientStrokeGreen,
                    gradientStrokeBlue,
                    gradientStrokePurple
                  ],
                  hoverBackgroundColor: [
                    gradientStrokeGreen,
                    gradientStrokeBlue,
                    gradientStrokePurple
                  ],
                  borderColor: [
                    gradientStrokeGreen,
                    gradientStrokeBlue,
                    gradientStrokePurple
                  ],
                  legendColor: [
                    gradientLegendGreen,
                    gradientLegendBlue,
                    gradientLegendPurple
                  ]
              }],

              // These labels appear in the legend and in the tooltips when hovering different arcs
              labels: [
                  'CKD ',
                  'ESRD',
                  'Non CKD',
              ]
          };
          var trafficChartOptions = {
              responsive: true,
              animation: {
                  animateScale: true,
                  animateRotate: true
              },
              legend: false,

              legendCallback: function(chart) {
                  var text = [];
                  text.push('<ul>');
                  for (var i = 0; i < trafficChartData.datasets[0].data.length; i++) {
                      text.push('<li><h2 class="mb-2">'+trafficChartData.datasets[0].data[i]+'%</h2><div class="legend-content"><span class="legend-dots" style="background:' +
                          trafficChartData.datasets[0].legendColor[i] +
                          '"></span>'+trafficChartData.labels[i]+'</div>');
                      text.push('</li>');
                  }
                  text.push('</ul>');
                  return text.join('');
              },
              cutoutPercentage: 86
          };
          var trafficChartPlugins = {
              beforeDraw: function(chart) {
                  var width = chart.chart.width,
                      height = chart.chart.height,
                      ctx = chart.chart.ctx;

                  ctx.restore();
                  var fontSize = 1.2;
                  ctx.font = fontSize + "em sans-serif";
                  ctx.textBaseline = "middle";
                  ctx.fillStyle = "#ffffff";
                  // ctx.borderWidth= 4;

                  var text = "1.2 M",
                      textX = Math.round((width - ctx.measureText(text).width) / 2),
                      textY = height / 2;

                  ctx.fillText(text, textX, textY);
                  ctx.save();
              }
          }
          var trafficChartCanvas = $("#traffic-chart").get(0).getContext("2d");
          var trafficChart = new Chart(trafficChartCanvas, {
              type: 'doughnut',
              data: trafficChartData,
              options: trafficChartOptions,
              plugins: trafficChartPlugins
          });
          $("#traffic-chart-legend").html(trafficChart.generateLegend());
      }




    //Navbar Search
    $( "#search-icon" ).on("click", function()  {
      $( "#search-field" ).toggle( "slow", function() {
      });
    });
    
  });
})(jQuery);