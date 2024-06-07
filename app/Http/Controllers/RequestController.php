<?php

namespace App\Http\Controllers;

use App\Models\CsrRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index(){
        $requests = CsrRequest::all();

        return view('admin.csr.all-requests', compact('requests'));
    }

    public function store(Request $request){
       $request->validate([
        'subject' => 'required',
        'message' => 'required',
       ]);

       CsrRequest::create([
        'user_id' => Auth::user()->id,
        'subject' => $request->subject,
        'msg' => $request->message,
       ]);

        return redirect()->back()->withSuccess('Request sent successfully!');
    }

    public function edit(Request $request){
        $requestId = $request->requestId;
        $request = CsrRequest::find($requestId);

        return response()->json(['request' => $request]);
    }

    public function update(Request $request, $id){
        $request->validate([
         'subject' => 'required',
         'message' => 'required',
        ]);

        $csrRequest = CsrRequest::find($id);
        $csrRequest->update([
            'subject' => $request->subject,
            'msg' => $request->message,
        ]);
 
         return redirect()->back()->withSuccess('Request update successfully!');
     }

     public function updateStatus($status_id, $requestId){
        $csrRequest = CsrRequest::find($requestId);
        
        $csrRequest->update([
            'status_id' => $status_id,
        ]);

        return redirect()->back()->withSuccess('Request status update successfully!');
     }

}
