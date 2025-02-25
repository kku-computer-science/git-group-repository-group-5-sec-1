@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
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
        <div class="card" style="padding: 16px;">
            <div class="card-body">
                <h4 class="card-title">สร้างกลุ่มวิจัย</h4>
                <p class="card-description">กรอกข้อมูลแก้ไขรายละเอียดกลุ่มวิจัย</p>
                <form action="{{ route('researchGroups.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="image-row-preview" class="form-group row" style="display: none;">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-8">
                            <img id="imagePreview" src="#" alt="Image Preview"
                            style="max-width: 100%; display:none; margin-top: 10px; height: 200px;">
                        </div>
                    </div>
                    <div class="form-group row" >
                        <p class="col-sm-3"><b>Image</b></p>
                        <div class="col-sm-8">
                            <input id="imageInput" type="file" name="group_image" accept="image/png, image/jpeg, image/jpg, image/webp"
                                class="form-control" value="{{ old('group_image') }}">
                        </div>
                    </div>
                    <div id="banner-row-preview" class="form-group row" style="display: none;">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-8">
                            <img id="bannerPreview" src="#" alt="Banner Preview"
                                style="width: 100%; display:none; margin-top: 10px; height: 200px;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-3"><b>Banner</b></p>
                        <div class="col-sm-8">
                            <input id="bannerImageInput" type="file" name="group_image"
                                accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control"
                                value="{{ old('banner_image') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-3 "><b>ชื่อกลุ่มวิจัย (ภาษาไทย)</b></p>
                        <div class="col-sm-8">
                            <input name="group_name_th" value="{{ old('group_name_th') }}" class="form-control"
                                placeholder="ชื่อกลุ่มวิจัย (ภาษาไทย)">
                        </div>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-3 "><b>ชื่อกลุ่มวิจัย (English)</b></p>
                        <div class="col-sm-8">
                            <input name="group_name_en" value="{{ old('group_name_en') }}" class="form-control"
                                placeholder="ชื่อกลุ่มวิจัย (English)">
                        </div>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-3"><b>Research rationale (ภาษาไทย)</b></p>
                        <div class="col-sm-8">
                            <textarea name="group_desc_th" value="{{ old('group_desc_th') }}" class="form-control" style="height:90px"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-3"><b>Research rationale (English)</b></p>
                        <div class="col-sm-8">
                            <textarea name="group_desc_en" value="{{ old('group_desc_en') }}" class="form-control" style="height:90px"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-3"><b>หัวข้อวิจัยที่เป็นจุดเน้นของกลุ่ม (ภาษาไทย)</b></p>
                        <div class="col-sm-8">
                            <textarea name="group_detail_en" value="{{ old('group_detail_th') }}" class="form-control" style="height:90px"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-3"><b>หัวข้อวิจัยที่เป็นจุดเน้นของกลุ่ม (English)</b></p>
                        <div class="col-sm-8">
                            <textarea name="group_detail_en" value="{{ old('group_detail_en') }}" class="form-control" style="height:90px"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <p class="col-sm-3"><b>หัวหน้ากลุ่มวิจัย</b></p>
                        <div class="col-sm-8" style="opacity: 0.75">
                            <select id='head0' name="head" readonly>
                                <option value="{{ $user->id }}" selected>
                                    {{ $user->fname_th }} {{ $user->lname_th }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-3 pt-4"><b>สมาชิกกลุ่มวิจัย</b></p>
                        <div class="col-sm-8">
                            <table class="table" id="dynamicAddRemove">
                                <tr>
                                    <th><button type="button" name="add" id="add-btn2"
                                            class="btn btn-success btn-sm add"><i class="mdi mdi-plus"></i></button></th>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary upload mt-5">Submit</button>
                    <a class="btn btn-light mt-5" href="{{ route('researchGroups.index') }}"> Back</a>
                </form>
            </div>
        </div>
    </div>

@stop
@section('javascript')
    <!-- <script type="text/javascript">
        $("body").on("click", ".upload", function(e) {
            $(this).parents("form").ajaxForm(options);
        });


        var options = {
            complete: function(response) {
                if ($.isEmptyObject(response.responseJSON.error)) {
                    // $("input[name='title']").val('');
                    // alert('Image Upload Successfully.');
                } else {
                    printErrorMsg(response.responseJSON.error);
                }
            }
        };


        function printErrorMsg(msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display', 'block');
            $.each(msg, function(key, value) {
                $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
            });
        }
    </script> -->
    <script>
        $(document).ready(function() {
            $("#selUser0").select2()
            $("#head0").select2()

            var i = 0;

            $("#add-btn2").click(function() {

                ++i;
                $("#dynamicAddRemove").append('<tr><td><select id="selUser' + i + '" name="moreFields[' +
                    i +
                    '][userid]"  style="width: 200px;"><option value="">Select User</option>@foreach ($users as $user)<option value="{{ $user->id }}">{{ $user->fname_th }} {{ $user->lname_th }}</option>@endforeach</select></td><td><button type="button" class="btn btn-danger btn-sm remove-tr"><i class="fas fa-minus"></i></button></td></tr>'
                );
                $("#selUser" + i).select2()
            });
            $(document).on('click', '.remove-tr', function() {
                $(this).parents('tr').remove();
            });

            // Show Banner when uploaded
            $("#bannerImageInput").change(function(e) {
                if (e.target.files && e.target.files.length > 0) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("#bannerPreview").attr("src", e.target.result).show();
                        $("#banner-row-preview").show();
                    }
                    reader.readAsDataURL(e.target.files[0]);
                } else {
                    $("#bannerPreview").hide();
                    $("#banner-row-preview").hide();
                }
            });

            // Show Image when uploaded
            $("#imageInput").change(function(e) {
                if (e.target.files && e.target.files.length > 0) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("#imagePreview").attr("src", e.target.result).show();
                        $("#image-row-preview").show();
                    }
                    reader.readAsDataURL(e.target.files[0]);
                } else {
                    $("#imagePreview").hide();
                    $("#image-row-preview").hide();
                }
            });

        });
    </script>
@stop
