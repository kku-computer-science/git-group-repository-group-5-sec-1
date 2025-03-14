@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
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
    @keyframes shake {
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
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>เพิ่มทุนไม่สำเร็จ</strong> <br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">เพิ่มทุนวิจัย</h4>
                <p class="card-description">กรอกข้อมูลรายละเอียดทุนงานวิจัย</p>
                <form class="forms-sample" action="{{ route('funds.store') }}" method="POST">
                    @csrf

                    <!-- ประเภททุนวิจัย -->
                    <div class="form-group row">
                        <label for="funds_type_id" class="col-sm-2">
                            ประเภททุนวิจัย<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-4">
                            <select name="funds_type_id" class="custom-select my-select" id="funds_type" >
                                <option value="">---- โปรดระบุประเภททุน ----</option>
                                @foreach($fundType as $type)
                                    <option value="{{ $type->id }}" {{ old('funds_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <!-- ลักษณะทุน -->
                    <div class="form-group row">
                        <label for="fund_cate" class="col-sm-2">
                            ลักษณะทุน<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-4">
                            <select name="fund_cate" class="custom-select my-select" id="funds_category" >
                                <option value="">---- โปรดระบุลักษณะทุน ----</option>
                            </select>
                            <span class="tooltip-text">กรุณาเลือกประเภททุนก่อน</span>

                        </div>
                    </div>

                    <!-- ชื่อทุน -->
                    <div class="form-group row">
                        <label for="fund_name" class="col-sm-2">
                            ชื่อทุน<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="fund_name" class="form-control" placeholder="name" value="{{ old('fund_name') }}" >

                        </div>
                    </div>

                    <!-- หน่วยงานที่สนับสนุน -->
                    <div class="form-group row">
                        <label for="support_resource" class="col-sm-2">
                            หน่วยงานที่สนับสนุน / โครงการวิจัย<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="support_resource" class="form-control" placeholder="Support Resource" value="{{ old('support_resource') }}" >

                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                    <a class="btn btn-light" href="{{ route('funds.index')}}">Cancel</a>
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
    const oldFundTypeId = @json(old('funds_type_id'));
    const oldFundCate = @json(old('fund_cate'));

    // ปิด select ของ category ไว้ก่อน (เปิดเฉพาะกรณีที่มีการเลือกประเภททุนไว้แล้ว)
    if (!oldFundTypeId) {
        categorySelect.disabled = true;
    } else {
        categorySelect.disabled = false;
        // ถ้ามีการส่งค่าเก่ากลับมา ให้โหลดหมวดหมู่ตามประเภททุนที่เลือกไว้
        loadCategories(oldFundTypeId);
    }

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
            // เคลียร์ตัวเลือกใน category select
            categorySelect.innerHTML = '<option value="">---- โปรดระบุลักษณะทุน ----</option>';
        }
    });

    function loadCategories(fundTypeId) {
        fetch(`/getFundsCategory/${fundTypeId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                categorySelect.innerHTML = '<option value="">---- โปรดระบุลักษณะทุน ----</option>';

                if (data && data.length > 0) {
                    data.forEach(category => {
                        const selected = category.id == oldFundCate ? 'selected' : '';
                        categorySelect.innerHTML += `<option value="${category.id}" ${selected}>${category.name}</option>`;
                    });
                } else {
                    console.log('No categories found for this fund type');
                }
            })
            .catch(error => {
                console.error('Error loading categories:', error);
                alert('เกิดข้อผิดพลาดในการโหลดข้อมูลลักษณะทุน: ' + error.message);
            });
    }

    // Ensure form maintains state after validation error
    const formElement = document.querySelector('.forms-sample');

    // Add validation listener before form submission
    formElement.addEventListener('submit', function(e) {
        // Store current selections in session storage in case of validation errors
        sessionStorage.setItem('lastFundTypeId', fundTypeSelect.value);
        sessionStorage.setItem('lastFundCateId', categorySelect.value);
    });

    // Check if we're returning after validation error (old values exist)
    if (oldFundTypeId) {
        // Make sure category select is enabled
        categorySelect.disabled = false;
        tooltipText.style.visibility = 'hidden';
        tooltipText.style.opacity = '0';
    }
});
</script>
@endsection
