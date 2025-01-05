<?php
namespace App\Http\Controllers;
use App\Models\Transactions;
use App\Models\Properties;
use Illuminate\Http\Request;
use App\Models\Reports;

class ReportsController extends Controller
{
    public function showGenerateReport(Reports $reports)
    {
        $this->authorize('create', $reports);
        $props = Properties::all();
        return view('reports.generate', compact('props'));
    }

    public function showPropertyReport(Properties $property)
    {
        $this->authorize('view', $property);

        $reports = Reports::where('propertyId, $property->id')
            ->with('property') //eager load property relationship if needed
            ->get();
        return view('reports.viewOne', compact('reports'));
    }


    public function generatePropertyReport(Request $request)
    {
        // $this->authorize('create', $reports);
        try {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $propertyId = $request->input('propertyId');
            $type = $request->input('type');

            // base query for transactions with error handling
            $baseQuery = Transactions::where('propertyId', $propertyId)
                ->whereBetween('paymentDate', [$startDate, $endDate]);

            $credits = (clone $baseQuery)
                ->where('type', 'credit')
                ->where('subtype', 'rent')
                ->sum('amount');

            $expenses = (clone $baseQuery)
                ->where('type', 'debit')
                ->sum('amount');

            // Fetch the property and current user
            $property = Properties::find($propertyId);

            $user = auth()->user();

            // Calculate the professional fee
            $feePercentage = $property->percentage;

            $professionalFee = $credits * ($feePercentage / 100);

            // Calculate net income
            $netIncome = $credits - $expenses - $professionalFee;

            $transactions = $baseQuery->get();
            $data = [
                'credits' => $credits,
                'expenses' => $expenses,
                'professionalFee' => $professionalFee,
                'netIncome' => $netIncome,
                'transactions' => $transactions->toArray(),
            ];

            $report = Reports::create([
                'propertyId' => $propertyId,
                'generated_by' => $user->id,
                'type' => $type,
                'report_data' => $data,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);

            return view('reports.property', compact(
                'property',
                'credits',
                'expenses',
                'professionalFee',
                'netIncome',
                'transactions',
                'startDate',
                'endDate'
            ));
        } catch (\Exception $e) {
            \Log::error('Report generation failed: ' . $e->getMessage());

            return back()->with('error', $e->getMessage());
        }
    }

    public function generateFirmReport(Request $request)
    {

        try {

            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            // base query for transactions with error handling
            $credits = Transactions::where('subType', 'legalFee')
                ->whereBetween('paymentDate', [$startDate, $endDate])
                ->sum('amount');

            $expenses = Transactions::where('type', 'debit')
                ->where('propertyId', null)
                ->sum('amount');

            $totalEarning = $credits - $expenses;

            // Fetch all transactions for detailed view
            $transactions = Transactions::where('subType', 'legalFee')
                ->whereBetween('paymentDate', [$startDate, $endDate])
                ->get();

            return view('reports.firm', compact(
                'credits',
                'expenses',
                'totalEarning',
                'transactions',
                'startDate',
                'endDate',
            ));
        } catch (\Exception $e) {
            \Log::error('Report generation failed: ' . $e->getMessage());
            // return redirect()->back()->with('error', $e->getMessage());
        }

    }
}
