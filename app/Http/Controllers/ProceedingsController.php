<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\cases;
use App\Models\proceedings;

use Illuminate\Http\Request;

class ProceedingsController extends Controller
{
    public function showAdd()
    {
        $cases = cases::all();
        return view('cases.proceedings', compact('clients'));
    }

    public function showEdit($id)
    {
        $pro = proceedings::find($id);
        $cases = cases::all();
        return view('cases.editPro', [
            'pro' => $pro,
            'cases' => $cases
        ]);
    }

    public function updatePro(Request $request, $id)
    {
        // find the case to be updated
        $pro = proceedings::findOrFail($id);

        if ($request->isMethod('put')) {
            $validatedData = $request->validate([
                'caseId' => 'required',
                'description' => 'required|string',
            ]);

            // update therequired field
            $pro->update([
                'caseId' => $validatedData['caseId'],
                'description' => $validatedData['description'],
            ]);

            // Redirect to the view cases page
            return redirect()->route('cases.proceedings')
                ->with('message', 'Record updated successfully');
        }
    }

    public function addPro(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'caseId' => 'required',
                'description' => 'required|string',
            ]);

            $pro = cases::create([
                'caseId' => $validatedData['caseId'],
                'description' => $validatedData['description'],
            ]);

            return redirect()->back()->with('message', 'Record added successfully');
        }
    }
}
