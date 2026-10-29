<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ExecutionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| General Authenticated Dashboard Route
|--------------------------------------------------------------------------
|
| This acts as a fallback. If anything sends the user to /dashboard,
| the system checks the stored role and redirects to the correct dashboard.
|
*/

Route::get('/dashboard', function () {

    $user = auth()->user();

    return match ($user->role) {
        'ptj' => redirect()->route('ptj.dashboard'),
        'kj' => redirect()->route('kj.dashboard'),
        'bendahari' => redirect()->route('bendahari.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        default => abort(403, 'Invalid user role.'),
    };

})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| PTJ Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:ptj'])->group(function () {

    Route::get(
        '/ptj/dashboard',
        [ProcurementController::class, 'dashboard']
    )->name('ptj.dashboard');

});


/*
|--------------------------------------------------------------------------
| Ketua Jabatan Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:kj'])->group(function () {

    Route::get(
        '/kj/dashboard',
        [ApprovalController::class, 'kjDashboard']
    )->name('kj.dashboard');

});


/*
|--------------------------------------------------------------------------
| Bendahari Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:bendahari'])->group(function () {

    Route::get(
        '/bendahari/dashboard',
        [ApprovalController::class, 'bendahariDashboard']
    )->name('bendahari.dashboard');

});

/*
|--------------------------------------------------------------------------
| System Administrator Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get(
        '/admin/dashboard',
        [UserController::class, 'dashboard']
    )->name('admin.dashboard');

});

/*
|--------------------------------------------------------------------------
| Module 1: Manage Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // View profile
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');


    // Edit profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');


    // Save profile changes
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

});

/*
|--------------------------------------------------------------------------
| Module 2: Manage Procurement Request
|--------------------------------------------------------------------------
|
| PTJ Administrative Staff only.
|
*/

Route::middleware(['auth', 'role:ptj'])
    ->prefix('ptj/procurement')
    ->name('ptj.procurement.')
    ->group(function () {

        // B1: View Procurement Request List
        Route::get(
            '/',
            [ProcurementController::class, 'index']
        )->name('index');


        // B2: New Procurement Request Form
        Route::get(
            '/create',
            [ProcurementController::class, 'create']
        )->name('create');


        // B2: Submit New Procurement Request
        Route::post(
            '/',
            [ProcurementController::class, 'store']
        )->name('store');


        // B3: View Request Details
        Route::get(
            '/{procurementRequest}',
            [ProcurementController::class, 'show']
        )->name('show');


        // B4: Edit Returned Request
        Route::get(
            '/{procurementRequest}/edit',
            [ProcurementController::class, 'edit']
        )->name('edit');


        // B4: Update and Resubmit
        Route::put(
            '/{procurementRequest}',
            [ProcurementController::class, 'update']
        )->name('update');

    });

    /*
|--------------------------------------------------------------------------
| Module 4: Manage Approval
|--------------------------------------------------------------------------
|
| Phase A: Ketua Jabatan Review & Decision
|
*/

Route::middleware(['auth', 'role:kj'])
    ->prefix('kj/approvals')
    ->name('kj.approvals.')
    ->group(function () {

        // B1: Pending Approval List
        Route::get(
            '/',
            [ApprovalController::class, 'index']
        )->name('index');


        // B3: Approval History
        Route::get(
            '/history',
            [ApprovalController::class, 'history']
        )->name('history');


        // B3: Approval History Details
        Route::get(
            '/history/{approval}',
            [ApprovalController::class, 'historyDetails']
        )->name('history.details');


        // B1: Review Request
        Route::get(
            '/{procurementRequest}',
            [ApprovalController::class, 'show']
        )->name('show');


        // B2: Approve
        Route::post(
            '/{procurementRequest}/approve',
            [ApprovalController::class, 'approve']
        )->name('approve');


        // A1: Reject
        Route::post(
            '/{procurementRequest}/reject',
            [ApprovalController::class, 'reject']
        )->name('reject');


        // A2: Return for Edit
        Route::post(
            '/{procurementRequest}/return-edit',
            [ApprovalController::class, 'returnForEdit']
        )->name('return-edit');

    });

    // Phase b: Benmdahari Review & Decision
    Route::middleware(['auth', 'role:bendahari'])
    ->prefix('bendahari/approvals')
    ->name('bendahari.approvals.')
    ->group(function () {

        Route::get(
            '/',
            [ApprovalController::class, 'bendahariIndex']
        )->name('index');

        Route::get(
            '/history',
            [ApprovalController::class, 'bendahariHistory']
        )->name('history');

        Route::get(
            '/history/{approval}',
            [ApprovalController::class, 'bendahariHistoryDetails']
        )->name('history.details');

        Route::get(
            '/{procurementRequest}',
            [ApprovalController::class, 'bendahariShow']
        )->name('show');

        Route::post(
            '/{procurementRequest}/approve',
            [ApprovalController::class, 'bendahariApprove']
        )->name('approve');

        Route::post(
            '/{procurementRequest}/reject',
            [ApprovalController::class, 'bendahariReject']
        )->name('reject');
    });

    /*
|--------------------------------------------------------------------------
| Module 5: Manage Order Execution
|--------------------------------------------------------------------------
|
| Phase A: PTJ Active Orders and Local Order Generation
|
*/

Route::middleware(['auth', 'role:ptj'])
    ->prefix('ptj/orders')
    ->name('ptj.orders.')
    ->group(function () {

        // B1: View Active Order List
        Route::get(
            '/',
            [ExecutionController::class, 'activeOrders']
        )->name('index');


        // B1: Open Draft Local Order
        Route::get(
            '/{procurementRequest}/draft-lo',
            [ExecutionController::class, 'draftLO']
        )->name('draft-lo');


        // B1: Generate Official Local Order
        Route::post(
            '/{procurementRequest}/generate-lo',
            [ExecutionController::class, 'generateLO']
        )->name('generate-lo');


        // View Generated Local Order Document
        Route::get(
            '/local-order/{localOrder}',
            [ExecutionController::class, 'showLocalOrder']
        )->name('local-order');

               // View Delivery Order PDF (PTJ own order)
        Route::get(
            '/delivery-order/{deliveryOrder}',
            [ExecutionController::class, 'viewMyDeliveryOrder']
        )->name('delivery-order');


        // View Vendor Invoice PDF (PTJ own order)
        Route::get(
            '/invoice/{deliveryOrder}',
            [ExecutionController::class, 'viewMyInvoice']
        )->name('invoice');

        
        /*
        |--------------------------------------------------------------------------
        | Module 5B - Confirm Delivery
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/local-order/{localOrder}/confirm-delivery',
            [ExecutionController::class, 'confirmDelivery']
        )->name('confirm-delivery');


        Route::post(
            '/local-order/{localOrder}/confirm-delivery',
            [ExecutionController::class, 'storeDelivery']
        )->name('store-delivery');

          });



        /*
        |--------------------------------------------------------------------------
        | Bendahari - Payment Processing
        |--------------------------------------------------------------------------
        |
        | Manage Order Execution:
        | View pending payments, review delivery documents and simulate
        | completion of the external payment process.
        |
        */

        Route::middleware(['auth', 'role:bendahari'])
            ->prefix('bendahari/payments')
            ->name('bendahari.payments.')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Pending Payments
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    [ExecutionController::class, 'pendingPayments']
                )->name('index');


                /*
                |--------------------------------------------------------------------------
                | Payment History
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | Keep /history BEFORE /{deliveryOrder}.
                | Otherwise Laravel may interpret "history" as a deliveryOrder ID.
                |
                */

                Route::get(
                    '/history',
                    [ExecutionController::class, 'paymentHistory']
                )->name('history');


                /*
                |--------------------------------------------------------------------------
                | Payment Details / Action
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/{deliveryOrder}',
                    [ExecutionController::class, 'paymentDetails']
                )->name('show');


                Route::post(
                    '/{deliveryOrder}/mark-paid',
                    [ExecutionController::class, 'markAsPaid']
                )->name('mark-paid');


                /*
                |--------------------------------------------------------------------------
                | Verification Documents
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/{deliveryOrder}/local-order',
                    [ExecutionController::class, 'viewPaymentLocalOrder']
                )->name('local-order');


                Route::get(
                    '/{deliveryOrder}/delivery-order',
                    [ExecutionController::class, 'viewDeliveryOrderDocument']
                )->name('delivery-order');


                Route::get(
                    '/{deliveryOrder}/invoice',
                    [ExecutionController::class, 'viewInvoiceDocument']
                )->name('invoice');
            });



        /*
        |--------------------------------------------------------------------------
        | Module 6: Manage User
        |--------------------------------------------------------------------------
        |
        | System Administrator only.
        |
        | Functions:
        | - View User Directory
        | - Register New User
        | - Update User Role / PTJ Department
        | - Deactivate / Reactivate User
        |
        */

        Route::middleware(['auth', 'role:admin'])
            ->prefix('admin/users')
            ->name('admin.users.')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | B1: View User Directory
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    [UserController::class, 'index']
                )->name('index');


                /*
                |--------------------------------------------------------------------------
                | B2: Add New User
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/create',
                    [UserController::class, 'create']
                )->name('create');


                Route::post(
                    '/',
                    [UserController::class, 'store']
                )->name('store');


                /*
                |--------------------------------------------------------------------------
                | B3: Update User Role / Department
                |--------------------------------------------------------------------------
                |
                | This will be submitted from the User Directory edit modal.
                |
                */

                Route::put(
                    '/{user}',
                    [UserController::class, 'update']
                )->name('update');

                /*
                |--------------------------------------------------------------------------
                | Live User Registration Validation
                |--------------------------------------------------------------------------
                |
                | Used by the Add User form to check whether Staff ID or Email
                | already exists before the form is submitted.
                |
                */

                Route::get(
                    '/check-availability',
                    [UserController::class, 'checkAvailability']
                )->name('check-availability');


                /*
                |--------------------------------------------------------------------------
                | B4: Deactivate / Reactivate User
                |--------------------------------------------------------------------------
                */

                Route::patch(
                    '/{user}/status',
                    [UserController::class, 'updateStatus']
                )->name('status');
            });


            /*
            |--------------------------------------------------------------------------
            | Module 7: Generate Report
            |--------------------------------------------------------------------------
            |
            | All authenticated UCPMS roles may access the reporting module.
            |
            | ReportController performs the second level of authorization by deciding
            | which report types are available to PTJ, KJ, Bendahari and Admin.
            |
            */

            Route::middleware('auth')
                ->prefix('report')
                ->name('report.')
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | UC-700: Generate Report Interface
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/',
                        [ReportController::class, 'index']
                    )->name('index');

                });




/*
|--------------------------------------------------------------------------
| Breeze Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';