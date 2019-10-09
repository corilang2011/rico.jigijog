@extends('layouts.app')

@section('content')

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('Support Desk')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Ticket ID') }}</th>
                    <th>{{ __('Sending Date') }}</th>
                    <th>{{ __('Subject') }}</th>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Last reply') }}</th>
                    <th>{{ __('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($tickets as $key => $ticket)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>#{{ $ticket->code }}</td>
                        <td>{{ date('m-d-Y h:i A', strtotime($ticket->created_at)) }} @if($ticket->viewed == 0) <span class="pull-right badge badge-info">{{ __('New Message') }}</span> @endif</td>
                        <td>{{ $ticket->subject }}</td>
                        <td>{{ $ticket->user->name }}</td>
                        <td>
                            @if ($ticket->status == 'pending')
                                <span class="badge badge-pill badge-danger">Pending</span>
                            @elseif ($ticket->status == 'open')
                                <span class="badge badge-pill badge-info">Open</span>
                            @else
                                <span class="badge badge-pill badge-success">Solved</span>
                            @endif
                        </td>
                        <td>
                            @if (count($ticket->ticketreplies) > 0)
                               {{ date('m-d-Y h:i A', strtotime($ticket->ticketreplies->last()->created_at)) }}
                            @else
                                {{ date('m-d-Y h:i A', strtotime($ticket->created_at)) }}
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{route('support_ticket.admin_show', encrypt($ticket->id))}}" class="btn-link"><b>{{__('View Details')}}</b></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection
