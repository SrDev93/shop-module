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
                        <h3 class="card-title">ویرایش فروشنده</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('seller.update', $seller->id) }}" method="post" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            <div class="col-md-6">
                                <label for="title" class="form-label">کاربر</label>
                                <select name="user_id" class="form-control">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if($seller->user_id == $user->id) selected @endif>{{ $user->Name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">لطفا کاربر را انتخاب کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">نام برند یا فروشگاه</label>
                                <input type="text" name="name" class="form-control" id="name" required value="{{ $seller->name }}">
                                <div class="invalid-feedback">لطفا نام برند یا فروشگاه را وارد کنید</div>
                            </div>
                            <div class="col-md-11">
                                <label for="logo" class="form-label">لوگو</label>
                                <input type="file" name="logo" class="form-control" aria-label="لوگو" accept="image/*" @if(!$seller->logo) required @endif>
                                <div class="invalid-feedback">لطفا یک تصویر انتخاب کنید</div>
                            </div>
                            <div class="col-md-1">
                                @if($seller->logo)
                                <label for="logo" class="form-label">لوگو فعلی</label>
                                <img src="{{ url($seller->logo) }}" style="max-width: 50%;">
                                @endif
                            </div>

{{--                            <div class="col-md-5">--}}
{{--                                <label for="banner" class="form-label">بنر</label>--}}
{{--                                <input type="file" name="banner" class="form-control" aria-label="بنر" accept="image/*" @if(!$seller->banner) required @endif>--}}
{{--                                <div class="invalid-feedback">لطفا یک تصویر انتخاب کنید</div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-1">--}}
{{--                                @if($seller->banner)--}}
{{--                                <label for="banner" class="form-label">بنر فعلی</label>--}}
{{--                                <img src="{{ url($seller->banner) }}" style="max-width: 100%;">--}}
{{--                                @endif--}}
{{--                            </div>--}}



                            <div class="col-md-6">
                                <label for="facebook" class="form-label">فیسبوک</label>
                                <input type="text" name="facebook" class="form-control" id="facebook" value="{{ $seller->facebook }}">
                                <div class="invalid-feedback">لطفا لینک صفحه فیسبوک خود را وارد کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="twitter" class="form-label">توییتر</label>
                                <input type="text" name="twitter" class="form-control" id="twitter" value="{{ $seller->twitter }}">
                                <div class="invalid-feedback">لطفا لینک صفحه توییتر خود را وارد کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="linkedin" class="form-label">لینکدین</label>
                                <input type="text" name="linkedin" class="form-control" id="linkedin" value="{{ $seller->linkedin }}">
                                <div class="invalid-feedback">لطفا لینک صفحه لینکدین خود را وارد کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="instagram" class="form-label">اینستاگرام</label>
                                <input type="text" name="instagram" class="form-control" id="instagram" value="{{ $seller->instagram }}">
                                <div class="invalid-feedback">لطفا لینک صفحه اینستاگرام خود را وارد کنید</div>
                            </div>

                            <div class="col-md-12">
                                <label for="docs" class="form-label">آپلود مدارک</label>
                                <input type="file" name="docs[]" class="form-control" id="docs" multiple>
                                <div class="invalid-feedback">لطفا مدارک خود را انتخاب کنید</div>
                            </div>

                            <div class="row-divider"></div>
                            <div class="col-md-12">
                                @if(count($seller->docs))
                                    <label for="docs" class="form-label">مدارک آپلود شده:</label>
                                    <ul style="list-style: auto; margin-right: 50px;">
                                        @foreach($seller->docs as $doc)
                                            @if($doc->path)
                                                <li class="mb-3">
                                                    <a href="{{ url($doc->path) }}" target="_blank">مشاهده</a>
                                                    <a href="{{ route('seller.doc.delete', $doc->id) }}" onclick="return confirm('برای حذف اطمینان دارید؟');" class="btn btn-danger mx-3"><i class="fa fa-trash"></i></a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
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
