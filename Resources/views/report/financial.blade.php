@extends('layouts.admin')

@push('stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.0/css/buttons.dataTables.min.css">
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
    @include('shop::report.partial.header')
    <!-- PAGE-HEADER END -->

        <!-- Row -->
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">گزارش مالی</h3>
                    </div>
                    <div class="card-body">

                        <form action="" method="get" class="mb-4">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="from_date" class="form-label">از تاریخ</label>
                                        <input type="text" name="from_date" class="form-control" id="from_date" value="{{ request()->get('from_date') }}" oninvalid="this.setCustomValidity('لطفا تاریخ شروع را انتخاب کنید')" oninput="this.setCustomValidity('')">
                                        <div class="invalid-feedback">لطفا از تاریخ را وارد کنید</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="to_date" class="form-label">تا تاریخ</label>
                                        <input type="text" name="to_date" class="form-control" id="to_date" value="{{ request()->get('to_date') }}" oninvalid="this.setCustomValidity('لطفا تاریخ پایان را انتخاب کنید')" oninput="this.setCustomValidity('')">
                                        <div class="invalid-feedback">لطفا تا تاریخ را وارد کنید</div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button class="btn btn-primary" type="submit">گزارش گیری</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom w-100" id="excel">
                                <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">ردیف</th>
                                    <th class="wd-15p border-bottom-0">نام محصول</th>
                                    @if(!auth()->user()->hasrole('seller'))
                                        <th class="wd-15p border-bottom-0">نام فروشنده</th>
                                    @endif
                                    <th class="wd-15p border-bottom-0">نام خریدار</th>
                                    <th class="wd-15p border-bottom-0">مبلغ</th>
                                    <th class="wd-20p border-bottom-0">زمان</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ optional($item->product)->name }}</td>
                                        @if(!auth()->user()->hasrole('seller'))
                                            <td>{{ $item->seller_product->seller->name }}</td>
                                        @endif
                                        <td>{{ $item->factor->user->name }}</td>
                                        <td><span class="numberPrice">{{ $item->price }}</span> تومان</td>
                                        <td>{{ verta($item->created_at)->format('Y/m/d') }}</td>
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

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.0/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.0/js/buttons.html5.min.js"></script>

        <script>
            $(document).ready(function () {
                $('#excel').DataTable({
                    language: {url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/fa.json"},
                    ordering: false,
                    responsive: !0,
                    dom: 'Bfrtip',
                    buttons: [
                        // 'copyHtml5',
                        'excelHtml5',
                        // 'csvHtml5',
                        // 'pdfHtml5'
                    ]
                });
            });
        </script>

        <script src="https://unpkg.com/persian-date@latest/dist/persian-date.min.js"></script>
        <script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#from_date').pDatepicker({
                    initialValue: false,
                    format: 'YYYY-MM-DD',
                    autoClose:true,
                    calendar: {
                        persian:{
                            locale:'fa'
                        }
                    }
                });

                $('#to_date').pDatepicker({
                    initialValue: false,
                    format: 'YYYY-MM-DD',
                    autoClose:true,
                    calendar: {
                        persian:{
                            locale:'fa'
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
