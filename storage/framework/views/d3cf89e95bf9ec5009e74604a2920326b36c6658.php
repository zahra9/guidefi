<?php $__env->startSection('linked_css'); ?>
    <link rel="stylesheet" src="<?php echo e(asset('css/nprogress.css')); ?>">
    <link rel="stylesheet" src="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <!--                <button type="button" class="btn video btn-info btn-lg" >Add New</button>-->
                <?php echo e(link_to_route('videos.create', 'Add New', [], ['class' => 'btn Addvideo btn-info btn-lg'])); ?>

                <div>

                    <h4 class="card-title font-weight-bold">Videos</h4>
                    <canvas id="countyBarChart"></canvas>





                    Type a message

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Title</th>
                                    <th>Video</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>

                                <?php if($videos->count()): ?>
                                    <?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="text-black video-index<?php echo e(!is_null($video->id) ? $video->id : ''); ?>">
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e(!is_null($video->title) ?$video->title:''); ?></td>
                                            <td>
                                                <?php if(!is_null($video->url)): ?>
                                                    <a href="<?php echo e($video->url); ?>" target="_blank"><img
                                                                src="<?php echo e($video->thumbnail); ?>"></a>
                                                <?php endif; ?>
                                            </td>

                                            <td><?php echo e(!is_null($video->user) ?$video->user->name:''); ?></td>

                                            <td><?php echo e(!is_null($video) ? \Carbon\Carbon::parse($video->created_at)->format('M-d-Y'): '-- -- --'); ?></td>

                                            <td>
                                                <select class="form-control video_status<?php echo e(!is_null($video->id) ? $video->id : ''); ?>"
                                                        name="status">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $privacyStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($video->admin_status == $status): ?>
                                                            <option data-id="<?php echo e($video->id); ?>" selected="selected"
                                                                    data-val="<?php echo e($status); ?>"><?php echo e($status); ?></option>
                                                        <?php else: ?>
                                                            <option data-id="<?php echo e($video->id); ?>"
                                                                    data-val="<?php echo e($status); ?>"><?php echo e($status); ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </select>

                                            </td>
                                            <td>
                                                <button id="admin-status"
                                                        class="btn <?php echo e(($video->admin_status == 'approved')?'btn-success':'btn-danger'); ?> video_status_button video_status_button<?php echo e(!is_null($video->id) ? $video->id : ''); ?>"
                                                        data-id="<?php echo e(!is_null($video->id) ? $video->id : ''); ?>">
                                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                </button>
                                                <a title="Delete" style="cursor: pointer;color:#FE0957;font-size: 17px;"
                                                   class="DeleteVideo  DeleteVideo<?php echo e(!is_null($video->id) ? $video->id : ''); ?>"
                                                   data-id="<?php echo e(!is_null($video->id) ? $video->id : ''); ?>">
                                                    <i class="ti-trash">
                                                    </i>
                                                </a>
                                            </td>

                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/nprogress.js')); ?>"></script>
    <?php echo $__env->make('scripts.status-change', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script>
        $(document).on("click", ".DeleteVideo", function () {
            if (confirm('Are you sure you want to delete?')) {
                var id = $(this).data('id');
                var url = '<?php echo url('videos'); ?>' + "/" + id;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    dataType: 'json',
                    data: {
                        id: id,
                        _method: 'DELETE'
                    },
                    beforeSend: function () {
                        $('.DeleteVideo' + id).html('<i class="fa fa-cog" aria-hidden="true"></i>');
                    },
                    success: function (response) {
                        if (response.status) {
                            $('.video-index' + id).remove();
                        }
                    },
                    error:function (response){
                       console.log('error');
                       console.log(response);
                    },

                });
            }
        });

    </script>

    <script src="<?php echo e(asset('Chart.js')); ?>"></script>
    <script>
        // aceChart(res) {
            var ctx = $("#countyBarChart").get(0).getContext("2d");
            Chart.defaults.global.defaultFontColor = 'transparent';
            Chart.defaults.global.defaultFontSize = 12;

            var theHelp = Chart.helpers;
            var res = null;
            let asian = 0;
            let black = 0;
            let hawai = 0;
            let hispanic = 0;
            let indian = 0;
            let other = 0;
            let white = 0;
            let total = 0;
            let asian_avg = 0;
            let black_avg = 0;
            let hawai_avg = 0;
            let hispanic_avg = 0;
            let indian_avg = 0;
            let other_avg = 0;
            let white_avg = 0;

            asian = asian + 10;
            black = black + 2;
            hawai = hawai + 30;
            hispanic = hispanic + 40;
            indian = indian +50;
            other = other + 60;
            white = white + 70;
            total = asian + black + hawai + hispanic + indian + other + white;
            asian_avg = (asian * 100) / total;
            black_avg = (black * 100) / total;
            hawai_avg = (hawai * 100) / total;
            hispanic_avg = (hispanic * 100) / total;
            indian_avg = (indian * 100) / total;
            other_avg = (other * 100) / total;
            white_avg = (white * 100) / total;
            if (asian_avg > 0 ||
                black_avg > 0 ||
                hawai_avg > 0 ||
                hispanic_avg > 0 ||
                indian_avg > 0 ||
                other_avg > 0 ||
                white_avg > 0) {
                $('.zip-code-graph-text').hide();

                var test =
                    [white_avg.toFixed(2), black_avg.toFixed(2), hispanic_avg.toFixed(2), asian_avg.toFixed(2),
                        hawai_avg.toFixed(2), indian_avg.toFixed(2), other_avg.toFixed(2)];

                    /* [Math.round(white_avg.toFixed(2)), Math.round(black_avg.toFixed(2)), Math.round(hispanic_avg.toFixed(2)),
                     Math.round(asian_avg.toFixed(2)), Math.round(hawai_avg.toFixed(2)), Math.round(indian_avg.toFixed(2)),
                     Math.round(other_avg.toFixed(2))];*/
                console.log('*******');
                console.log( test.sort(function(a, b){return a - b}));

                var sort = test.sort(function(a, b){return a - b});

                var data = {
                    fill: false,
                    labels: ["White", "Black", "Hispanic", "Asian", "Hawaiian", "Indian", "Other"],
                    datasets: [{
                        fill: false,
                        data: sort,
                            // [white_avg.toFixed(2), black_avg.toFixed(2), hispanic_avg.toFixed(2), asian_avg.toFixed(2), hawai_avg.toFixed(2), indian_avg.toFixed(2), other_avg.toFixed(2)],
                        backgroundColor: [
                            'rgba(202, 216, 255, 1)',
                            'rgba(157, 177, 236, 1)',
                            'rgba(0, 102, 204, 1)',
                            'rgba(0, 102, 153, 1)',
                            'rgba(0, 51, 153, 1)',
                            'rgba(204, 204, 204, 1)',
                            'rgba(238, 238, 238, 1)'
                        ],
                        fontColor: [
                            'rgba(202, 216, 255, 1)',
                        ],
                        borderColor: [
                            'rgba(202, 216, 255, 1)',
                            'rgba(157, 177, 236, 1)',
                            'rgba(0, 102, 204, 1)',
                            'rgba(0, 102, 153, 1)',
                            'rgba(0, 51, 153, 1)',
                            'rgba(204, 204, 204, 1)',
                            'rgba(238, 238, 238, 1)'
                        ],
                    }]
                };
                console.log(data);
            } else {
                $('.zip-code-graph').hide();
                $('.zip-code-graph-text').show();

            }
            var options = {
                rotation: -0.7 * Math.PI,
                responsive: true,
                animation: {
                    animateScale: true,
                    animateRotate: true
                },

                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 15,
                        bottom: 15,
                    }
                },

                legend: {
                    position: 'right',
                    display:true,


                    // generateLabels changes from chart to chart,  check the source,
                    // this one is from the doughut :
                    // https://github.com/chartjs/Chart.js/blob/master/src/controllers/controller.doughnut.js#L42
                    labels: {
                        // generateLabels: function(chart) {
                        //   var data = chart.data;
                        //   if (data.labels.length && data.datasets.length) {
                        //     return data.labels.map(function(label, i) {
                        //       var meta = chart.getDatasetMeta(0);
                        //       var ds = data.datasets[0];
                        //       var arc = meta.data[i];
                        //       var custom = arc && arc.custom || {};
                        //       var getValueAtIndexOrDefault = theHelp.getValueAtIndexOrDefault;
                        //       var arcOpts = chart.options.elements.arc;
                        //       var fill = custom.backgroundColor ? custom.backgroundColor : getValueAtIndexOrDefault(ds.backgroundColor, i, arcOpts.backgroundColor);
                        //       var stroke = custom.borderColor ? custom.borderColor : getValueAtIndexOrDefault(ds.borderColor, i, arcOpts.borderColor);
                        //       var bw = custom.borderWidth ? custom.borderWidth : getValueAtIndexOrDefault(ds.borderWidth, i, arcOpts.borderWidth);
                        //       return {
                        //         // And finally :
                        //         text:label,
                        //         fillStyle: fill,
                        //         strokeStyle: stroke,
                        //         lineWidth: bw,
                        //         hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
                        //         index: i
                        //       };
                        //     });
                        //   }
                        //   return [];
                        // }
                    }
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItems, data) {
                            return data.labels[tooltipItems.index] +
                                " : " +
                                data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] +
                                ' %%%';
                        }
                    }
                }
            };

// Chart declaration:
            var myPieChart = new Chart(ctx, {
                type: 'pie',
                data: data,
                options: options
            });

            //Plugin from githubExample:
//https://github.com/chartjs/Chart.js/blob/master/samples/data_labelling.html


            Chart.plugins.register({
                afterDatasetsDraw: function(chartInstance, easing) {
                    // To only draw at the end of animation, check for easing === 1
                    var ctx = chartInstance.chart.ctx;
                    chartInstance.data.datasets.forEach(function(dataset, i) {
                        var meta = chartInstance.getDatasetMeta(i);
                        // console.log('---------');
                        // console.log(dataset.);
                        if (!meta.hidden) {
                            meta.data.forEach(function(element, index) {
                                // Draw the text in black, with the specified font
                                ctx.fillStyle = (index > 2)?'black':'';
                                var fontSize = 12;
                                var fontStyle = 'normal';
                                var fontFamily = 'Helvetica Neue';
                                ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                                // Just naively convert to string for now
                                var dataString = dataset.data[index].toString();

                                // Make sure alignment settings are correct
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                var padding = 5;
                                var position = element.tooltipPosition();
                                ctx.fillText(dataString + '%', position.x, position.y - (fontSize / 2) - padding);
                                console.log(index);
                            });
                        }
                    });
                }
            });


        // }
    </script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/video/index.blade.php ENDPATH**/ ?>