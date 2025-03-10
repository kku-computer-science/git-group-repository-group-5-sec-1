

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
    #funds_category:disabled {
        cursor: not-allowed;
    }
    #funds_category:disabled:hover {
        color: red;
    }
    #funds_category:not(:disabled) {
        cursor: pointer;
        color: inherit;
    }
    .shake-animation {
        animation: shake 0.2s ease-in-out;
    }
    @keyframes  shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .tooltip-text {
        visibility: hidden;
        width: auto;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 10px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .col-sm-4 {
        position: relative;
    }
    #funds_category:disabled:hover + .tooltip-text {
        visibility: visible;
        opacity: 1;
    }
</style>

<div class="container">
    <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <strong>Fund created unsuccesful</strong> <br><br>
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
                <h4 class="card-title">เพิ่มทุนวิจัย</h4>
                <p class="card-description">กรอกข้อมูลรายละเอียดทุนงานวิจัย</p>
                <form class="forms-sample" action="<?php echo e(route('funds.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <!-- ประเภททุนวิจัย -->
                    <div class="form-group row">
                        <label for="funds_type_id" class="col-sm-2">
                            ประเภททุนวิจัย<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-4">
                            <select name="funds_type_id" class="custom-select my-select" id="funds_type" required>
                                <option value="">---- โปรดระบุประเภททุน ----</option>
                                <?php $__currentLoopData = $fundType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>" <?php echo e(old('funds_type_id') == $type->id ? 'selected' : ''); ?>>
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
                            </select>
                            <span class="tooltip-text">กรุณาเลือกประเภททุนก่อน</span>
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
                            <input type="text" name="fund_name" class="form-control" placeholder="name" value="<?php echo e(old('fund_name')); ?>" required>
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
                            หน่วยงานที่สนับสนุน / โครงการวิจัย<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="support_resource" class="form-control" placeholder="Support Resource" value="<?php echo e(old('support_resource')); ?>" required>
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

                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                    <a class="btn btn-light" href="<?php echo e(route('funds.index')); ?>">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fundTypeSelect = document.getElementById('funds_type');
    const categorySelect = document.getElementById('funds_category');
    const tooltipText = document.querySelector('.tooltip-text');
    const oldFundCate = <?php echo json_encode(old('fund_cate'), 15, 512) ?>;
    
    // ปิด select ของ category ไว้ก่อน
    categorySelect.disabled = true;
    
    // เพิ่ม event listener เมื่อคลิกที่ category
    categorySelect.addEventListener('mousedown', function(e) {
        if (fundTypeSelect.value === '') {
            e.preventDefault();
            this.classList.add('shake-animation');
            alert('กรุณาเลือกประเภททุนวิจัยก่อน');
            
            setTimeout(() => {
                this.classList.remove('shake-animation');
            }, 200);
        }
    });
    
    // เพิ่ม event listener เมื่อเลือก fund type
    fundTypeSelect.addEventListener('change', function() {
        if (this.value) {
            categorySelect.disabled = false;
            categorySelect.style.color = 'inherit'; 
            tooltipText.style.visibility = 'hidden'; 
            tooltipText.style.opacity = '0';
            categorySelect.classList.remove('disabled-select');
            loadCategories(this.value);
        } else {
            categorySelect.disabled = true;
            tooltipText.style.visibility = 'visible';
            tooltipText.style.opacity = '1';
            categorySelect.classList.add('disabled-select');
        }
    });

    function loadCategories(fundTypeId) {
        fetch(`/getFundsCategory/${fundTypeId}`)
            .then(response => response.json())
            .then(data => {
                categorySelect.innerHTML = '<option value="">---- โปรดระบุลักษณะทุน ----</option>';
                data.forEach(category => {
                    categorySelect.innerHTML += `<option value="${category.id}" ${category.id == oldFundCate ? 'selected' : ''}>${category.name}</option>`;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการโหลดข้อมูลลักษณะทุน');
            });
    }

    // Load categories if old fund type is selected
    if (fundTypeSelect.value) {
        loadCategories(fundTypeSelect.value);
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboards.users.layouts.user-dash-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8-3-68\git-group-repository-group-5-sec-1\Sprint_3\InitialProject\Project_2-master\resources\views/funds/create.blade.php ENDPATH**/ ?>