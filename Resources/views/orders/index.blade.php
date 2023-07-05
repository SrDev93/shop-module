@extends('layouts.admin')

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">


        <!-- PAGE-HEADER -->
        @include('shop::orders.partial.header')
        <!-- PAGE-HEADER END -->

        <!-- Row -->
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">لیست سفارشات</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom w-100" id="responsive-datatable">
                                <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">کاربر</th>
                                    <th class="wd-15p border-bottom-0">شماره سفارش</th>
                                    <th class="wd-15p border-bottom-0">مبلغ سفارش</th>
                                    <th class="wd-15p border-bottom-0">وضعیت سفارش</th>
                                    <th class="wd-20p border-bottom-0">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ optional($item->user)->name }}</td>
                                        <td>{{ $item->id }}</td>
                                        <td><span class="numberPrice">{{ $item->price }}</span> تومان </td>
                                        <td>
                                            @if($item->status == 1)
                                                ثبت شده
                                            @elseif($item->status == 2)
                                                پردازش شده
                                            @elseif($item->status == 3)
                                                ارسال شده
                                            @elseif($item->status == 4)
                                                تحویل داده شده
                                            @endif
                                        </td>
                                        <td>
                                            @if(!Auth::user()->hasrole('seller'))
                                                @if($item->status != 4)
                                                    <a href="{{ route('orders.status', $item->id) }}" class="btn btn-warning fs-14 text-white edit-icn" title="تغییر وضعیت">
                                                        <i class="fa fa-level-up"></i>
                                                    </a>
                                                @endif
                                            @endif
                                            <a href="{{ route('orders.show', $item->id) }}" class="btn btn-primary fs-14 text-white edit-icn" title="مشاهده">
                                                <i class="fe fe-eye"></i>
                                            </a>
{{--                                            <a href="{{ route('product.edit', $item->id) }}" class="btn btn-primary fs-14 text-white edit-icn" title="ویرایش">--}}
{{--                                                <i class="fe fe-edit"></i>--}}
{{--                                            </a>--}}

{{--                                            <button type="submit" onclick="return confirm('برای حذف اطمبنان دارید؟')" form="form-{{ $item->id }}" class="btn btn-danger fs-14 text-white edit-icn" title="حذف">--}}
{{--                                                <i class="fe fe-trash"></i>--}}
{{--                                            </button>--}}
{{--                                            <form id="form-{{ $item->id }}" action="{{ route('product.destroy', $item->id) }}" method="post">--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}
{{--                                            </form>--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('product.create') }}" class="btn btn-primary">افزودن محصول</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection
