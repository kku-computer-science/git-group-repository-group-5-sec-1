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
        <strong>Fund created unsuccesful</strong> <br><br>
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
                            <select name="funds_type_id" class="custom-select my-select" id="funds_type" required>
                                <option value="">---- โปรดระบุประเภททุน ----</option>
                                @foreach($fundType as $type)
                                    <option value="{{ $type->id }}" {{ old('funds_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('funds_type_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
                            @error('fund_cate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- ชื่อทุน -->
                    <div class="form-group row">
                        <label for="fund_name" class="col-sm-2">
                            ชื่อทุน<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="fund_name" class="form-control" placeholder="name" value="{{ old('fund_name') }}" required>
                            @error('fund_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- หน่วยงานที่สนับสนุน -->
                    <div class="form-group row">
                        <label for="support_resource" class="col-sm-2">
                            หน่วยงานที่สนับสนุน / โครงการวิจัย<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="support_resource" class="form-control" placeholder="Support Resource" value="{{ old('support_resource') }}" required>
                            @error('support_resource')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
    const oldFundCate = @json(old('fund_cate'));

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
@endsection
