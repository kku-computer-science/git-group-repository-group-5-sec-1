
<?php $__env->startSection('content'); ?>

<div class="container refund">
    <p>โครงการบริการวิชาการ/ โครงการวิจัย</p>

    <div class="table-refund table-responsive">
        <table id="example1" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th style="font-weight: bold;">ลำดับ</th>
                    <th class="col-md-1" style="font-weight: bold;">ปี</th>
                    <th class="col-md-4" style="font-weight: bold;">ชื่อโครงการ </th>
                    <!-- <th>ระยะเวลาโครงการ</th>
                    <th>ผู้รับผิดชอบโครงการ</th>
                    <th>ประเภททุนวิจัย</th>
                    <th>หน่วยงานที่สนันสนุนทุน</th>
                    <th>งบประมาณที่ได้รับจัดสรร</th> -->
                    <th class="col-md-4" style="font-weight: bold;">รายละเอียด</th>
                    <th class="col-md-2" style="font-weight: bold;">ผู้รับผิดชอบโครงการ</th>
                    <!-- <th class="col-md-5">หน่วยงานที่รับผิดชอบ</th> -->
                    <th class="col-md-1" style="font-weight: bold;">สถานะ</th>
                </tr>
            </thead>

            
            <tbody>
                <?php $__currentLoopData = $resp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $re): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="vertical-align: top;text-align: left;"><?php echo e($i+1); ?></td>
                    <td style="vertical-align: top;text-align: left;"><?php echo e(($re->project_year)+543); ?></td>
                    <td style="vertical-align: top;text-align: left;">
                        <?php echo e($re->project_name); ?>


                    </td>
                    <td>
                        <div style="padding-bottom: 10px">

                            <?php if($re->project_start != null): ?>
                            <span style="font-weight: bold;">
                                ระยะเวลาโครงการ
                            </span>
                            <span style="padding-left: 10px;">
                                <?php echo e(\Carbon\Carbon::parse($re->project_start)->thaidate('j F Y')); ?> ถึง <?php echo e(\Carbon\Carbon::parse($re->project_end)->thaidate('j F Y')); ?>

                            </span>
                            <?php else: ?>
                            <span style="font-weight: bold;">
                                ระยะเวลาโครงการ
                            </span>
                            <span>

                            </span>
                            <?php endif; ?>
                        </div>


                        <!-- <?php if($re->project_start != null): ?>
                    <td><?php echo e(\Carbon\Carbon::parse($re->project_start)->thaidate('j F Y')); ?><br>ถึง <?php echo e(\Carbon\Carbon::parse($re->project_end)->thaidate('j F Y')); ?></td>
                    <?php else: ?>
                    <td></td>
                    <?php endif; ?> -->

                        <!-- <td><?php $__currentLoopData = $re->user; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($user->position); ?><?php echo e($user->fname_th); ?> <?php echo e($user->lname_th); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td> -->
                        <!-- <td>
                        <?php if(is_null($re->fund)): ?>
                        <?php else: ?>
                        <?php echo e($re->fund->fund_type); ?>

                        <?php endif; ?>
                    </td> -->
                        <!-- <td><?php if(is_null($re->fund)): ?>
                        <?php else: ?>
                        <?php echo e($re->fund->support_resource); ?>

                        <?php endif; ?>
                    </td> -->
                        <!-- <td><?php echo e($re->budget); ?></td> -->
                        <div style="padding-bottom: 10px;">
                            <span style="font-weight: bold;">ประเภททุนวิจัย</span>
                            <span style="padding-left: 10px;"> <?php if(is_null($re->fund)): ?>
                                <?php else: ?>
                                <?php echo e($re->fund->fund_type); ?>

                                <?php endif; ?></span>
                        </div>
                        <div style="padding-bottom: 10px;">
                            <span style="font-weight: bold;">หน่วยงานที่สนันสนุนทุน</span>
                            <span style="padding-left: 10px;"> <?php if(is_null($re->fund)): ?>
                                <?php else: ?>
                                <?php echo e($re->fund->support_resource); ?>

                                <?php endif; ?></span>
                        </div>
                        <div style="padding-bottom: 10px;">
                            <span style="font-weight: bold;">หน่วยงานที่รับผิดชอบ</span>
                            <span style="padding-left: 10px;">
                                <?php echo e($re->responsible_department); ?>

                            </span>
                        </div>
                        <div style="padding-bottom: 10px;">

                            <span style="font-weight: bold;">งบประมาณที่ได้รับจัดสรร</span>
                            <span style="padding-left: 10px;"> <?php echo e(number_format($re->budget)); ?> บาท</span>
                        </div>
                    </td>

                    <td style="vertical-align: top;text-align: left;">
                        <div style="padding-bottom: 10px;">
                            <span><?php $__currentLoopData = $re->user; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($user->position_th); ?> <?php echo e($user->fname_th); ?> <?php echo e($user->lname_th); ?><br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></span>
                        </div>
                    </td>
                    <?php if($re->status == 1): ?>
                    <td style="vertical-align: top;text-align: left;">
                        <h6><label class="badge badge-success">ยื่นขอ</label></h6>
                    </td>
                    <?php elseif($re->status == 2): ?>
                    <td style="vertical-align: top;text-align: left;">
                        <h6><label class="badge bg-warning text-dark">ดำเนินการ</label></h6>
                    </td>
                    <?php else: ?>
                    <td style="vertical-align: top;text-align: left;">
                        <h6><label class="badge bg-dark">ปิดโครงการ</label>
                            <h6>
                    </td>
                    <?php endif; ?>
                    <!-- <td></td>
                    <td></td> -->
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

</div>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

<script>
    $(document).ready(function() {

        var table1 = $('#example1').DataTable({
            responsive: true,
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\soft en\git-group-repository-group-5-sec-1\InitialProject\Source_Code\resources\views/research_proj.blade.php ENDPATH**/ ?>