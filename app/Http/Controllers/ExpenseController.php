<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use Auth;
use Session;
use DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $expense = DB::table('expenses')->orderBy('created_at', 'desc')->get();

        return view('expenses.index', compact('expense'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$expense = new Expense;
    	$expense->amount = $request->amount;
    	$expense->description = htmlspecialchars($request->description);

        if($expense->save()){
            flash(__('Amount has been added successfully!'))->success();
            return redirect()->action('ExpenseController@index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //  
        $expense = Expense::findOrFail($id);
        if($expense != null){
            $expense->delete();

            flash('Expense has been deleted successfully')->success();
        }
        else{
            flash('Something went wrong')->error();
        }
        return back();
    }
}
