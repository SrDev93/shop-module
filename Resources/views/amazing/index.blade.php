@extends('layouts.admin')
@push('stylesheets')
    <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.min.css">
    <style>
        .select2-container {
            width: 100% !important;
        }
        .month-grid-box .title {
            display: none;
        }
        .dark-mode .month-grid-box .header {
            background: #fefefe !important;
        }
    </style>
@endpush
@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">


        <!-- PAGE-HEADER -->
        @include('shop::amazing.partial.header')
        <!-- PAGE-HEADER END -->

        <!-- Row -->
        <div class="row row-sm">
            <div class="col-lg-12">
                <form action="{{ route('amazing.update') }}" method="post">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h3 class="card-title">لیست محصولات</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom w-100" id="responsive-datatable">
                                    <thead>
                                    <tr>
                                        <th class="wd-15p border-bottom-0">نام محصول</th>
                                        <th class="wd-15p border-bottom-0">درصد تخفیف</th>
                                        <th class="wd-15p border-bottom-0">زمان پیشنهاد</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $item)
                                        @if($item->product)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td>
                                                    <input type="hidden" name="products[]" value="{{ $item->id }}">
                                                    <input type="number" name="off[]" value="{{ $item->off }}" class="form-control">
                                                </td>
                                                <td><input type="text" name="amazing_date[]" value="{{ $item->amazing_date }}" class="form-control amazing_date" dir="ltr" data-date="{{ $item->amazing_date }}"></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">بروزرسانی</button>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
        <!-- End Row -->
    </div>

    @push('scripts')
        <script src="https://unpkg.com/persian-date@latest/dist/persian-date.min.js"></script>
        <script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.amazing_date').each(function(i, obj) {
                    if(obj.value !== ''){
                        $(this).pDatepicker({
                            // initialValue: false,
                            format: 'YYYY-MM-DD HH:mm:ss',
                            timePicker: {
                                enabled:true
                            },
                            minDate: '{{ date('Y-m-d') }}',
                            autoClose:true,
                            calendar: {
                                persian:{
                                    locale:'fa'
                                }
                            }
                        });
                    }else {
                        $(this).pDatepicker({
                            initialValue: false,
                            format: 'YYYY-MM-DD HH:mm:ss',
                            timePicker: {
                                enabled:true
                            },
                            minDate: '{{ date('Y-m-d') }}',
                            autoClose:true,
                            calendar: {
                                persian:{
                                    locale:'fa'
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
