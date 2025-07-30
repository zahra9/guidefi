<?php $__env->startSection('linked_css'); ?>
<link rel="stylesheet" src="<?php echo e(asset('css/nprogress.css')); ?>">
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" />
<Style>
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border: solid black 1px;
        outline: 0;
        background: none !important;
        height: 40px !important;
        overflow-y: scroll !important;
    }

    .select2-container--default .select2-selection--multiple {
        background: none !important;
        height: 40px !important;
        border: 1px solid #bce0ea !important;
    }

    .dropdown-menu-right li {
        padding: 0 5px;
        color: #fff;
        font-size: 13px;
    }

    .dropdown-menu-right li a {
        color: #fff;
    }

    .dropdown-menu-right li a i {
        margin-right: 5px;
    }

    td.details-control {
        background: url('../resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.details td.details-control {
        background: url('../resources/details_close.png') no-repeat center center;
    }
</Style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<!-- content-wrapper starts -->
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title advisor-detail ml-2"><strong>Advisor's Personal Details</strong> </h4>

            


            <div class="row mt-5">

                <?php if(!is_null($advisorDetail->person->image_url)): ?>
                <div class="col-md-12" style="text-align: center;">
                    <img src="<?php echo e($advisorDetail->person->image_url); ?>" width="150px" height="150px" style="border-radius: 50%;" alt="">
                </div>
                <?php else: ?>
                <div class="col-md-12" style="text-align: center;">
                    <img src="<?php echo e(asset('images/default-avatar.png')); ?>" width="150px" height="150px" style="border-radius: 50%;" alt="">
                </div>
                <?php endif; ?>
                <div class="col-md-6 mt-5 ">
                    <div class="col-12 ">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>First Name:</p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->first_name:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Last Name:</p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->last_name:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Email:</p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->email:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Phone No:</p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->phone:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Date Of Birth:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <?php echo e(!is_null($advisorDetail->person)?
                               \Carbon\Carbon::parse($advisorDetail->person->birth_date)->format('M-d-Y'):'-- -- --'); ?>

                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Age:</p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->age:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-12">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Gender:</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-black"><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->gender:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="col-md-6 mt-5">


                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Religion:</p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->religion:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>State:</p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->state:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Zip Code:</p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->zipcode:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Experience level:</p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->experience_level:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Academic Records:</p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->academic_records:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Website:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <?php if(!is_null($advisorDetail->person->website)): ?>
                                        <a href="<?php echo e(url($advisorDetail->person->website)); ?>" target="_blank"><?php echo e($advisorDetail->person->website); ?></a>
                                        <?php endif; ?>
                                    </p>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Driving License:</p>
                                </div>
                                <div class="col-md-6">
                                  <img width="100%" src="<?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->driving_licence:'-- -- --'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>



                    <!--    <div class="col-12 pl-5">
                                            <div class="text-black">
                                                <div class="row ml-3 advisor-list">
                                                    <div class="col-md-6 advisor-detail">
                                                        <p>Paid Amount:</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p>
                                                            <?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($totalAmount) ?$totalAmount : '0', 2, ',', '.')); ?>


                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->

                    <!-- <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml">
                                <div class="col-md-6">
                                    <p>BioGraphy:</p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo e(!is_null($advisorDetail->person)?$advisorDetail->person->bio:'-- -- --'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>


        </div>
    </div>
</div>

<div class="content-wrapper mt-5">
    <div class="card">
        <div class="card-body">
            <div class="row pl-3 mt-5">
                <div class="col-md-6">
                    <div class="text-black">
                        <div class=" <?php echo e($errors->has('types') ? 'is-invalid' :'-- -- --'); ?>">
                            <div class="row">
                                <div class="col-md-2 ml-3" style="font-weight: 600;">Type</div>
                                <div class="col-md-3 pl-0">
                                    <select class="content-overflow js-example-basic-multiple form-control js-example-basic-multiple advisor_request_type js-example-basic-single" name="types[]" multiple required>
                                        <?php if(!is_null($types)): ?>
                                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(!empty($selectedAdvisorType)): ?>
                                        <option value="<?php echo e($type->user_type); ?>" <?php echo e($selectedAdvisorType->contains($type->user_type)?'selected':''); ?>><?php echo e($type->user_type); ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo e($type->user_type); ?>"><?php echo e($type->user_type); ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>


                                    </select>
                                    <button class="btn btn-outline-primary advisor_request_type_submit">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Total Amount Paid by Advisor :</p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($totalAmount) ?$totalAmount : '0', 2, ',', '.')); ?>


                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="content-wrapper mt-5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-title">
                        <h5 class="mt-5 card-title"><strong>Paid By Advisor</strong> </h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive mt-4">
                        <table id="example" class="table">
                            <thead>

                                <tr class="head-color">
                                    <th>#</th>
                                    <th>Invoice Number</th>
                                    <th>Period Start</th>
                                    <th>Period End</th>
                                    <th>Payment Status</th>
                                    <th>Amount Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($paidByAdvisor) && !is_null($advisorClientType)): ?>
                                <?php $__currentLoopData = $paidByAdvisor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="text-black">
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e(!is_null($payment->inv_number)?$payment->inv_number:'-- -- --'); ?><?php if(!is_null($payment->invoice_pdf)): ?>
                                        <a href="<?php echo e(url($payment->invoice_pdf)); ?>">
                                            <i class="ti-download" aria-hidden="true"> </i>
                                        </a>
                                        <?php endif; ?></td>
                                    <td><?php echo e(!is_null($payment->period_start)?
    \Carbon\Carbon::parse($payment->period_start)->format('M-d-Y'):'-- -- --'); ?>

                                    </td>
                                    <td>
                                        <?php echo e(!is_null($payment->period_end)?
       \Carbon\Carbon::parse($payment->period_end)->format('M-d-Y'):'-- -- --'); ?>

                                    </td>
                                    <td><?php echo e($payment->status); ?></td>
                                    <td><?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($payment->amount_paid) ? $payment->amount_paid : '0', 2, ',', '.')); ?></td>
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

<div class="content-wrapper mt-5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-title">
                        <h5 class="mt-5 card-title"><strong>Paid For Advisor</strong> </h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive mt-4">
                        <table id="example" class="table">
                            <thead>

                                <tr class="head-color">
                                    <th>#</th>
                                    <th>Invoice Number</th>
                                    <th>Period Start</th>
                                    <th>Period End</th>
                                    <th>Payment Status</th>
                                    <th>Amount Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($paidForAdvisor) && !is_null($paidForAdvisor) ): ?>
                                <?php $__currentLoopData = $paidForAdvisor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="text-black">
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e(!is_null($payment->inv_number)?$payment->inv_number:'-- -- --'); ?><?php if(!is_null($payment->invoice_pdf)): ?>
                                        <a href="<?php echo e(url($payment->invoice_pdf)); ?>">
                                            <i class="ti-download" aria-hidden="true"> </i>
                                        </a>
                                        <?php endif; ?></td>
                                    <td><?php echo e(!is_null($payment->period_start)?
                                    \Carbon\Carbon::parse($payment->period_start)->format('M-d-Y'):'-- -- --'); ?>

                                    </td>
                                    <td>
                                        <?php echo e(!is_null($payment->period_end)?
                                    \Carbon\Carbon::parse($payment->period_end)->format('M-d-Y'):'-- -- --'); ?>

                                    </td>
                                    <td><?php echo e($payment->status); ?></td>
                                    <td><?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($payment->amount_paid) ? $payment->amount_paid : '0', 2, ',', '.')); ?></td>
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
<div class="content-wrapper mt-5">


    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-title">
                        <h5 class="pl-3 card-title mt-5"><strong>Advisor's Call Logs</strong> </h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive mt-4">
                        <table id="example" class="table">
                            <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Client Name</th>
                                    <th>Advisor Name</th>
                                    <th>Type</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($advisorDetail->advisorCallLogs->count()): ?>
                                <?php $__currentLoopData = $advisorDetail->advisorCallLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $callLog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="text-black">
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($callLog->client->name); ?></td>
                                    <td><?php echo e($callLog->advisor['name']); ?></td>
                                    <td><?php echo e($callLog->type); ?></td>
                                    <td>
                                        <?php echo e(!is_null($callLog->start_time)?
                                                     \Carbon\Carbon::parse($callLog->start_time)->format('M-d-Y'):'-- -- --'); ?>

                                    </td>
                                    <td>
                                        <?php echo e(!is_null($callLog->end_time)?
                                                         \Carbon\Carbon::parse($callLog->end_time)->format('M-d-Y'):'-- -- --'); ?>

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

<div class="content-wrapper mt-5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-title">
                        <h5 class="pl-3 card-title mt-5"><strong>Client Advisor Deals</strong></h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive mt-4">
                        <table id="example" class="table">
                            <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Client Name</th>
                                    <th>Advisor Name</th>
                                    <th>Offer</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Commision Amount <br>Percentage</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Appointment No.</th>
                                    <th>Appointment Day</th>
                                    <th>Appointment Status</th>
                                </tr>s
                            </thead>
                            <tbody>
                                <?php if($deals->count()): ?>
                                <?php $__currentLoopData = $deals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="text-black">
                                    <td><?php echo e(!is_null($deal->offer)?$loop->iteration:''); ?></td>
                                    <td><?php echo e(!is_null($deal->client)?$deal->client->name:''); ?></td>
                                    <td><?php echo e(!is_null($deal->advisor)?$deal->advisor->name:''); ?></td>
                                    <td><?php echo e(!is_null($deal->offer)?$deal->offer->offer_title:''); ?></td>
                                    <td><?php echo e($deal->description); ?></td>
                                    <td>
                                        <?php echo e(prettyPrice(!is_null($deal->deal_amout)?$deal->deal_amout:0)); ?>

                                    </td>
                                    <td>
                                        <?php echo e(prettyPrice(!is_null($deal-> commission_amount)?$deal-> commission_amount:0)); ?>

                                        <br>
                                        <?php echo e(prettyPricePercent(!is_null($deal-> commission_percentage)?$deal-> commission_percentage:0)); ?>

                                    </td>
                                    <td>
                                        <?php echo e(!is_null($deal->deal_start_date)?
                                                            \Carbon\Carbon::parse($deal->deal_start_date)->format('M-d-Y'):'-- -- --'); ?>

                                    </td>
                                    <td>
                                        <?php echo e(!is_null($deal->deal_end_date)?
                                                               \Carbon\Carbon::parse($deal->deal_end_date)->format('M-d-Y'):'-- -- --'); ?>

                                    </td>
                                    <td><?php echo e(!is_null($deal->status)?($deal->status == 1)?'Approved':'Ended':''); ?></td>
                                    <td><?php echo e($deal->appointment->appointment_number); ?></td>
                                    <td><?php echo e($deal->appointment->appointment_day); ?></td>
                                    <td><?php echo e($deal->appointment->appointment_status); ?></td>

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
</div>
</div>
</div>
<!-- content-wrapper ends -->

<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="<?php echo e(asset('js/nprogress.js')); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
    $(document).on("click", ".advisor_request_type_submit", function(e) {
        $selected = $('.advisor_request_type').val();
        $advisor = "<?php echo e(request()->id); ?>";
        data = {
            '_token': '<?php echo csrf_token() ?>',
            'types': $selected,
            'advisor': $advisor
        }
        e.preventDefault();
        $f = $('.advisor_request_type');

        var $sb = $f.find('[type="submit"]');
        var oldBtnText = $sb.html();

        $.ajax({
            beforeSend: function() {
                $sb.html(' Processing...').prop('disabled', true);
                // NProgress.set(0.4);
            },
            url: "<?php echo e(route('advisor-client-type.store')); ?>", //$f.attr('action'),
            type: 'POST',
            data: data, //$f.serialize(),
            dataType: 'JSON',
            success: function(data) {
                $sb.html(oldBtnText).prop('disabled', false);
                if (data.status) {
                    toastr.clear();
                    toastr.success(data.message, 'Success', {
                        timeOut: 5000
                    });
                    // location.reload(true);
                } else {
                    toastr.clear();
                    toastr.error(data.message, 'Error', {
                        timeOut: 5000
                    })
                }
            },
            error: function(xhr) {
                $sb.html(oldBtnText).prop('disabled', false);
                loadRequestErrors(xhr);
            }
        });
    });

    function loadRequestErrors(xhr) {
        if (xhr.message) {
            $.each(xhr.message, function(key, item) {
                toastr.clear();
                toastr.error(item, 'Error', {
                    timeOut: 5000
                });
            });
        } else if (xhr.responseJSON.message) {
            toastr.clear();
            toastr.error(xhr.responseJSON.message, 'Error', {
                timeOut: 5000
            });
        }
    }
</script>
<script>
    function format(d) {
        return 'Full name: ' + d.first_name + ' ' + d.last_name + '<br>' +
            'Salary: ' + d.salary + '<br>' +
            'The child row can contain any data you wish, including links, images, inner tables etc.';
    }

    $(document).ready(function() {
        var dt = $('#example').DataTable({
            /*"processing": true,
            "serverSide": true,
            "ajax": "scripts/ids-objects.php",*/
            /* "columns": [
                 {
                     "class":          "details-control",
                     "orderable":      false,
                     "data":           null,
                     "defaultContent": ""
                 },
                 { "data": "first_name" },
                 { "data": "last_name" },
                 { "data": "position" },
                 { "data": "office" }
             ],
             "order": [[1, 'asc']]*/
        });

        // Array to track the ids of the details displayed rows
        var detailRows = [];

        $('#example tbody').on('click', 'tr td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = dt.row(tr);
            var idx = $.inArray(tr.attr('id'), detailRows);

            if (row.child.isShown()) {
                tr.removeClass('details');
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            } else {
                tr.addClass('details');
                row.child(format(row.data())).show();

                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }
        });

        // On each draw, loop over the `detailRows` array and show any child rows
        dt.on('draw', function() {
            $.each(detailRows, function(i, id) {
                $('#' + id + ' td.details-control').trigger('click');
            });
        });
    });

    $(document).ready(function() {
        if ($('h4').hasClass('advisor-detail')) {
            $('videos').removeClass('active').addClass('fa-angle-up');
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/pages/admin/advisorsDeatail.blade.php ENDPATH**/ ?>