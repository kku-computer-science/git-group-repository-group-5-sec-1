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
<<<<<<< HEAD
</style>
<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
=======
    .required-star {
        color: red;
    }
</style>

<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>แก้ไขไม่สำเร็จ</strong> <br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
>>>>>>> Nantapong_1341
            @endforeach
        </ul>
    </div>
    @endif
<<<<<<< HEAD
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card" style="padding: 16px;">
            <div class="card-body">
                <h4 class="card-title">Edit Fund</h4>
                <p class="card-description">กรอกข้อมูลแก้ไขรายละเอียดทุนงานวิจัย</p>
                <form class="forms-sample" action="{{ route('funds.update',$fund->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <p class="col-sm-3 "><b>ประเภททุนวิจัย</b></p>
                        <!-- <label for="exampleInputfund_type" class="col-sm-2 ">ประเภททุนวิจัย</label> -->
                        <div class="col-sm-4">
                            <select name="fund_type" class="custom-select my-select" id="fund_type" onchange='toggleDropdown(this);' required>
                                <option value="ทุนภายใน" {{ $fund->fund_type == 'ทุนภายใน' ? 'selected' : '' }}>ทุนภายใน</option>
                                <option value="ทุนภายนอก" {{ $fund->fund_type == 'ทุนภายนอก' ? 'selected' : '' }}>ทุนภายนอก</option>
                            </select>
                        </div>
                    </div>
                    <div id="fund_code">
                        <div class="form-group row">
                            <p class="col-sm-3"><b>ระดับทุน</b></p>
                            <div class="col-sm-4">
                                <select name="fund_level" class="custom-select my-select">
                                    <option value=""{{ $fund->fund_level == '' ? 'selected' : '' }}>ไม่ระบุ</option>
                                    <option value="สูง" {{ $fund->fund_level == 'สูง' ? 'selected' : '' }}>สูง</option>
                                    <option value="กลาง" {{ $fund->fund_level == 'กลาง' ? 'selected' : '' }}>กลาง</option>
                                    <option value="ล่าง" {{ $fund->fund_level == 'ล่าง' ? 'selected' : '' }}>ล่าง</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-3 "><b>ชื่อทุน</b></p>
                        <div class="col-sm-8">
                            <input type="text" name="fund_name" value="{{ $fund->fund_name }}" class="form-control" placeholder="fund_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-3 "><b>หน่วยงานที่สนับสนุน / โครงการวิจัย</b></p>
                        <div class="col-sm-8">
                            <input type="text" name="support_resource" value="{{ $fund->support_resource }}" class="form-control" placeholder="Support Resource">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-5">Submit</button>
                    <a class="btn btn-light mt-5" href="{{ route('funds.index')}}">Cancel</a>
=======

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">แก้ไขทุนวิจัย</h4>
                <p class="card-description">กรอกข้อมูลแก้ไขรายละเอียดทุนงานวิจัย</p>
                <form class="forms-sample" action="{{ route('funds.update', $fund->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- ประเภททุนวิจัย -->
                    <div class="form-group row">
                        <label for="funds_type_id" class="col-sm-2">
                            ประเภททุนวิจัย<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-4">
                            <select name="funds_type_id" class="custom-select my-select" id="funds_type" required>
                                <option value="">---- โปรดระบุประเภททุน ----</option>
                                @foreach($fundType as $type)
                                    <option value="{{ $type->id }}" {{ $fund->category->fund_type_id == $type->id ? 'selected' : '' }}>
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
                                @foreach($fundCategory as $category)
                                    <option value="{{ $category->id }}" {{ $fund->fund_cate == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
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
                            <input type="text" name="fund_name" value="{{ $fund->fund_name }}" class="form-control" required>
                            @error('fund_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- หน่วยงานที่สนับสนุน -->
                    <div class="form-group row">
                        <label for="support_resource" class="col-sm-2">
                            หน่วยงานที่สนับสนุน<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="support_resource" value="{{ $fund->support_resource }}" class="form-control" required>
                            @error('support_resource')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">บันทึก</button>
                    <a class="btn btn-light" href="{{ route('funds.index')}}">ยกเลิก</a>
>>>>>>> Nantapong_1341
                </form>
            </div>
        </div>
    </div>
<<<<<<< HEAD

</div>

<script>
    const ac = document.getElementById("fund_code");
    const ab = document.getElementById("fund_type").value;
    //console.log(ab);
    if (ab === "ทุนภายนอก") {
        ac.style.display = "none";
    }

    //ac.style.display = "none";

    function toggleDropdown(selObj) {
        ac.style.display = selObj.value === "ทุนภายใน" ? "block" : "none";
    }
=======
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
                    const selected = category.id == {{ $fund->fund_cate }} ? 'selected' : '';
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
>>>>>>> Nantapong_1341
</script>
@endsection