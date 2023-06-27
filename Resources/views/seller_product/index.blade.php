@extends('layouts.admin')
@push('stylesheets')
    <style>
        .select2-container {
            width: 100%!important;
        }
    </style>
@endpush
@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">


        <!-- PAGE-HEADER -->
        @include('shop::seller_product.partial.header')
        <!-- PAGE-HEADER END -->

        <!-- Row -->
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">لیست محصولات</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom w-100" id="responsive-datatable">
                                <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">دسته بندی</th>
                                    <th class="wd-15p border-bottom-0">نام محصول</th>
                                    <th class="wd-15p border-bottom-0">قیمت</th>
                                    <th class="wd-20p border-bottom-0">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ optional(optional($item->product)->category)->name }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td><span class="numberPrice">{{ $item->price }}</span> تومان </td>
                                        <td>
                                            <a href="{{ route('sellerProduct.edit', [$seller->id, $item->id]) }}" class="btn btn-primary fs-14 text-white edit-icn" title="ویرایش">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <button type="submit" onclick="return confirm('برای حذف اطمبنان دارید؟')" form="form-{{ $item->id }}" class="btn btn-danger fs-14 text-white edit-icn" title="حذف">
                                                <i class="fe fe-trash"></i>
                                            </button>
                                            <form id="form-{{ $item->id }}" action="{{ route('sellerProduct.destroy', [$seller->id, $item->id]) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">افزودن محصول</a>
{{--                        <a href="{{ route('product.create') }}" class="btn btn-primary" data-toggle="modal" data-target="#myModal">افزودن محصول</a>--}}
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('sellerProduct.create', $seller->id) }}" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">انتخاب محصول</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <select name="product_id" class="form-control select2">
                            <option value>انتخاب محصول</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-primary">افزودن محصول</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
