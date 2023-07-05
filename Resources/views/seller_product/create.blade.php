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
    @include('shop::seller_product.partial.header')
        <!-- PAGE-HEADER END -->

        <!-- ROW -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        @if(!$product)
                            <h3 class="card-title">افزودن محصول و قیمت</h3>
                        @else
                            <h3 class="card-title">افزودن قیمت برای محصول {{ $product->name }}</h3>
                        @endif
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sellerProduct.store', $seller->id) }}" method="post" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            @if(!$product)
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label">دسته بندی</label>
                                    <select name="category_id" class="form-control category" required>
                                        <option value>انتخاب دسته بندی</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">لطفا دسته بندی را انتخاب کنید</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="name" class="form-label">نام محصول</label>
                                    <input type="text" name="name" class="form-control" id="name" required value="{{ old('name') }}">
                                    <div class="invalid-feedback">لطفا نام محصول را وارد کنید</div>
                                </div>
                                <div class="col-md-12">
                                    <label for="img" class="form-label">تصویر شاخص</label>
                                    <input type="file" name="img" class="form-control" aria-label="تصویر شاخص" accept="image/*" required>
                                    <div class="invalid-feedback">لطفا یک تصویر انتخاب کنید</div>
                                </div>

                                <div class="col-md-12">
                                    <label for="short_text" class="form-label">توضیح کوتاه</label>
                                    <input type="text" name="short_text" class="form-control" id="short_text" value="{{ old('short_text') }}">
                                    <div class="invalid-feedback">لطفا توضیح کوتاه را وارد کنید</div>
                                </div>
                                <div class="col-md-12">
                                    <label for="description" class="form-label">توضیحات کامل</label>
                                    <textarea id="editor1" name="description" class="cke_rtl" required>{{ old('description') }}</textarea>
                                    <div class="invalid-feedback">لطفا توضیحات کامل را وارد کنید</div>
                                </div>

                                <div class="row-divider"></div>
                                <div class="col-md-12">
                                    <label for="description" class="form-label">ویژگی های محصول</label>
                                    {{--                                    <button type="button" class="btn btn-success add-property"><i class="fa fa-plus-circle"></i> افزودن ویژگی </button>--}}
                                </div>
                                <div class="properties row col-md-12">

                                    {{--                                <div class="col-md-6">--}}
                                    {{--                                    <label for="property_title" class="form-label">عنوان ویژگی</label>--}}
                                    {{--                                    <input type="text" name="property_id[]" class="form-control">--}}
                                    {{--                                    <input type="text" name="property_value[]" class="form-control">--}}
                                    {{--                                </div>--}}
                                </div>
                                <div class="row-divider"></div>

                                <div class="photos row col-md-12">
                                    <div class="col-md-12">
                                        <label for="photos" class="form-label">تصاویر گالری</label>
                                        <button type="button" class="btn btn-success add-photo"><i class="fa fa-plus-circle"></i> افزودن تصویر </button>
                                    </div>

    {{--                                <div class="col-md-6">--}}
    {{--                                    <label for="photo" class="form-label">تصویر گالری</label>--}}
    {{--                                    <input type="file" name="photo[]" class="form-control" accept="image/*">--}}
    {{--                                    <div class="invalid-feedback">لطفا یک تصویر را وارد کنید</div>--}}
    {{--                                </div>--}}
                                </div>

                                <div class="row-divider"></div>
                                <div class="col-md-6">
                                    <label for="price" class="form-label">قیمت محصول ( تومان )</label>
                                    <input type="number" name="price" class="form-control" id="price" required value="{{ old('price') }}">
                                    <div class="invalid-feedback">لطفا قیمت محصول به تومان را وارد کنید</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="off" class="form-label">درصد تخفیف محصول</label>
                                    <input type="number" name="off" class="form-control" id="off" value="{{ old('off') }}">
                                    <div class="invalid-feedback">لطفا درصد تخفیف محصول را وارد کنید</div>
                                </div>


                                <div class="col-md-6">
                                    <label for="stock" class="form-label">موجودی محصول</label>
                                    <input type="number" name="stock" class="form-control" id="stock" required value="{{ old('stock')?old('stock'):'0' }}">
                                    <div class="invalid-feedback">لطفا موجودی محصول را وارد کنید</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="warranty" class="form-label">نوع گارانتی</label>
                                    <input type="text" name="warranty" class="form-control" id="warranty" value="{{ old('warranty') }}" placeholder="گارانتی اصالت و سلامت کالا">
                                    <div class="invalid-feedback">لطفا نوع گارانتی را وارد کنید</div>
                                </div>
                            @else

                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="col-md-6">
                                    <label for="price" class="form-label">قیمت محصول ( تومان )</label>
                                    <input type="number" name="price" class="form-control" id="price" required value="{{ old('price') }}">
                                    <div class="invalid-feedback">لطفا قیمت محصول به تومان را وارد کنید</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="off" class="form-label">درصد تخفیف محصول</label>
                                    <input type="number" name="off" class="form-control" id="off" value="{{ old('off') }}">
                                    <div class="invalid-feedback">لطفا درصد تخفیف محصول را وارد کنید</div>
                                </div>


                                <div class="col-md-6">
                                    <label for="stock" class="form-label">موجودی محصول</label>
                                    <input type="number" name="stock" class="form-control" id="stock" required value="{{ old('stock')?old('stock'):'0' }}">
                                    <div class="invalid-feedback">لطفا موجودی محصول را وارد کنید</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="warranty" class="form-label">نوع گارانتی</label>
                                    <input type="text" name="warranty" class="form-control" id="warranty" value="{{ old('warranty') }}" placeholder="گارانتی اصالت و سلامت کالا">
                                    <div class="invalid-feedback">لطفا نوع گارانتی را وارد کنید</div>
                                </div>

                            @endif


                            <div class="col-12 mt-4">
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

            $('.category').change(function (){
                let id = $(this).val();
                $('.properties').empty();

                $.ajax({
                    url: '{{ route('product.property', '') }}/'+id,
                    type:'GET',
                    dataType:'json',
                    success: function (res){
                        if(res.success) {
                            $.each(res.data, function (index, item){
                                $('.properties').append(`<div class="col-md-6">
                                    <label for="property_title" class="form-label">${item.name}</label>
                                    <input type="hidden" name="property_id[]" value="${item.id}">
                                    <input type="text" name="property_value[]" class="form-control">
                                </div>`)
                            })
                        }
                    }
                })
            })
        </script>
    @endpush
@endsection
