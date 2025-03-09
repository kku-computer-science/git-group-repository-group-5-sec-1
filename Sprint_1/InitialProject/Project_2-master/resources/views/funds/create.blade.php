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
</style>
<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <!-- <a class="btn btn-primary" href="{{ route('funds.index') }}"> Back </a> -->
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">เพิ่มทุนวิจัย</h4>
                <p class="card-description">กรอกข้อมูลรายละเอียดทุนงานวิจัย</p>
                <form class="forms-sample" id="fundForm" action="{{ route('funds.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="fund_type" class="col-sm-2">ประเภททุนวิจัย</label>
                        <div class="col-sm-4">
                            <select name="fund_type" class="custom-select my-select" id="fund_type" onchange='toggleDropdown(this);' required>
                                <option value="" disabled selected>---- โปรดระบุประเภททุน ----</option>
                                <option value="ทุนภายใน">ทุนภายใน</option>
                                <option value="ทุนภายนอก">ทุนภายนอก</option>
                            </select>
                        </div>
                    </div>
                    <div id="fund_code" style="display: none;">
                        <div class="form-group row">
                            <label for="fund_level" class="col-sm-2">ระดับทุน</label>
                            <div class="col-sm-4">
                                <select name="fund_level" class="custom-select my-select" id="fund_level" disabled required>
                                    <option value="" disabled selected>---- โปรดระบุระดับทุน ----</option>
                                    <option value="ไม่ระบุ">ไม่ระบุ</option>
                                    <option value="สูง">สูง</option>
                                    <option value="กลาง">กลาง</option>
                                    <option value="ล่าง">ล่าง</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="external_fund_details" style="display: none;">
                        <div class="form-group row">
                            <label for="external_fund_name" class="col-sm-2">ชื่อทุน (สำหรับทุนภายนอก)</label>
                            <div class="col-sm-8">
                                <input type="text" name="external_fund_name" id="external_fund_name" class="form-control" placeholder="ระบุชื่อทุนภายนอก" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fund_name" class="col-sm-2">ชื่อทุน</label>
                        <div class="col-sm-8">
                            <input type="text" name="fund_name" id="fund_name" class="form-control" placeholder="name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="support_resource" class="col-sm-2">หน่วยงานที่สนับสนุน / โครงการวิจัย</label>
                        <div class="col-sm-8">
                            <input type="text" name="support_resource" id="support_resource" class="form-control" placeholder="Support Resource" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary me-2" id="submitBtn" disabled>Submit</button>
                    <a class="btn btn-light" href="{{ route('funds.index')}}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        const fundCodeDiv = document.getElementById("fund_code");
        const fundLevelSelect = document.getElementById("fund_level");
        const externalFundDetailsDiv = document.getElementById("external_fund_details");
        const externalFundNameInput = document.getElementById("external_fund_name");
        const fundTypeSelect = document.getElementById("fund_type");
        const submitBtn = document.getElementById("submitBtn");
        const form = document.getElementById("fundForm");
        const fundNameInput = document.getElementById("fund_name");
        const supportResourceInput = document.getElementById("support_resource");
    
        function toggleDropdown(selObj) {
            // Show/hide fund level dropdown for "ทุนภายใน"
            if (selObj.value === "ทุนภายใน") {
                fundCodeDiv.style.display = "block";
                fundLevelSelect.disabled = false;
                externalFundDetailsDiv.style.display = "none";
                externalFundNameInput.disabled = true;
                externalFundNameInput.value = ""; // Reset external fund name
            } 
            // Show/hide external fund details for "ทุนภายนอก"
            else if (selObj.value === "ทุนภายนอก") {
                fundCodeDiv.style.display = "none";
                fundLevelSelect.disabled = true;
                fundLevelSelect.value = ""; // Reset fund level
                externalFundDetailsDiv.style.display = "block";
                externalFundNameInput.disabled = false;
            } 
            else {
                fundCodeDiv.style.display = "none";
                fundLevelSelect.disabled = true;
                fundLevelSelect.value = "";
                externalFundDetailsDiv.style.display = "none";
                externalFundNameInput.disabled = true;
                externalFundNameInput.value = "";
            }
            checkFormValidity();
        }
    
        function checkFormValidity() {
            let isValid = true;
    
            // Check if fund type is selected
            if (!fundTypeSelect.value) {
                isValid = false;
            }
    
            // Check fund level if fund type is "ทุนภายใน"
            if (fundTypeSelect.value === "ทุนภายใน" && !fundLevelSelect.value) {
                isValid = false;
            }
    
            // Check external fund name if fund type is "ทุนภายนอก"
            if (fundTypeSelect.value === "ทุนภายนอก" && !externalFundNameInput.value.trim()) {
                isValid = false;
            }
    
            // Check if fund name and support resource are filled
            if (!fundNameInput.value.trim() || !supportResourceInput.value.trim()) {
                isValid = false;
            }
    
            // Enable/disable submit button
            submitBtn.disabled = !isValid;
        }
    
        // Add event listeners to all form inputs
        fundTypeSelect.addEventListener('change', toggleDropdown);
        fundLevelSelect.addEventListener('change', checkFormValidity);
        externalFundNameInput.addEventListener('input', checkFormValidity);
        fundNameInput.addEventListener('input', checkFormValidity);
        supportResourceInput.addEventListener('input', checkFormValidity);
    
        // Initial check
        checkFormValidity();
    </script>
</script>
@endsection