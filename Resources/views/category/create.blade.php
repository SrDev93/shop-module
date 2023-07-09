@extends('layouts.admin')

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">

        @include('shop::category.partial.header')

        <!-- ROW -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">افزودن دسته بندی</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('category.store') }}" method="post" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            <div class="col-md-6">
                                <label for="name" class="form-label">نام دسته بندی</label>
                                <input type="text" name="name" class="form-control" id="name" required value="{{ old('name') }}">
                                <div class="invalid-feedback">لطفا نام دسته بندی را وارد کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="slug" class="form-label">نامک</label>
                                <input type="text" name="slug" class="form-control" id="slug" required value="{{ old('slug') }}">
                                <div class="invalid-feedback">لطفا نامک را وارد کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="photo" class="form-label">تصویر شاخص ( 120 * 250 )</label>
                                <input type="file" name="photo" class="form-control" id="photo" required accept="image/*">
                                <div class="invalid-feedback">لطفا تصویر شاخص را انتخاب کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="banner" class="form-label">بنر ( 254 * 1272 )</label>
                                <input type="file" name="banner" class="form-control" id="banner" required accept="image/*">
                                <div class="invalid-feedback">لطفا بنر را انتخاب کنید</div>
                            </div>
{{--                            <div class="col-md-4">--}}
{{--                                <label for="banner2" class="form-label">بنر ( 246 * 616 )</label>--}}
{{--                                <input type="file" name="banner2" class="form-control" id="banner2" accept="image/*">--}}
{{--                                <div class="invalid-feedback">لطفا بنر را انتخاب کنید</div>--}}
{{--                            </div>--}}

                            <div class="row-divider"></div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary add-property"><i class="fa fa-plus-circle"></i> افزودن ویژگی</button>
                                </div>
                                <div class="row properties">
                                    <div class="col-md-4">
                                        <label for="property" class="form-label">ویژگی</label>
                                        <input type="text" name="property[]" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="property" class="form-label">فیلتر</label>
                                        <select name="filter[]" class="form-control">
                                            <option value="0">فقط جهت نمایش</option>
                                            <option value="1">جهت نمایش و فیلتر</option>
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="row-divider"></div>
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">ارسال فرم</button>
                                @csrf
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ROW CLOSED -->
    </div>

    @push('scripts')
        <script>
            $('.add-property').click(function (){
                $('.properties').append(`<div class="col-md-4">
                                        <label for="property" class="form-label">ویژگی</label>
                                        <input type="text" name="property[]" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="property" class="form-label">فیلتر</label>
                                        <select name="filter[]" class="form-control">
                                            <option value="0">فقط جهت نمایش</option>
                                            <option value="1">جهت نمایش و فیلتر</option>
                                        </select>
                                    </div>`);
            })
        </script>
    @endpush
@endsection
