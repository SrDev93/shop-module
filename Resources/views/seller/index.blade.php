@extends('layouts.admin')

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">


        <!-- PAGE-HEADER -->
        @include('shop::seller.partial.header')
        <!-- PAGE-HEADER END -->

        <!-- Row -->
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">لیست فروشندگان</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom w-100" id="responsive-datatable">
                                <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">نام کاربر</th>
                                    <th class="wd-15p border-bottom-0">نام فروشگاه</th>
                                    <th class="wd-15p border-bottom-0">محصولات</th>
                                    <th class="wd-20p border-bottom-0">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>@if($item->user) {{ $item->user->Name }} @endif</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ count($item->products) }}</td>
                                        <td>
                                            <a href="{{ route('sellerProduct.index', $item->id) }}" class="btn btn-info fs-14 text-white edit-icn" title="مشاهده محصولات">
                                                <i class="fe fe-eye"></i>
                                            </a>
                                            @if($item->status)
                                                <a href="{{ route('seller.status', $item->id) }}" class="btn btn-warning fs-14 text-white edit-icn" title="رد تایید">
                                                    <i class="fa fa-close"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('seller.status', $item->id) }}" class="btn btn-success fs-14 text-white edit-icn" title="تایید">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('seller.edit', $item->id) }}" class="btn btn-primary fs-14 text-white edit-icn" title="ویرایش">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <button type="submit" onclick="return confirm('برای حذف اطمبنان دارید؟')" form="form-{{ $item->id }}" class="btn btn-danger fs-14 text-white edit-icn" title="حذف">
                                                <i class="fe fe-trash"></i>
                                            </button>
                                            <form id="form-{{ $item->id }}" action="{{ route('seller.destroy', $item->id) }}" method="post">
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
                        <a href="{{ route('seller.create') }}" class="btn btn-primary">افزودن فروشنده</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection
