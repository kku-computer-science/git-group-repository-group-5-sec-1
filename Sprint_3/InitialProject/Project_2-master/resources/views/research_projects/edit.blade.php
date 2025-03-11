@extends('dashboards.users.layouts.user-dash-layout')
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
                <h4 class="card-title">แก้ไขข้อมูลโครงการวิจัย</h4>
                <p class="card-description">กรอกข้อมูลแก้ไขรายละเอียดโครงการวิจัย</p>
                <form action="{{ route('researchProjects.update',$researchProject->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group row mt-5">
                        <label for="exampleInputfund_name" class="col-sm-2 ">ชื่อโครงการวิจัย</label>
                        <div class="col-sm-8">
                            <input type="text" name="project_name" class="form-control" placeholder="ชื่อโครงการวิจัย" value="{{ $researchProject->project_name }}">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_name" class="col-sm-2 ">วันที่เริ่มต้น</label>
                        <div class="col-sm-4">
                            <input type="date" name="project_start" id="Project_start" class="form-control" value="{{ $researchProject->project_start }}">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_name" class="col-sm-2 ">วันที่สิ้นสุด</label>
                        <div class="col-sm-4">
                            <input type="date" name="project_end" id="Project_end" class="form-control" value="{{ $researchProject->project_end }}">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_details" class="col-sm-2 ">เลือกประเภททุน<span class="required-star">*</span></label>
                        <div class="col-sm-4">
                            <select name="funds_type_id" class="custom-select my-select" id="funds_type" required>
                                <option value="">---- โปรดระบุประเภททุน ----</option>
                                @foreach($fundType as $type)
                                    <option value="{{ $type->id }}" {{ $researchProject->fund->category->fundType->id == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('funds_type_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <label for="exampleInputfund_details" class="col-sm-2 ">เลือกลักษณะทุน<span class="required-star">*</span></label>
                        <div class="col-sm-4">
                            <select name="fund_cate" class="custom-select my-select" id="funds_category" required>
                                <option value="">---- โปรดระบุลักษณะทุน ----</option>
                            </select>
                            <p class="tooltip-text mt-1">กรุณาเลือกประเภททุนก่อน</p>
                            @error('fund_cate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputproject_year" class="col-sm-2 ">ปีที่ยื่น (ค.ศ.)</label>
                        <div class="col-sm-4">
                            <input type="year" name="project_year" class="form-control" placeholder="year" value="{{ $researchProject->project_year }}">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_name" class="col-sm-2 ">งบประมาณ</label>
                        <div class="col-sm-4">
                            <input type="number" name="budget" class="form-control" placeholder="หน่วยบาท" value="{{ $researchProject->budget }}">
                        </div>
                        <div class="col-sm-2 d-flex align-items-center">
                            <input type="hidden" name="show_budget" id="show_budget" value="{{ $researchProject->show_budget }}">
                            <input type="checkbox" id="show_budget_checkbox" {{ $researchProject->show_budget == 1 ? 'checked' : '' }}>
                            <label class="ms-2 mt-2">แสดงงบประมาณ</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputfund_name" class="col-sm-2 "></label>
                        <div class="col-sm-10">
                            <p id="budget_message" class="text-muted">
                                งบประมาณจะ<strong>{{ $researchProject->show_budget == 1 ? 'แสดง' : 'ไม่แสดง' }}</strong>ในหน้า โครงการบริการวิชาการ/ โครงการวิจัย หากต้องการ{{ $researchProject->show_budget == 1 ? 'ซ่อน' : 'ให้แสดง' }} โปรด{{ $researchProject->show_budget == 1 ? 'ยกเลิกการทำเครื่องหมายในช่อง' : 'ทำเครื่องหมายในช่อง' }} แสดงงบประมาณ
                            </p>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label for="exampleInputresponsible_department" class="col-sm-2 ">หน่วยงานที่รับผิดชอบ</label>
                        <div class="col-sm-9">
                            <select id='dep' style='width: 200px;' class="custom-select my-select" name="responsible_department">
                                @foreach($deps as $dep)
                                    @php
                                        $isSelected = false;
                                        if($researchProject->responsibleDepartmentResearchProject->isNotEmpty()) {
                                            foreach($researchProject->responsibleDepartmentResearchProject as $rdp) {
                                                if($rdp->responsible_department_id == $dep->id) {
                                                    $isSelected = true;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $dep->id }}" {{ $isSelected ? 'selected' : '' }}>{{ $dep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_details" class="col-sm-2 ">รายละเอียดโครงการ</label>
                        <div class="col-sm-9">
                            <textarea type="text" name="note" class="form-control form-control-lg" style="height:150px" placeholder="Note">{{ $researchProject->note }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="exampleInputstatus" class="col-sm-2 ">สถานะ</label>
                        <div class="col-sm-3">
                            <select id='status' class="custom-select my-select" name="status">
                                <option value="" disabled>โปรดระบุสถานะดำเนินงาน</option>
                                <option value="1" {{ $researchProject->status == 1 ? 'selected' : '' }}>ยื่นขอ</option>
                                <option value="2" {{ $researchProject->status == 2 ? 'selected' : '' }}>ดำเนินการ</option>
                                <option value="3" {{ $researchProject->status == 3 ? 'selected' : '' }}>ปิดโครงการ</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label for="exampleInputfund_details" class="col-sm-2 ">ผู้รับผิดชอบโครงการ</label>
                        <div class="col-sm-9">
                            <select id='head0' style='width: 200px;' name="head">
                                <option value=''>Select User</option>
                                @foreach($users as $user)
                                @php
                                    $isHead = false;
                                    foreach($researchProject->user as $u) {
                                        if($u->pivot->role == 1 && $u->id == $user->id) {
                                            $isHead = true;
                                            break;
                                        }
                                    }
                                @endphp
                                <option value="{{ $user->id }}" {{ $isHead ? 'selected' : '' }}>{{ $user->fname_th }} {{ $user->lname_th }}</option>
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
                            </table>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label for="exampleInputpaper_doi" class="col-sm-2 ">ผู้รับผิดชอบโครงการ (ร่วม) ภายนอก</label>
                        <div class="col-sm-9">
                            <div class="table-responsive">
                                <table class="table table-hover small-text" id="tb">
                                    <tr class="tr-header">
                                        <th>ตำแหน่งหรือคำนำหน้า</th>
                                        <th>ชื่อ</th>
                                        <th>นามสกุล</th>
                                        <th><a href="javascript:void(0);" style="font-size:18px;" id="addMore2" title="Add More Person"><i class="mdi mdi-plus"></i></span></a></th>
                                    </tr>
                                </table>
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
</div>
@stop

@section('javascript')
<script>
    $(document).ready(function() {
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
                }
                // ถ้าวันสิ้นสุดตรงกับวันปัจจุบัน หรือน้อยกว่าวันปัจจุบัน (ผ่านมาแล้ว)
                else if (isSameDate(endDate, today) || endDate < today) {
                    // เปลี่ยนสถานะเป็น "ปิดโครงการ" (value="3")
                    $('#status').val('3');
                    $('#status').attr('disabled', true);
                } else {
                    // เปลี่ยนสถานะเป็น "ดำเนินการ" (value="2")
                    $('#status').val('2');
                    $('#status').attr('disabled', false);
                }
            }
        }

        updateProjectStatus();
    });
</script>
<script>
    const fundTypeSelect = document.getElementById('funds_type');
    const categorySelect = document.getElementById('funds_category');
    const tooltipText = document.querySelector('.tooltip-text');
    const currentFundCate = "{{ $researchProject->fund->category->id }}";

    function loadCategories(fundTypeId) {
        fetch(`/getFundsCategory/${fundTypeId}`)
            .then(response => response.json())
            .then(data => {
                categorySelect.innerHTML = '<option value="">---- โปรดระบุลักษณะทุน ----</option>';
                data.forEach(category => {
                    categorySelect.innerHTML += `<option value="${category.id}" ${category.id == currentFundCate ? 'selected' : ''}>${category.name}</option>`;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการโหลดข้อมูลลักษณะทุน');
            });
    }

    // โหลดลักษณะทุนเมื่อโหลดหน้า
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

        // เพิ่มผู้รับผิดชอบโครงการร่วมภายในที่มีอยู่แล้ว
        @foreach($researchProject->user as $u)
            @if($u->pivot->role == 2)
                $("#dynamicAddRemove").append('<tr><td><select id="selUser' + i +
                    '" name="moreFields[' + i +
                    '][userid]"  style="width: 200px;"><option value="">Select User</option>@foreach($users as $user)<option value="{{ $user->id }}" {{ $u->id == $user->id ? "selected" : "" }}>{{ $user->fname_th }} {{ $user->lname_th }}</option>@endforeach</select></td><td><button type="button" class="btn btn-danger btn-sm remove-tr"><i class="mdi mdi-minus"></i></button></td></tr>'
                );
                $("#selUser" + i).select2();
                i++;
            @endif
        @endforeach

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
<script>
    $(document).ready(function() {
        // แสดงผู้รับผิดชอบโครงการร่วมภายนอกที่มีอยู่แล้ว
        @foreach($researchProject->outsider as $outsider)
            @if($outsider->pivot->role == 2)
                $('#tb').append('<tr><td><input type="text" name="title_name[]" class="form-control" value="{{ $outsider->title_name }}" placeholder="ตำแหน่งหรือคำนำหน้า"></td><td><input type="text" name="fname[]" class="form-control" value="{{ $outsider->fname }}" placeholder="ชื่อ" ></td><td><input type="text" name="lname[]" class="form-control" value="{{ $outsider->lname }}" placeholder="นามสกุล" ></td><td><a href="javascript:void(0);" class="remove"><span><i class="mdi mdi-minus"></span></a></td></tr>');
            @endif
        @endforeach

        $('#addMore2').on('click', function() {
            $('#tb').append('<tr><td><input type="text" name="title_name[]" class="form-control" placeholder="ตำแหน่งหรือคำนำหน้า"></td><td><input type="text" name="fname[]" class="form-control" placeholder="ชื่อ" ></td><td><input type="text" name="lname[]" class="form-control" placeholder="นามสกุล" ></td><td><a href="javascript:void(0);" class="remove"><span><i class="mdi mdi-minus"></span></a></td></tr>');
        });

        $(document).on('click', '.remove', function() {
            var trIndex = $(this).closest("tr").index();
            if (trIndex > 0) {
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
