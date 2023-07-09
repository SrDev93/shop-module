@extends('layouts.admin')

@push('stylesheets')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endpush

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
    @include('shop::seller.partial.header')
        <!-- PAGE-HEADER END -->

        <!-- ROW -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">افزودن فروشنده</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('seller.store') }}" method="post" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            <div class="col-md-6">
                                <label for="title" class="form-label">کاربر</label>
                                <select name="user_id" class="form-control">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if(old('user_id') == $user->id) selected @endif>{{ $user->Name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">لطفا کاربر را انتخاب کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">نام برند یا فروشگاه</label>
                                <input type="text" name="name" class="form-control" id="name" required value="{{ old('name') }}">
                                <div class="invalid-feedback">لطفا نام برند یا فروشگاه را وارد کنید</div>
                            </div>
                            <div class="col-md-12">
                                <label for="logo" class="form-label">لوگو</label>
                                <input type="file" name="logo" class="form-control" aria-label="لوگو" accept="image/*" required>
                                <div class="invalid-feedback">لطفا یک تصویر انتخاب کنید</div>
                            </div>
{{--                            <div class="col-md-6">--}}
{{--                                <label for="banner" class="form-label">بنر</label>--}}
{{--                                <input type="file" name="banner" class="form-control" aria-label="بنر" accept="image/*" required>--}}
{{--                                <div class="invalid-feedback">لطفا یک تصویر انتخاب کنید</div>--}}
{{--                            </div>--}}


                            <div class="col-md-6">
                                <label for="facebook" class="form-label">فیسبوک</label>
                                <input type="text" name="facebook" class="form-control" id="facebook" value="{{ old('facebook') }}">
                                <div class="invalid-feedback">لطفا لینک صفحه فیسبوک خود را وارد کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="twitter" class="form-label">توییتر</label>
                                <input type="text" name="twitter" class="form-control" id="twitter" value="{{ old('twitter') }}">
                                <div class="invalid-feedback">لطفا لینک صفحه توییتر خود را وارد کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="linkedin" class="form-label">لینکدین</label>
                                <input type="text" name="linkedin" class="form-control" id="linkedin" value="{{ old('linkedin') }}">
                                <div class="invalid-feedback">لطفا لینک صفحه لینکدین خود را وارد کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="instagram" class="form-label">اینستاگرام</label>
                                <input type="text" name="instagram" class="form-control" id="instagram" value="{{ old('instagram') }}">
                                <div class="invalid-feedback">لطفا لینک صفحه اینستاگرام خود را وارد کنید</div>
                            </div>

                            <div class="col-md-12">
                                <label for="docs" class="form-label">آپلود مدارک</label>
                                <input type="file" name="docs[]" class="form-control" id="docs" multiple>
                                <div class="invalid-feedback">لطفا مدارک خود را انتخاب کنید</div>
                            </div>


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
{{--        @include('ckfinder::setup')--}}
        <script>
            // var editor = CKEDITOR.replace('editor1', {
            //     // Define the toolbar groups as it is a more accessible solution.
            //     toolbarGroups: [
            //         {
            //             "name": "basicstyles",
            //             "groups": ["basicstyles"]
            //         },
            //         {
            //             "name": "links",
            //             "groups": ["links"]
            //         },
            //         {
            //             "name": "paragraph",
            //             "groups": ["list", "blocks"]
            //         },
            //         {
            //             "name": "document",
            //             "groups": ["mode"]
            //         },
            //         {
            //             "name": "insert",
            //             "groups": ["insert"]
            //         },
            //         {
            //             "name": "styles",
            //             "groups": ["styles"]
            //         },
            //         {
            //             "name": "about",
            //             "groups": ["about"]
            //         },
            //         {   "name": 'paragraph',
            //             "groups": ['list', 'blocks', 'align', 'bidi']
            //         }
            //     ],
            //     // Remove the redundant buttons from toolbar groups defined above.
            //     removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,PasteFromWord'
            // });
            // CKFinder.setupCKEditor( editor );
        </script>
    @endpush
@endsection
