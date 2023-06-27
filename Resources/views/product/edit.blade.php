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
    @include('shop::product.partial.header')
    <!-- PAGE-HEADER END -->

        <!-- ROW -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">ویرایش محصول</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.update', $product->id) }}" method="post" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            <div class="col-md-6">
                                <label for="category_id" class="form-label">دسته بندی</label>
                                <select name="category_id" class="form-control">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @if($product->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">لطفا دسته بندی را انتخاب کنید</div>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">نام محصول</label>
                                <input type="text" name="name" class="form-control" id="name" required value="{{ $product->name }}">
                                <div class="invalid-feedback">لطفا نام محصول را وارد کنید</div>
                            </div>
                            <div class="col-md-11">
                                <label for="img" class="form-label">تصویر شاخص</label>
                                <input type="file" name="img" class="form-control" aria-label="تصویر شاخص" accept="image/*" @if(!$product->img) required @endif>
                                <div class="invalid-feedback">لطفا یک تصویر انتخاب کنید</div>
                            </div>
                            <div class="col-md-1">
                                @if($product->img)
                                    <label for="img" class="form-label">تصویر شاخص</label>
                                    <img src="{{ url($product->img) }}" style="max-width: 50%;">
                                @endif
                            </div>

                            <div class="col-md-12">
                                <label for="short_text" class="form-label">توضیح کوتاه</label>
                                <input type="text" name="short_text" class="form-control" id="short_text" value="{{ $product->short_text }}">
                                <div class="invalid-feedback">لطفا توضیح کوتاه را وارد کنید</div>
                            </div>
                            <div class="col-md-12">
                                <label for="description" class="form-label">توضیحات کامل</label>
                                <textarea id="editor1" name="description" class="cke_rtl" required>{{ $product->description }}</textarea>
                                <div class="invalid-feedback">لطفا توضیحات کامل را وارد کنید</div>
                            </div>

                            <div class="properties row col-md-12">
                                <div class="col-md-12">
                                    <label for="description" class="form-label">ویژگی های محصول</label>
                                    <button type="button" class="btn btn-success add-property"><i class="fa fa-plus-circle"></i> افزودن ویژگی </button>
                                </div>

                                @foreach($product->properties as $property)
                                    <input type="hidden" name="property_id[]" value="{{ $property->id }}">
                                    <div class="col-md-6">
                                        <label for="property_title" class="form-label">عنوان ویژگی</label>
                                        <input type="text" name="property_title[]" class="form-control" value="{{ $property->title }}">
                                        <div class="invalid-feedback">لطفا عنوان ویژگی را وارد کنید</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="property_value" class="form-label">مقدار ویژگی</label>
                                        <input type="text" name="property_value[]" class="form-control" value="{{ $property->value }}">
                                        <div class="invalid-feedback">لطفا مقدار ویژگی را وارد کنید</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="photos row col-md-12">
                                <div class="col-md-12">
                                    <label for="photos" class="form-label">تصاویر گالری</label>
                                    <button type="button" class="btn btn-success add-photo"><i class="fa fa-plus-circle"></i> افزودن تصویر </button>
                                </div>

                                @foreach($product->photo as $photo)
                                    <input type="hidden" name="photo_id[]" value="{{ $photo->id }}">
                                    <div class="col-md-5">
                                        <label for="photo" class="form-label">تصویر گالری</label>
                                        <input type="file" name="photo[]" class="form-control" accept="image/*">
                                        <div class="invalid-feedback">لطفا یک تصویر را وارد کنید</div>
                                    </div>
                                    <div class="col-md-1">
                                        @if($photo->path)
                                            <label for="photo" class="form-label">تصویر گالری</label>
                                            <img src="{{ url($photo->path) }}" style="max-width: 50%;">
                                        @endif
                                    </div>
                                @endforeach
                            </div>


                            <div class="col-12 mt-4">
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
        @include('ckfinder::setup')
        <script>
            var editor = CKEDITOR.replace('editor1', {
                // Define the toolbar groups as it is a more accessible solution.
                toolbarGroups: [
                    {
                        "name": "basicstyles",
                        "groups": ["basicstyles"]
                    },
                    {
                        "name": "links",
                        "groups": ["links"]
                    },
                    {
                        "name": "paragraph",
                        "groups": ["list", "blocks"]
                    },
                    {
                        "name": "document",
                        "groups": ["mode"]
                    },
                    {
                        "name": "insert",
                        "groups": ["insert"]
                    },
                    {
                        "name": "styles",
                        "groups": ["styles"]
                    },
                    {
                        "name": "about",
                        "groups": ["about"]
                    },
                    {   "name": 'paragraph',
                        "groups": ['list', 'blocks', 'align', 'bidi']
                    }
                ],
                // Remove the redundant buttons from toolbar groups defined above.
                removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,PasteFromWord'
            });
            CKFinder.setupCKEditor( editor );
        </script>

        <script>
            $('.add-property').click(function (){
                $('.properties').append(`<div class="col-md-6">
                                    <label for="property_title" class="form-label">عنوان ویژگی</label>
                                    <input type="text" name="property_title[]" class="form-control">
                                    <div class="invalid-feedback">لطفا عنوان ویژگی را وارد کنید</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="property_value" class="form-label">مقدار ویژگی</label>
                                    <input type="text" name="property_value[]" class="form-control">
                                    <div class="invalid-feedback">لطفا مقدار ویژگی را وارد کنید</div>
                                </div>`);
            });

            $('.add-photo').click(function (){
                $('.photos').append(`<div class="col-md-6">
                                    <label for="photo" class="form-label">تصویر گالری</label>
                                    <input type="file" name="photo[]" class="form-control" accept="image/*">
                                    <div class="invalid-feedback">لطفا یک تصویر را وارد کنید</div>
                                </div>`);
            })
        </script>
    @endpush
@endsection
