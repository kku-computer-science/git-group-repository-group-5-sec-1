
<?php $__env->startSection('content'); ?>
<style>
    .my-select {
        background-color: #fff;
        color: #212529;
        border: #000 0.2 solid;
        border-radius: 10px;
        padding: 4px 10px;
        width: 100%;
        font-size: 14px;
    }
    .required-star {
        color: red;
    }
</style>

<div class="container">
    <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <strong>แก้ไขไม่สำเร็จ</strong> <br><br>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">แก้ไขทุนวิจัย</h4>
                <p class="card-description">กรอกข้อมูลแก้ไขรายละเอียดทุนงานวิจัย</p>
                <form class="forms-sample" action="<?php echo e(route('funds.update', $fund->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <!-- ประเภททุนวิจัย -->
                    <div class="form-group row">
                        <label for="funds_type_id" class="col-sm-2">
                            ประเภททุนวิจัย<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-4">
                            <select name="funds_type_id" class="custom-select my-select" id="funds_type" required>
                                <option value="">---- โปรดระบุประเภททุน ----</option>
                                <?php $__currentLoopData = $fundType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>" <?php echo e($fund->category->fund_type_id == $type->id ? 'selected' : ''); ?>>
                                        <?php echo e($type->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['funds_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- ลักษณะทุน -->
                    <div class="form-group row">
                        <label for="fund_cate" class="col-sm-2">
                            ลักษณะทุน<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-4">
                            <select name="fund_cate" class="custom-select my-select" id="funds_category" required>
                                <option value="">---- โปรดระบุลักษณะทุน ----</option>
                                <?php $__currentLoopData = $fundCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e($fund->fund_cate == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['fund_cate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- ชื่อทุน -->
                    <div class="form-group row">
                        <label for="fund_name" class="col-sm-2">
                            ชื่อทุน<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="fund_name" value="<?php echo e($fund->fund_name); ?>" class="form-control" required>
                            <?php $__errorArgs = ['fund_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- หน่วยงานที่สนับสนุน -->
                    <div class="form-group row">
                        <label for="support_resource" class="col-sm-2">
                            หน่วยงานที่สนับสนุน<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="support_resource" value="<?php echo e($fund->support_resource); ?>" class="form-control" required>
                            <?php $__errorArgs = ['support_resource'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">บันทึก</button>
                    <a class="btn btn-light" href="<?php echo e(route('funds.index')); ?>">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fundTypeSelect = document.getElementById('funds_type');
    const categorySelect = document.getElementById('funds_category');
    
    fundTypeSelect.addEventListener('change', function() {
        if (this.value) {
            loadCategories(this.value);
        } else {
            categorySelect.innerHTML = '<option value="">---- โปรดระบุลักษณะทุน ----</option>';
            categorySelect.disabled = true;
        }
    });

    function loadCategories(fundTypeId) {
        fetch(`/getFundsCategory/${fundTypeId}`)
            .then(response => response.json())
            .then(data => {
                categorySelect.innerHTML = '<option value="">---- โปรดระบุลักษณะทุน ----</option>';
                data.forEach(category => {
                    const selected = category.id == <?php echo e($fund->fund_cate); ?> ? 'selected' : '';
                    categorySelect.innerHTML += `<option value="${category.id}" ${selected}>${category.name}</option>`;
                });
                categorySelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการโหลดข้อมูลลักษณะทุน');
            });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboards.users.layouts.user-dash-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8-3-68\git-group-repository-group-5-sec-1\Sprint_3\InitialProject\Project_2-master\resources\views/funds/edit.blade.php ENDPATH**/ ?>