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
                        <h3 class="card-title">اطلاعات و آدرس خریدار</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom w-100">
                                <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">نام و نام خانوادگی</th>
                                    <th class="wd-15p border-bottom-0">استان / شهر</th>
                                    <th class="wd-15p border-bottom-0">آدرس</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ optional($item->address)->first_name }} {{ optional($item->address)->last_name }}</td>
                                        <td>{{ optional(optional($item->address)->province)->name }} / {{ optional(optional($item->address)->city)->name }}</td>
                                        <td>{{ optional($item->address)->address }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>

                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">لیست خرید</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom w-100" id="responsive-datatable">
                                <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">محصول</th>
                                    <th class="wd-15p border-bottom-0">قیمت واحد</th>
                                    <th class="wd-15p border-bottom-0">تعداد</th>
                                    <th class="wd-15p border-bottom-0">قیمت کل</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($item->baskets as $basket)
                                    <tr>
                                        <td>
                                            @if($basket->product)
                                                @if($basket->product->img)
                                                    <img src="{{ url($basket->product->img) }}?width=50">
                                                @endif
                                                {{ optional($basket->product)->name }}
                                                <h6>
                                                    <small>فروشنده : {{ $basket->seller_product->seller->name }}</small>
                                                </h6>
                                            @endif
                                        </td>
                                        <td><span class="numberPrice">{{ $basket->price / $basket->quantity }}</span> تومان </td>
                                        <td>{{ $basket->quantity }}</td>
                                        <td><span class="numberPrice">{{ $basket->price }}</span> تومان </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection
