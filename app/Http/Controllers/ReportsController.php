<?php
namespace App\Http\Controllers;
use App\Models\Transactions;
use App\Models\Properties;
use App\Models\User;
use App\Notifications\reportReady;
use DB;
use Illuminate\Http\Request;
use App\Models\Reports;
use Notification;

class ReportsController extends Controller
{
    public function showGenerateReport(Reports $reports)
    {
        $this->authorize('create', $reports);
        $props = Properties::all();
        return view('reports.generate', compact('props'));
    }

    public function showViewForm()
    {
        $user = auth()->user();
        $props = Properties::where('clientId', $user->id)->get();
        return view('reports.viewForm', compact('props'));
    }

    public function viewPropReport(Request $request)
    {
        $validated = $request->validate([
            'propertyId' => 'required|integer',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate'
        ]);

        $user = auth()->user();

        $report = Reports::where('propertyId', $validated['propertyId'])
            ->where('startDate', $validated['startDate'])
            ->where('endDate', $validated['endDate'])
            ->firstOrFail();

        return view('reports.clientView', compact('report'));
    }

    public function generatePropertyReport(Request $request)
    {
        // $this->authorize('create', $reports);
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'propertyId' => 'required|integer',
                'startDate' => 'required|date',
                'endDate' => 'required|date|after_or_equal:startDate',
                'type' => 'required'
            ]);

            // Fetch the property and current user
            $property = Properties::findOrFail($validated['propertyId']);

            $user = auth()->user();
            $client = User::findOrFail($property->clientId);

            $reportData = $this->calculatePropData(
                $property,
                $validated['startDate'],
                $validated['endDate']
            );

            $report = Reports::create([
                'propertyId' => $property->id,
                'generated_by' => $user->id,
                'type' => $validated['type'],
                'report_data' => $reportData,
                'startDate' => $validated['startDate'],
                'endDate' => $validated['endDate'],
            ]);

            Notification::send($client, new reportReady($report));

            DB::commit();

            return view('reports.property', array_merge(
                $reportData,
                [
                    'property' => $property,
                    'startDate' => $validated['startDate'],
                    'endDate' => $validated['endDate']
                ]
            ));
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Report generation failed: ' . $e->getMessage());
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

    private function calculatePropData(Properties $property, string $startDate, string $endDate)
    {
        $baseQuery = Transactions::where('propertyId', $property->id)
            ->whereBetween('paymentDate', [$startDate, $endDate]);

        $credits = (clone $baseQuery)
            ->where('type', 'credit')
            ->where('subtype', 'rent')
            ->sum('amount');

        $expenses = (clone $baseQuery)
            ->where('type', 'debit')
            ->sum('amount');

        // Calculate the professional fee
        $feePercentage = $property->percentage;

        $professionalFee = $credits * ($feePercentage / 100);

        // Calculate net income
        $netIncome = $credits - $expenses - $professionalFee;

        // eager loading tenant rshp when getting transactions
        $transactions = $baseQuery->get();

        return [
            'credits' => $credits,
            'expenses' => $expenses,
            'professionalFee' => $professionalFee,
            'netIncome' => $netIncome,
            'transactions' => $transactions->toArray(),
        ];
    }
}
