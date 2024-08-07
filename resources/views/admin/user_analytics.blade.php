@extends('layouts.layout')
@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-4 mt-5">
                <div class="card-body">
                    {{ $dataTable->table() }}
                    {{-- <table class="table datatable pt-4 pb-4" id="useranalytics-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Email/Mobile</th>
                                <th>Payment Date</th>
                                <th>Package</th>
                                <th>Amount</th>
                                <th>Transaction ID</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table> --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script type="text/javascript">
    $(document).ready(function() {
        filter();
    })
    function filter(){
        // $('#useranalytics-table').DataTable({
        //     processing: true,
        //     serverSide: false,
        //     ordering:false,
        //     ajax: {
        //         url: '{{route("user.analytics.datatable")}}',
        //         type: 'GET',
        //     },
        //     columns: [
        //         {data:'sr_no'},
        //         {data: 'name',},
        //         {data: 'email',},
        //         {data: 'payment.created_at',},
        //         {data: 'payment.package',},
        //         {data: 'payment.amount',},
        //         {data: 'payment.transaction_id',},
        //     ],
        //     "columnDefs": [
        //         { "className": "text-center", "targets": "_all" }
        //     ],
        // });
    }
</script>
@endpush