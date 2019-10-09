<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-heading h3">{{ $users->name }} {{__('Referred Users')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%" id="example">
            <thead>
                <tr>
                    <th>{{ __('Date Referred') }}</th>
                    <th>{{__('Name')}}</th>
                    <th>{{ __('Email') }}</th>
                    <th width="120px">{{ __('User Type') }}</th>
                    <th>{{__('Email Date Verifed')}}</th>
                    <th width="120px">{{__('ID Status')}}</th>
                    <th>{{__('Balance')}}</th>
            <thead>  
            <tbody>
                @php
                    $refer =  DB::table('users')->where('referred_by','LIKE',"%{$users->id}%")->get();
                @endphp
                @foreach($refer as $referred)
                <tr>
                    <td>{{ date('m-d-Y h:i A', strtotime($referred->created_at)) }}</td>
                    <td>{{ $referred->name }}</td>
                    <td>{{ $referred->email}}</td>
                    <td>
                        <div class="label label-table label-default" style="margin-left: 10px;" >
                                 {{ $referred->user_type }}
                        </div>
                    </td>
                    @if($referred->email_verified_at != NULL)
                        <td>
                            <div class="label label-table label-info" style="margin-left: 10px;" >
                                {{date('m-d-Y', strtotime($referred->email_verified_at))}}
                            </div>
                        </td>
                    @else
                        <td>
                            <div class="label label-table label-warning" style="margin-left: 10px;" >
                                {{__('Pending')}}
                            </div>
                        </td>
                    @endif
                    @if($referred->ref_id_status != NULL)
                       <td>
                            <div class="label label-table label-success" style="margin-left: 10px;" >
                                {{__('Verified')}}
                            </div>
                        </td>
                    @else
                        <td>
                            <div class="label label-table label-danger" style="margin-left: 10px;" >
                                {{__('Unverified')}}
                            </div>
                        </td>
                    @endif
                    <td>{{ single_price($referred->balance)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>