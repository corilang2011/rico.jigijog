@extends('layouts.app')

@section('content')

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('Price Adjustments Log')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('From')}}</th>
                    <th>{{__('To')}}</th>
                    <th>{{__('Date Adjusted')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adjustment as $key => $value)
                    @php
                        $product = App\Product::findOrFail($value->product_id);
                    @endphp
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td><a style="color: #ff4e00;" href="{{ route('product', $product->slug) }}" target="_blank">{{ __($product->name) }}</a></td>
                        <td>{{ single_price($value->from) }}</td>
                        <td>{{ single_price($value->to) }}</td>
                        <td>{{ $value->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection


@section('script')
    <script type="text/javascript">

    </script>
@endsection
