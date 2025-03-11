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
</style>

<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>แก้ไขไม่สำเร็จ</strong> <br><br>
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
                            <select name="funds_type_id" class="custom-select my-select" id="funds_type" >
                                <option value="">---- โปรดระบุประเภททุน ----</option>
                                @foreach($fundType as $type)
                                    <option value="{{ $type->id }}" {{ $fund->category->fund_type_id == $type->id ? 'selected' : '' }}>
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
                            <select name="fund_cate" class="custom-select my-select" id="funds_category">
                                <option value="">---- โปรดระบุลักษณะทุน ----</option>
                                @foreach($fundCategory as $category)
                                    <option value="{{ $category->id }}" {{ $fund->fund_cate == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <!-- ชื่อทุน -->
                    <div class="form-group row">
                        <label for="fund_name" class="col-sm-2">
                            ชื่อทุน<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="fund_name" value="{{ $fund->fund_name }}" class="form-control" >

                        </div>
                    </div>

                    <!-- หน่วยงานที่สนับสนุน -->
                    <div class="form-group row">
                        <label for="support_resource" class="col-sm-2">
                            หน่วยงานที่สนับสนุน<span class="required-star">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="support_resource" value="{{ $fund->support_resource }}" class="form-control" >

                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">บันทึก</button>
                    <a class="btn btn-light" href="{{ route('funds.index')}}">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fundTypeSelect = document.getElementById('funds_type');
    const categorySelect = document.getElementById('funds_category');

    // Get old input values or use current fund values
    const oldFundTypeId = @json(old('funds_type_id', $fund->category->fund_type_id ?? null));
    const oldFundCate = @json(old('fund_cate', $fund->fund_cate ?? null));

    // Set initial state
    if (oldFundTypeId) {
        // If returning after validation error, ensure the right fund type is selected
        if (fundTypeSelect.value !== oldFundTypeId) {
            fundTypeSelect.value = oldFundTypeId;
        }

        // Load categories for the selected fund type
        loadCategories(oldFundTypeId);
    }

    // Add event listener for fund type changes
    fundTypeSelect.addEventListener('change', function() {
        if (this.value) {
            loadCategories(this.value);
        } else {
            // Reset category dropdown if no fund type selected
            categorySelect.innerHTML = '<option value="">---- โปรดระบุลักษณะทุน ----</option>';
            categorySelect.disabled = true;
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
                        // Use old input value or current fund value for selection
                        const selected = category.id == oldFundCate ? 'selected' : '';
                        categorySelect.innerHTML += `<option value="${category.id}" ${selected}>${category.name}</option>`;
                    });
                } else {
                    console.log('No categories found for this fund type');
                }
                categorySelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading categories:', error);
                // No alert - just log to console
            });
    }

    // Store form state before submission
    const formElement = document.querySelector('.forms-sample');
    formElement.addEventListener('submit', function() {
        // Store last selections in case of validation error
        sessionStorage.setItem('editFundTypeId', fundTypeSelect.value);
        sessionStorage.setItem('editFundCateId', categorySelect.value);
    });
});
</script>
@endsection
