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
                        <h3 class="card-title">ویرایش دسته بندی</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('category.update', $category->id) }}" method="post" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            <div class="col-md-6">
                                <label for="name" class="form-label">نام دسته بندی</label>
                                <input type="text" name="name" class="form-control" id="name" required value="{{ $category->name }}">
                                <div class="invalid-feedback">لطفا نام دسته بندی را وارد کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="slug" class="form-label">نامک</label>
                                <input type="text" name="slug" class="form-control" id="slug" required value="{{ $category->slug }}">
                                <div class="invalid-feedback">لطفا نامک را وارد کنید</div>
                            </div>
                            <div class="col-md-5">
                                <label for="photo" class="form-label">تصویر شاخص</label>
                                <input type="file" name="photo" class="form-control" id="photo" @if(!$category->photo) required @endif accept="image/*">
                                <div class="invalid-feedback">لطفا تصویر شاخص را انتخاب کنید</div>
                            </div>
                            <div class="col-md-1">
                                @if($category->photo)
                                    <label for="photo" class="form-label">تصویر شاخص فعلی</label>
                                    <img src="{{ url($category->photo) }}" style="max-width: 100%">
                                @endif
                            </div>

                            <div class="col-md-5">
                                <label for="banner" class="form-label">بنر</label>
                                <input type="file" name="banner" class="form-control" id="banner" @if(!$category->banner) required @endif accept="image/*">
                                <div class="invalid-feedback">لطفا بنر را انتخاب کنید</div>
                            </div>
                            <div class="col-md-1">
                                @if($category->banner)
                                    <label for="banner" class="form-label">بنر فعلی</label>
                                    <img src="{{ url($category->banner) }}" style="max-width: 100%">
                                @endif
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">ارسال فرم</button>
                                @csrf
                                @method('PATCH')
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ROW CLOSED -->


    </div>
@endsection
