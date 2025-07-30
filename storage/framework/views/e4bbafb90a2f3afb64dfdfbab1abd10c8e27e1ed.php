<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <div class="row no-gutters">


        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color active-patients card-heading card-heading-overlap">Total
                                Users </h5>
                            
                                <h4 class="text-black mt-3 ml-3"><?php echo e($total_users); ?></h4>
                            <!--<h6 class="text-muted">35.19% Since last month</h6>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color new-patients card-heading card-heading-overlap">Total
                                Advisors</h5>
                            <h4 class="text-black mt-3 ml-3"><?php echo e($total_advisors); ?></h4>
                            <!--<h6 class="text-muted">73.52% Since last month</h6>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color ckd-patients card-heading card-heading-overlap">Total Clients</h5>
                            <h4 class="text-black mt-3 ml-3"><?php echo e($total_clients); ?></h4>
                            <!--<h6 class="text-muted">49.39% Since last month</h6>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color new-patients card-heading-overlap card-heading card-heading-overlap">
                                Paid Users</h5>
                            <h4 class="text-black mt-3 ml-3"><?php echo e($paid); ?></h4>
                            <!--<h6 class="text-muted">35.19% Since last month</h6>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color ckd-patients card-heading card-heading-overlap">Unpaid Users</h5>
                            <h4 class="text-black mt-3 ml-3"><?php echo e($unPaid); ?></h4>
                            <!--<h6 class="text-muted">49.39% Since last month</h6>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color new-patients card-heading-overlap card-heading">Yearly
                                Revenue</h5>
                            <h4 class="text-black mt-3 ml-3">
                                <?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($revenueYearly) ?$revenueYearly : '0', 2, ',', '.')); ?>

                            </h4>
                            <!--<h6 class="text-muted">49.39% Since last month</h6>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color new-patients card-heading-overlap card-heading">Monthly
                                Revenue</h5>
                            <h4 class="text-black mt-3 ml-3">
                                <?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($revenueMonth) ?$revenueMonth : '0', 2, ',', '.')); ?>

                            </h4>
                            <!--<h6 class="text-muted">49.39% Since last month</h6>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color new-patients card-heading-overlap card-heading">Yearly
                                Profit</h5>
                            <h4 class="text-black mt-3 ml-3">
                                <?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($yearlyProfit) ?$yearlyProfit : '0', 2, ',', '.')); ?>


                            </h4>
                            <!--<h6 class="text-muted">49.39% Since last month</h6>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color new-patients card-heading-overlap card-heading">Monthly
                                Profit</h5>
                            <h4 class="text-black mt-3 ml-3">
                                <?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($monthlyProfit) ?$monthlyProfit : '0', 2, ',', '.')); ?>

                            </h4>
                            <!--<h6 class="text-muted">49.39% Since last month</h6>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color active-patients card-heading card-heading-overlap">Weekly
                                Profit</h5>
                            <h4 class="text-black mt-3 ml-3">
                                <?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($weeklyProfit) ?$weeklyProfit : '0', 2, ',', '.')); ?>


                            </h4>
                            <!--<h6 class="text-muted">73.52% Since last month</h6>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color new-patients card-heading-overlap card-heading">Daily Profit</h5>
                            <h4 class="text-black mt-3 ml-3">
                                <?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($dailyProfit) ?$dailyProfit : '0', 2, ',', '.')); ?>



                            </h4>
                            <!--<h6 class="text-muted">73.52% Since last month</h6>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row no-gutters">




        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color new-patients card-heading-overlap card-heading">Completed
                                Discovery Calls</h5>
                            <h4 class="text-black mt-3 ml-3"><?php echo e($completedCalls); ?></h4>
                            <!--<h6 class="text-muted">73.52% Since last month</h6>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="head-color new-patients card-heading-overlap card-heading">Discovery Calls In Progress</h5>

                            <h4 class="text-black mt-3 ml-3"> <?php echo e($waitingCalls); ?></h4>
                            <!--<h6 class="text-muted">49.39% Since last month</h6>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>


    <div class="row">
        <div class="col-12 col-sm-12 col-xl-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title" style="line-height: 25px;">Revenue And Profit</h4>

                    <?php if($revenues->sum() && $profit->sum()): ?>
                    <div>

                        <canvas id="Chart1"></canvas>
                    </div>
                    <?php else: ?>
                    <div>
                        <span class="text-black">No Record Added Yet!!</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-xl-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title" style="line-height: 25px;">Total Number of Advisors and Client <small style="font-size: 11px;">(Based on 6 months trend)</small></h4>

                    <?php if(count($advisorLastMonth)>0): ?>
                    <div>
                        <canvas id="chart-sales"></canvas>
                    </div>
                    <?php else: ?>
                    <div>
                        <span>No Record Added Yet!!</span>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12 col-md-12 col-xl-12 grid-margin" style="left: auto">
            <span class="head-color download-position">Download Dashboard as:</span>
            <form action="<?php echo e(route('admin.home.export')); ?>" method="POST" id="statsDownload">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="total_user" value="<?php echo e($total_users); ?>">
                <input type="hidden" name="paid_user" value="<?php echo e($paid); ?>">
                <input type="hidden" name="unpaid_user" value="<?php echo e($unPaid); ?>">
                <input type="hidden" name="total_client" value="<?php echo e($total_clients); ?>">
                <input type="hidden" name="total_advisor" value="<?php echo e($total_advisors); ?>">
                <input type="hidden" name="today_profit" value="<?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($dailyProfit)?$dailyProfit : '0', 2, ',', '.')); ?>">
                <input type="hidden" name="weekly_profit" value="<?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($weeklyProfit)?$weeklyProfit : '0', 2, ',', '.')); ?>">
                <input type="hidden" name="monthly_profit" value="<?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($monthlyProfit)?$monthlyProfit : '0', 2, ',', '.')); ?>">
                <input type="hidden" name="yearly_profit" value="<?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($yearlyProfit)?$yearlyProfit : '0', 2, ',', '.')); ?>">
                <input type="hidden" name="month_revenue" value="<?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($revenueMonth) ?$revenueMonth : '0', 2, ',', '.')); ?>">
                <input type="hidden" name="yearly_revenue" value="<?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($revenueYearly) ?$revenueYearly : '0', 2, ',', '.')); ?>">
                <input type="hidden" name="cdc" value="<?php echo e($completedCalls); ?>">
                <input type="hidden" name="wdc" value="<?php echo e($waitingCalls); ?>">
                <input type="hidden" name="crm" value="90%">
                <span class="processed-pdf"></span>
                <button type="submit" id="statsCsvDownload" class="btn btn-primary ml-3 " style="float: right;margin-top:8px;" data-toggle="tooltip" data-placement="right" title="Download as CSV"><i class="ti-download"></i></button>
                <button id="statsPdfDownload" class="btn btn-default" style="float: right;" data-toggle="tooltip" data-placement="right" title="Download as PDF">
                 <img src="/assets/img/pdf.png" width="26px" alt=""></button>
            </form>
        </div>
    </div>
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('footer_scripts'); ?>
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('scripts'); ?>
    <script>
        $(document).on("click", "#statsPdfDownload", function(e) {
            e.preventDefault();
            var $f = $('#statsDownload')[0];
            console.log($f.attributes["action"].value);
            var formData = new FormData($f);
            formData.append('type', 'pdf');
            $.ajax({
                 beforeSend: function () {
                     $(".processed-pdf").html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>').prop('disabled', true);
                 },
                url: $f.attributes["action"].value,
                type: $f.attributes["method"].value,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Statistics.pdf";
                    link.click();
                    $(".processed-pdf").html("").prop('disabled', false);
                },
                error: function(xhr) {
                    $(".processed-pdf").html("").prop('disabled', false);
                    alert('else error');
                }
            });
        });
        $(document).on("click", "#statsCsvDownload", function(e) {
            e.preventDefault();
            var $f = $('#statsDownload')[0];
            console.log($f.attributes["action"].value);

            var formData = new FormData($f);
            formData.append('type', 'csv');
            $.ajax({
                /* beforeSend: function () {
                     $sb.html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>Processing').prop('disabled', true);
                 },*/
                url: $f.attributes["action"].value,
                type: $f.attributes["method"].value,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {

                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Statistics.csv";
                    link.click();
                },
                error: function(xhr) {
                    alert('else error');
                }
            });
        });
    </script>
    <script>

        var barOptions_stacked = {
            tooltips: {
                display: true
            },
            hover :{
                animationDuration:0
            },
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero:true,
                        fontFamily: "'Open Sans Bold', sans-serif",
                        fontSize:11,
                        fontColor: "#296a81"

                    },
                    scaleLabel:{
                        display:false
                    },
                    gridLines: {
                        display:false
                    },
                    stacked: true
                }],
                yAxes: [{
                    barThickness : 15,
                    gridLines: {
                        display:false,
                        color: "#fff",
                        zeroLineColor: "#fff",
                        zeroLineWidth: 0
                    },
                    ticks: {
                        fontFamily: "'Open Sans Bold', sans-serif",
                        fontSize:11,
                        fontColor:'#296a81'
                    },
                    stacked: true
                }]
            },
            legend:{
                display:true,
                labels: {
                    fontColor: "#04151b",
                }
            },


            pointLabelFontFamily : "Quadon Extra Bold",
            scaleFontFamily : "Quadon Extra Bold",
        };



        var ctx = document.getElementById("Chart1");
        var myChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: ["TOTAL", "THIS YEAR", "THIS MONTH", "THIS WEEK"],

                datasets: [{
                    label: 'Revenue',
                    data:[
                    <?php if(count($revenues)>0): ?>
        <?php $__currentLoopData = $revenues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revenue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        '<?php echo e($revenue); ?>',
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        2000, 2500, 2500, 2500,
        <?php endif; ?>
        ],
        backgroundColor: "#04b4f0",
            hoverBackgroundColor: "#04b4f0"
        },{
            label: 'Profit',
                data: [
            <?php if(count($profit)>0): ?>
            <?php $__currentLoopData = $profit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                '<?php echo e($proft); ?>',
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                2000, 2500, 2500, 2500,
                <?php endif; ?>
        ],backgroundColor: "#3add99",
          hoverBackgroundColor: "#3add99"
        }]
        },

        options: barOptions_stacked,
        });

    </script>

    <script>
        //Chart Sales
        if ($("#chart-sales").length) {
            var sum = '<?php echo e($sumAdvisorClient); ?>';
            var chartSalesCanvas = $("#chart-sales").get(0).getContext("2d");
            var gradient1 = chartSalesCanvas.createLinearGradient(0, 0, 0, 160);
            gradient1.addColorStop(0, '#05B4F0');
            gradient1.addColorStop(1, 'rgba(0, 0, 0, 0)');

            var gradient2 = chartSalesCanvas.createLinearGradient(0, 0, 0, 160);
            gradient2.addColorStop(0, '#42CBA4');
            gradient2.addColorStop(1, 'rgba(0, 0, 0, 0)');


            var areaData = {
                labels: [

                    "Jan", "Feb", "Mar", "Apr", "May", "June"
                ],
                datasets: [{
                    data: [
                    <?php if(count($advisorLastMonth)>0): ?>
        <?php $__currentLoopData = $advisorLastMonth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $graphTotal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            '<?php echo e($graphTotal); ?>',
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
            2000, 2500, 2500, 2500,
            <?php endif; ?>
                    ],
                    backgroundColor: gradient1,
                    borderColor: [
                        '#04b4f0'
                    ],
                    borderWidth: 2,
                    pointBorderColor: "#04b4f0",
                    pointBorderWidth: 4,
                    pointRadius: 1,
                    fill: 'origin',
                    label: "Total Advisor"
                },
                    {
                        data: [
                        <?php if(count($clientLastMonth)>0): ?>
                    <?php $__currentLoopData = $clientLastMonth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $graphTotal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        '<?php echo e($graphTotal); ?>',
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        2000, 2500, 2500, 2500,
                        <?php endif; ?>
                    ],
                        backgroundColor: gradient2,
                        borderColor: [
                            '#3add99'

                        ],
                        borderWidth: 2,
                        pointBorderColor: "#3add99",
                        pointBorderWidth: 4,
                        pointRadius: 1,
                        fill: 'origin',
                        label: "Total Client"
                    }
                ]
            };

            var areaOptions = {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            fontColor: "#296a81",
                            // stepSize: 1,
                            min: 0,
                            max: <?php echo e($sumAdvisorClient); ?>,
                        },
                        gridLines: {
                            drawBorder: false,
                            color: "rgba(101, 103, 119, 0.21)"
                        }
                    }]
                },
                legend: {
                    display: true,
                    labels: {
                        fontColor: "#04151b",

                    }
                },
                legendCallback : function(chart) {
                    var text = [];
                    text.push(' <div class="d-flex justify-content-between justify-content-lg-start flex-wrap">');
                    text.push('<div class="mr-5 mb-2">');
                    text.push('<div class="d-flex">');
                    text.push('<i class="ti-briefcase" style="color: ' + chart.data.datasets[0].borderColor[0] +' "></i>');
                    text.push('<h3 class="text-white ml-3">'+ chart.data.datasets[0].data[1] + '</h3>');
                    text.push('</div>');
                    text.push('<h6 class="font-weight-normal mb-0">Online sales</h6>');
                    text.push('</div>');
                    text.push('<div class="mb-2">');
                    text.push('<div class="d-flex">');
                    text.push('<i class="ti-apple" style="color: ' + chart.data.datasets[1].borderColor[0] +' "></i>');
                    text.push('<h3 class="text-white ml-3">'+ chart.data.datasets[1].data[2] + '</h3>');
                    text.push('</div>');
                    text.push('<h6 class="font-weight-normal mb-0">Sales in store</h6>');
                    text.push('</div>');
                    text.push('</div>');
                    return text.join('');
                },
                tooltips: {
                    enabled: true
                }
            }
            var salesChartCanvas = $("#chart-sales").get(0).getContext("2d");
            var salesChart = new Chart(salesChartCanvas, {
                type: 'line',
                data: areaData,
                options: areaOptions
            });
            document.getElementById('sales-legend').innerHTML = salesChart.generateLegend();
        }




    </script>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/pages/admin/home.blade.php ENDPATH**/ ?>