@extends('dashboards.users.layouts.user-dash-layout')
<!-- <link  rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> -->
@section('content')

<style>
    .my-select {
        background-color: #fff;
        color: #212529;
        border: #000 0.2 solid;
        border-radius: 5px;
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
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card" style="padding: 16px;">
            <div class="card-body">
                <h4 class="card-title">เพิ่มข้อมูลโครงการวิจัย</h4>
                <p class="card-description">กรอกข้อมูลรายละเอียดโครงการวิจัย</p>
                <form action="{{ route('researchProjects.store') }}" method="POST">
                    @csrf
                    <div class="form-group row mt-5">
                        <label for="exampleInputfund_name" class="col-sm-2 ">ชื่อโครงการวิจัย <span class="text-danger fw-bold">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" name="project_name" class="form-control" placeholder="ชื่อโครงการวิจัย" value="{{ old('project_name') }}">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_name" class="col-sm-2 ">วันที่เริ่มต้น <span class="text-danger fw-bold">*</span></label>
                        <div class="col-sm-4">
                            <input type="date" name="project_start" id="Project_start" class="form-control" value="{{ old('project_start') }}">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_name" class="col-sm-2 ">วันที่สิ้นสุด <span class="text-danger fw-bold">*</span></label>
                        <div class="col-sm-4">
                            <input type="date" name="project_end" id="Project_end" class="form-control" value="{{ old('project_end') }}">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_details" class="col-sm-2 ">เลือกประเภททุน <span class="text-danger fw-bold">*</span></label>
                        <div class="col-sm-4">
                            <select name="funds_type_id" class="custom-select my-select" id="funds_type" >
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
                        <label for="funds_category" class="col-sm-2">ลักษณะทุน <span class="text-danger fw-bold">*</span></label>
                        <div class="col-sm-4">
                            <select name="fund_cate" id="funds_category" class="custom-select my-select" >
                                <option value="">-- โปรดระบุลักษณะทุน --</option>
                            </select>
                            <p class="tooltip-text mt-1">กรุณาเลือกประเภททุนก่อน</p>
                            @error('fund_cate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="funds" class="col-sm-2">ทุน <span class="text-danger fw-bold">*</span></label>
                        <div class="col-sm-4">
                            <select name="fund" id="funds" class="custom-select my-select">
                                <option value="">-- โปรดเลือกทุน --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputproject_year" class="col-sm-2 ">ปีที่ยื่น (ค.ศ.) <span class="text-danger fw-bold">*</span></label>
                        <div class="col-sm-4">
                            <input type="year" name="project_year" class="form-control" placeholder="year">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_name" class="col-sm-2 ">งบประมาณ <span class="text-danger fw-bold">*</span></label>
                        <div class="col-sm-4">
                            <input type="number" name="budget" class="form-control" placeholder="หน่วยบาท" value="{{ old('budget') }}">
                        </div>
                        <div class="col-sm-2 d-flex align-items-center">
                            <input type="hidden" name="show_budget" id="show_budget" value="0">
                            <input type="checkbox" id="show_budget_checkbox">
                            <label class="ms-2 mt-2">แสดงงบประมาณ</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputfund_name" class="col-sm-2 "></label>
                        <div class="col-sm-10">
                            <p id="budget_message" class="text-muted">
                                งบประมาณจะ<strong>ไม่แสดง</strong>ในหน้า โครงการบริการวิชาการ/ โครงการวิจัย หากต้องการให้แสดง โปรดทำเครื่องหมายในช่อง แสดงงบประมาณ
                            </p>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label for="exampleInputresponsible_department" class="col-sm-2 ">หน่วยงานที่รับผิดชอบ <span class="text-danger fw-bold">*</span></label>
                        <div class="col-sm-9">
                            <select id='dep' style='width: 200px;' class="custom-select my-select" name="responsible_department">
                                @foreach($deps as $dep)
                                <option disable selected value="{{ $dep->id }}">{{ $dep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_details" class="col-sm-2 ">รายละเอียดโครงการ</label>
                        <div class="col-sm-9">
                            <textarea type="text" name="note" class="form-control form-control-lg" style="height:150px" placeholder="Note" value="{{ old('note') }}"></textarea>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="exampleInputstatus" class="col-sm-2 ">สถานะ <span class="text-danger fw-bold">*</span></label>
                        <div class="col-sm-3">
                            <input type="hidden" name="status" id="hidden_status" value="">
                            <select id='status' class="custom-select my-select">
                                <option value="" disabled selected>โปรดระบุสถานะดำเนินงาน</option>
                                <option value="2">ดำเนินการ</option>
                                <option value="3">ปิดโครงการ</option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label for="exampleInputstatus" class="col-sm-2 ">สถานะ</label>
                        <div class="col-sm-8">
                            <select name="status" class="form-control" id="status">
                                <option value="1">ยื่นขอ</option>
                                <option value="2">ดำเนินการ</option>
                                <option value="3">ปิดโครงการ</option>
                            </select>
                        </div>
                    </div> -->

                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_details" class="col-sm-2 ">ผู้รับผิดชอบโครงการ <span class="text-danger fw-bold">*</span></label>
                        <div class="col-sm-9">
                            <select id='head0' style='width: 200px;' name="head">
                                <option value=''>Select User</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->fname_th }} {{ $user->lname_th }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_details" class="col-sm-2 ">ผู้รับผิดชอบโครงการ (ร่วม) ภายใน</label>
                        <div class="col-sm-9">
                            <table class="table" id="dynamicAddRemove">
                                <tr>
                                    <th><button type="button" name="add" id="add-btn2" class="btn btn-success btn-sm add"><i class="mdi mdi-plus"></i></button></th>
                                </tr>
                                <tr>
                                    <!-- <td><input type="text" name="moreFields[0][Budget]" placeholder="Enter title" class="form-control" /></td> -->
                                    <td><select id='selUser0' style='width: 200px;' name="moreFields[0][userid]">
                                            <option value=''>Select User</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->fname_th }} {{ $user->lname_th }}</option>
                                            @endforeach
                                        </select></td>

                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="form-group row mt-2">
                        <label for="exampleInputpaper_doi" class="col-sm-2 ">ผู้รับผิดชอบโครงการ (ร่วม) ภายนอก</label>
                        <div class="col-sm-9">
                            <div class="table-responsive">
                                <table class="table table-bordered w-75" id="dynamic_field">
                                    <tr>
                                        <td>
                                            <p>ตำแหน่งหรือคำนำหน้า :</p><input type="text" name="title_name[]" placeholder="ตำแหน่งหรือคำนำหน้า" style='width: 200px;' class="form-control name_list" /><br>
                                            <p>ชื่อ :</p><input type="text" name="fname[]" placeholder="ชื่อ" style='width: 300px;' class="form-control name_list" /><br>
                                            <p>นามสกุล :</p><input type="text" name="lname[]" placeholder="นามสกุล" style='width: 300px;' class="form-control name_list" />
                                        </td>
                                        <td><button type="button" name="add" id="add" class="btn btn-success btn-sm"><i class="mdi mdi-plus"></i></button>
                                        </td>
                                    </tr>
                                </table>
                                <!-- <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />
                            </div>
                        </div>
                    </div> -->
                    <div class="form-group row mt-2">
                        <label for="exampleInputpaper_doi" class="col-sm-2 ">ผู้รับผิดชอบโครงการ (ร่วม) ภายนอก</label>
                        <div class="col-sm-9">
                            <div class="table-responsive">
                                <table class="table table-hover small-text" id="tb">
                                    <tr class="tr-header">
                                        <th>ตำแหน่งหรือคำนำหน้า</th>
                                        <th>ชื่อ</th>
                                        <th>นามสกุล</th>
                                        <!-- <th>Email Id</th> -->
                                            <!-- <button type="button" name="add" id="add" class="btn btn-success btn-sm"><i class="mdi mdi-plus"></i></button> -->
                                        <th><a href="javascript:void(0);" style="font-size:18px;" id="addMore2" title="Add More Person"><i class="mdi mdi-plus"></i></span></a></th>
                                    <tr>
                                        <td><input type="text" name="title_name[]" class="form-control" placeholder="ตำแหน่งหรือคำนำหน้า"></td>
                                        <td><input type="text" name="fname[]" class="form-control" placeholder="ชื่อ" ></td>
                                        <td><input type="text" name="lname[]" class="form-control" placeholder="นามสกุล" ></td>
                                        <!-- <td><input type="text" name="emailid[]" class="form-control"></td> -->
                                        <td><a href='javascript:void(0);' class='remove'><span><i class="mdi mdi-minus"></span></a></td>
                                    </tr>
                                </table>
                                <!-- <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" /> -->
                            </div>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                        <a class="btn btn-light" href="{{ route('researchProjects.index')}}">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @stop
    @section('javascript')
    <script>
        $(document).ready(function() {
            $('#status').on('change', function() {
                $('#hidden_status').val($(this).val());
            });

            // ฟังก์ชันสำหรับรับวันที่ปัจจุบันในรูปแบบ YYYY-MM-DD
            function getTodayFormatted() {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0');
                var yyyy = today.getFullYear();

                return yyyy + '-' + mm + '-' + dd;
            }

            var today = getTodayFormatted();

            // 1. กำหนดค่า min เป็นวันที่ปัจจุบัน
            $('#Project_start').attr('min', today);

            // 2. ตรวจสอบวันที่เริ่มต้น
            $('#Project_start').on('change input', function() {
                // ถ้าวันที่ที่เลือกน้อยกว่าวันปัจจุบัน
                if($(this).val() < today) {
                    alert('ไม่สามารถเลือกวันที่ย้อนหลังได้ ระบบได้กำหนดเป็นวันที่ปัจจุบันให้อัตโนมัติ');
                    $(this).val(today); // กำหนดเป็นวันที่ปัจจุบัน
                }

                // 3. อัพเดทค่า min ของวันสิ้นสุดให้เท่ากับวันที่เริ่มต้น
                $('#Project_end').attr('min', $(this).val());

                // 4. ถ้าวันสิ้นสุดถูกกำหนดไว้แล้วและน้อยกว่าวันเริ่มต้นใหม่ ให้ปรับวันสิ้นสุด
                if($('#Project_end').val() && $('#Project_end').val() < $(this).val()) {
                    $('#Project_end').val($(this).val());
                }

                updateProjectStatus();
            });

            // 5. ตรวจสอบวันสิ้นสุด
            $('#Project_end').on('change input', function() {
                var startDate = $('#Project_start').val();

                // ถ้าไม่มีวันเริ่มต้น แต่มีการกำหนดวันสิ้นสุด
                if(!startDate && $(this).val()) {
                    alert('กรุณากำหนดวันที่เริ่มต้นก่อน');
                    $(this).val(''); // ล้างค่าวันสิ้นสุด
                    $('#Project_start').focus(); // โฟกัสไปที่ช่องวันเริ่มต้น
                    return;
                }

                // ถ้าวันสิ้นสุดน้อยกว่าวันเริ่มต้น
                if($(this).val() < startDate && $(this).val()) {
                    alert('วันที่สิ้นสุดต้องไม่น้อยกว่าวันที่เริ่มต้น');
                    $(this).val(startDate); // กำหนดให้เท่ากับวันเริ่มต้น
                }

                // ตรวจสอบและอัพเดทสถานะโครงการ
                updateProjectStatus();
            });

            // 6. ตรวจสอบค่าเริ่มต้นเมื่อโหลดหน้า
            if($('#Project_start').val() && $('#Project_start').val() < today) {
                // ในหน้าแก้ไข ควรแจ้งเตือนผู้ใช้ว่ามีการปรับวันที่
                alert('วันที่เริ่มต้นเป็นวันที่ย้อนหลัง ระบบได้ปรับเป็นวันที่ปัจจุบัน');
                $('#Project_start').val(today);
            }

            // ถ้าวันที่เริ่มต้นมีค่า ให้กำหนดค่า min ของวันสิ้นสุด
            if($('#Project_start').val()) {
                $('#Project_end').attr('min', $('#Project_start').val());
            }

            if($('#Project_end').val() && $('#Project_start').val() && $('#Project_end').val() < $('#Project_start').val()) {
                // ในหน้าแก้ไข ควรแจ้งเตือนผู้ใช้ว่ามีการปรับวันที่
                alert('วันที่สิ้นสุดน้อยกว่าวันที่เริ่มต้น ระบบได้ปรับให้เท่ากับวันที่เริ่มต้น');
                $('#Project_end').val($('#Project_start').val());
            }

            $('#project_year').on('blur', function() {
                validateProjectYear();
            });

            function validateProjectYear() {
                var yearInput = $('#project_year');
                var yearError = $('#project_year_error');
                var year = parseInt(yearInput.val());
                var currentYear = new Date().getFullYear();
                var minYear = 1900;
                var maxYear = currentYear + 50;

                // ตรวจสอบว่ามีการกรอกข้อมูลหรือไม่
                if (!yearInput.val()) {
                    yearInput.addClass('is-invalid');
                    yearError.text('กรุณาระบุปีที่ยื่น');
                    return false;
                }

                // ตรวจสอบว่าเป็นตัวเลขเต็มหรือไม่
                if (!Number.isInteger(year)) {
                    yearInput.addClass('is-invalid');
                    yearError.text('ปีที่ยื่นต้องเป็นตัวเลขเต็มเท่านั้น');
                    return false;
                }

                // ตรวจสอบช่วงของปี
                if (year < minYear) {
                    yearInput.addClass('is-invalid');
                    yearError.text('ปีที่ยื่นต้องไม่น้อยกว่า ' + minYear);
                    return false;
                }
                else if (year > maxYear) {
                    yearInput.addClass('is-invalid');
                    yearError.text('ปีที่ยื่นต้องไม่มากกว่า ' + maxYear + ' (ปัจจุบัน + 5 ปี)');
                    return false;
                }

                // ถ้าผ่านการตรวจสอบทั้งหมด
                yearInput.removeClass('is-invalid')
                yearError.text('');
                return true;
            }

            function isSameDate(date1, date2) {
                var d1 = new Date(date1);
                var d2 = new Date(date2);
                return d1.getFullYear() === d2.getFullYear() &&
                       d1.getMonth() === d2.getMonth() &&
                       d1.getDate() === d2.getDate();
            }

            function updateProjectStatus() {
                var startDate = $('#Project_start').val();
                var endDate = $('#Project_end').val();
                var today = getTodayFormatted();

                if (startDate && endDate) {
                    // ถ้าวันเริ่มต้นตรงกับวันสิ้นสุด
                    if (startDate === endDate) {
                        // เปลี่ยนสถานะเป็น "ปิดโครงการ" (value="3")
                        $('#status').val('3');
                        $('#status').attr('disabled', true);
                        $('#hidden_status').val('3'); // อัพเดทค่าใน hidden field
                    }
                    // ถ้าวันสิ้นสุดตรงกับวันปัจจุบัน หรือน้อยกว่าวันปัจจุบัน (ผ่านมาแล้ว)
                    else if (isSameDate(endDate, today) || endDate < today) {
                        // เปลี่ยนสถานะเป็น "ปิดโครงการ" (value="3")
                        $('#status').val('3');
                        $('#status').attr('disabled', true);
                        $('#hidden_status').val('3'); // อัพเดทค่าใน hidden field
                    } else {
                        // เปลี่ยนสถานะเป็น "ดำเนินการ" (value="2")
                        $('#status').val('2');
                        $('#status').attr('disabled', false);
                        $('#hidden_status').val('2'); // อัพเดทค่าใน hidden field
                    }
                }
            }

            $('#funds_category').on('change', function () {
                let fundCateId = $(this).val();

                if (fundCateId) {
                    $.ajax({
                        url: '/get-funds-by-category',
                        type: 'GET',
                        data: {
                            fund_cate: fundCateId
                        },
                        success: function (data) {
                            $('#funds').empty();
                            $('#funds').append('<option value="">-- โปรดเลือกทุน --</option>');
                            $.each(data, function (key, value) {
                                $('#funds').append(`<option value="${value.id}">${value.fund_name}</option>`);
                            });
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            alert('เกิดข้อผิดพลาดในการโหลดข้อมูลทุน');
                        }
                    });
                } else {
                    $('#funds').empty();
                    $('#funds').append('<option value="">-- โปรดเลือกทุน --</option>');
                }
            });

            $('#funds_type').on('change', function () {
                let fundTypeId = $(this).val();

                if (fundTypeId) {
                    $.ajax({
                        url: `/getFundsCategory/${fundTypeId}`,
                        type: 'GET',
                        success: function (data) {
                            $('#funds_category').empty();
                            $('#funds_category').append('<option value="">-- โปรดระบุลักษณะทุน --</option>');
                            $.each(data, function (key, value) {
                                $('#funds_category').append(`<option value="${value.id}">${value.name}</option>`);
                            });
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            alert('เกิดข้อผิดพลาดในการโหลดข้อมูลลักษณะทุน');
                        }
                    });
                } else {
                    $('#funds_category').empty();
                    $('#funds_category').append('<option value="">-- โปรดระบุลักษณะทุน --</option>');
                }
            });

            updateProjectStatus();

        });
    </script>
    <script>
        const fundTypeSelect = document.getElementById('funds_type');
        const categorySelect = document.getElementById('funds_category');
        const tooltipText = document.querySelector('.tooltip-text');
        const oldFundCate = @json(old('fund_cate'));

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
        if (fundTypeSelect.value) {
            loadCategories(fundTypeSelect.value);
        }

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
         // ปิด select ของ category ไว้ก่อน
         categorySelect.disabled = true;

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
    </script>
    <script>
        $(document).ready(function() {
            $("#selUser0").select2()
            $("#head0").select2()
            //$("#fund").select2()
            //$("#dep").select2()
            var i = 0;

            $("#add-btn2").click(function() {

                ++i;
                $("#dynamicAddRemove").append('<tr><td><select id="selUser' + i +
                    '" name="moreFields[' + i +
                    '][userid]"  style="width: 200px;"><option value="">Select User</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->fname_th }} {{ $user->lname_th }}</option>@endforeach</select></td><td><button type="button" class="btn btn-danger btn-sm remove-tr"><i class="mdi mdi-minus"></i></button></td></tr>'
                );
                $("#selUser" + i).select2()
            });
            $(document).on('click', '.remove-tr', function() {
                $(this).parents('tr').remove();
            });

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var postURL = "<?php echo url("addmore"); ?>";
            var i = 0;


            $('#add').click(function() {
                i++;
                $('#dynamic_field').append('<tr id="row' + i +
                    '" class="dynamic-added"><td><p>ตำแหน่งหรือคำนำหน้า :</p><input type="text" name="title_name[]" placeholder="ตำแหน่งหรือคำนำหน้า" style="width: 200px;" class="form-control name_list" /><br><p>ชื่อ :</p><input type="text" name="fname[]" placeholder="ชื่อ" style="width: 300px;" class="form-control name_list" /><br><p>นามสกุล :</p><input type="text" name="lname[]" placeholder="นามสกุล" style="width: 300px;" class="form-control name_list" /></td><td><button type="button" name="remove" id="' +
                    i + '" class="btn btn-danger btn-sm btn_remove"><i class="mdi mdi-minus"></i></button></td></tr>');
            });


            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('#submit').click(function() {
                $.ajax({
                    url: postURL,
                    method: "POST",
                    data: $('#add_name').serialize(),
                    type: 'json',
                    success: function(data) {
                        if (data.error) {
                            printErrorMsg(data.error);
                        } else {
                            i = 1;
                            $('.dynamic-added').remove();
                            $('#add_name')[0].reset();
                            $(".print-success-msg").find("ul").html('');
                            $(".print-success-msg").css('display', 'block');
                            $(".print-error-msg").css('display', 'none');
                            $(".print-success-msg").find("ul").append(
                                '<li>Record Inserted Successfully.</li>');
                        }
                    }
                });
            });


            function printErrorMsg(msg) {
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display', 'block');
                $(".print-success-msg").css('display', 'none');
                $.each(msg, function(key, value) {
                    $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
                });
            }
        });
    </script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#addMore2').on('click', function() {
                var data = $("#tb tr:eq(1)").clone(true).appendTo("#tb");
                data.find("input").val('');
            });
            $(document).on('click', '.remove', function() {
                var trIndex = $(this).closest("tr").index();
                if (trIndex > 1) {
                    $(this).closest("tr").remove();
                } else {
                    alert("Sorry!! Can't remove first row!");
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const checkbox = document.getElementById("show_budget_checkbox");
            const hiddenInput = document.getElementById("show_budget");
            const message = document.getElementById("budget_message");

            checkbox.addEventListener("change", function () {
                if (this.checked) {
                    message.innerHTML = "งบประมาณจะ<strong>แสดง</strong>ในหน้า โครงการบริการวิชาการ/ โครงการวิจัย หากต้องการซ่อน โปรดยกเลิกการทำเครื่องหมายในช่อง แสดงงบประมาณ";
                    hiddenInput.value = 1;
                } else {
                    message.innerHTML = "งบประมาณจะ<strong>ไม่แสดง</strong>ในหน้า โครงการบริการวิชาการ/ โครงการวิจัย หากต้องการให้แสดง โปรดทำเครื่องหมายในช่อง แสดงงบประมาณ";
                    hiddenInput.value = 0;
                }
            });
        });
    </script>
    @stop
