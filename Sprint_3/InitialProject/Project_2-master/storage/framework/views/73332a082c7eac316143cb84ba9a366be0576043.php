

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card col-md-8" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">Fund Detail</h4>
            <p class="card-description">ข้อมูลรายละเอียดทุน</p>
            
            <div class="row">
                <p class="card-text col-sm-3"><b>ชื่อทุน</b></p>
                <p class="card-text col-sm-9"><?php echo e($fund->fund_name ?? '-'); ?></p>
            </div>

            <div class="row">
                <p class="card-text col-sm-3"><b>ประเภททุน</b></p>
                <p class="card-text col-sm-9"><?php echo e($fund->category->fundType->name ?? '-'); ?></p>
            </div>

            <div class="row">
                <p class="card-text col-sm-3"><b>ลักษณะทุน</b></p>
                <p class="card-text col-sm-9"><?php echo e($fund->category->name ?? '-'); ?></p>
            </div>

            <div class="row">
                <p class="card-text col-sm-3"><b>ปี</b></p>
                <p class="card-text col-sm-9"><?php echo e($fund->fund_year ?? '-'); ?></p>
            </div>

            <div class="row">
                <p class="card-text col-sm-3"><b>รายละเอียดทุน</b></p>
                <p class="card-text col-sm-9"><?php echo e($fund->fund_details ?? '-'); ?></p>
            </div>

            <div class="row">
                <p class="card-text col-sm-3"><b>หน่วยงานที่สนับสนุน</b></p>
                <p class="card-text col-sm-9"><?php echo e($fund->support_resource ?? '-'); ?></p>
            </div>

            <div class="row">
                <p class="card-text col-sm-3"><b>หน่วยงาน</b></p>
                <p class="card-text col-sm-9"><?php echo e($fund->fund_agency ?? '-'); ?></p>
            </div>

            <div class="row">
                <p class="card-text col-sm-3"><b>เพิ่มรายละเอียดโดย</b></p>
                <p class="card-text col-sm-9">
                    <?php echo e($fund->user ? $fund->user->fname_th . ' ' . $fund->user->lname_th : '-'); ?>

                </p>
            </div>

            <div class="pull-right mt-5">
                <a class="btn btn-primary btn-sm" href="<?php echo e(route('funds.index')); ?>">กลับ</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboards.users.layouts.user-dash-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8-3-68\git-group-repository-group-5-sec-1\Sprint_3\InitialProject\Project_2-master\resources\views/funds/show.blade.php ENDPATH**/ ?>