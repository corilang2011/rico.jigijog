@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Add Expense')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('expense.store') }}" method="POST">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="amount">{{__('Amount')}}</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{__('Amount')}}" id="amount" name="amount" class="form-control" required>
                    </div>
                </div>
				<div class="form-group">
				  <label class="col-sm-2 control-label" for="description">{{__('Description')}}</label>
                    <div class="col-sm-10">
                        <textarea class="form-control rounded-0" name="description" id="exampleFormControlTextarea2" rows="3" ></textarea>
                    </div>
				</div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection
