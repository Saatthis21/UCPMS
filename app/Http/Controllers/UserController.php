<?php

namespace App\Http\Controllers;

use App\Models\PtjDepartment;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    /**
     * Display the System Administrator dashboard using live UCPMS data.
     */
    public function dashboard(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Dynamic Greeting
        |--------------------------------------------------------------------------
        */

        $hour = now()->hour;

        $greeting = match (true) {
            $hour < 12 => 'Good morning',
            $hour < 18 => 'Good afternoon',
            default => 'Good evening',
        };


        /*
        |--------------------------------------------------------------------------
        | User Summary
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::count();

        $activeAccounts = User::where(
            'is_active',
            true
        )->count();

        $newUsersThisMonth = User::whereYear(
                'created_at',
                now()->year
            )
            ->whereMonth(
                'created_at',
                now()->month
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Documented UCPMS Module Count
        |--------------------------------------------------------------------------
        |
        | UCPMS Phase 1 defines seven modules/use cases:
        | Manage Profile, Procurement Request, Quotation Evaluation,
        | Approval, Order Execution, User, and Report.
        |
        */

        $systemModules = 7;


        /*
        |--------------------------------------------------------------------------
        | Recent Audit Log Preview
        |--------------------------------------------------------------------------
        |
        | Use a LEFT JOIN so an audit record can still be displayed even if
        | the related user record is unavailable.
        |
        */

        $recentAuditLogs = AuditLog::query()
            ->leftJoin(
                'users',
                'audit_logs.user_id',
                '=',
                'users.user_id'
            )
            ->select([
                'audit_logs.audit_log_id',
                'audit_logs.procurement_request_id',
                'audit_logs.user_id',
                'audit_logs.action',
                'audit_logs.description',
                'audit_logs.created_at',
                'users.staff_id as actor_staff_id',
                'users.user_name as actor_name',
                'users.email as actor_email',
            ])
            ->orderByDesc('audit_logs.created_at')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Lightweight System Status
        |--------------------------------------------------------------------------
        |
        | This does not create a new monitoring module. It only reports the
        | status that can be safely determined while this authenticated page
        | is being served.
        |
        */

        $databaseConnected = false;

        try {
            DB::connection()->getPdo();
            $databaseConnected = true;
        } catch (\Throwable $exception) {
            report($exception);
        }

        $systemStatus = [
            'database' => $databaseConnected
                ? 'Connected'
                : 'Unavailable',

            // If this request reached Laravel, the web server and application
            // are currently serving the page.
            'web_server' => 'Running',
            'application' => 'Available',

            'authentication' => Auth::check()
                ? 'Active'
                : 'Unavailable',
        ];


        return view(
            'dashboards.admin',
            compact(
                'greeting',
                'totalUsers',
                'activeAccounts',
                'newUsersThisMonth',
                'systemModules',
                'recentAuditLogs',
                'systemStatus'
            )
        );
    }


    /**
     * Display the registered user directory.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Start User Directory Query
        |--------------------------------------------------------------------------
        */

        $query = User::query()
            ->with('ptjDepartment');


        /*
        |--------------------------------------------------------------------------
        | Search Filter
        |--------------------------------------------------------------------------
        |
        | Search by:
        | - Staff ID
        | - Full Name
        | - Official Email
        |
        */

        $search = trim((string) $request->query('search', ''));

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where('staff_id', 'like', '%' . $search . '%')
                    ->orWhere('user_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Role Filter
        |--------------------------------------------------------------------------
        */

        $role = $request->query('role');

        if (in_array($role, [
            'ptj',
            'kj',
            'bendahari',
            'admin',
        ], true)) {

            $query->where('role', $role);
        }


        /*
        |--------------------------------------------------------------------------
        | PTJ Department Filter
        |--------------------------------------------------------------------------
        */

        $ptjId = $request->query('ptj_id');

        if ($ptjId !== null && $ptjId !== '') {
            $query->where('ptj_id', $ptjId);
        }


        /*
        |--------------------------------------------------------------------------
        | Account Status Filter
        |--------------------------------------------------------------------------
        */

        $status = $request->query('status');

        if ($status === 'active') {

            $query->where('is_active', true);

        } elseif ($status === 'inactive') {

            $query->where('is_active', false);
        }


        /*
        |--------------------------------------------------------------------------
        | Retrieve Directory Records
        |--------------------------------------------------------------------------
        */

        $users = $query
            ->orderBy('user_name')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Retrieve Active PTJ Departments
        |--------------------------------------------------------------------------
        */

        $ptjDepartments = PtjDepartment::query()
            ->where('is_active', true)
            ->orderBy('department_name')
            ->get();


        return view('user.userDirectory', compact(
            'users',
            'ptjDepartments'
        ));
    }
    
    /**
     * Display the Add New User form.
     */
    public function create(): View
    {
        $ptjDepartments = PtjDepartment::where('is_active', true)
            ->orderBy('department_name')
            ->get();

        return view('user.addUserForm', compact('ptjDepartments'));
    }

    /**
     * Register a new UCPMS user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'user_name' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:100',
                    'unique:users,email',
                ],

                'staff_id' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:users,staff_id',
                ],

                'role' => [
                    'required',
                    Rule::in([
                        'ptj',
                        'kj',
                        'bendahari',
                    ]),
                ],

                'ptj_id' => [
                    Rule::requiredIf(
                        fn () => in_array(
                            $request->role,
                            ['ptj', 'kj'],
                            true
                        )
                    ),

                    'nullable',

                    Rule::exists(
                        'ptj_departments',
                        'ptj_id'
                    )->where(
                        fn ($query) =>
                            $query->where(
                                'is_active',
                                true
                            )
                    ),
                ],

                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8),
                ],
            ],
            [
                'staff_id.unique' =>
                    'This Staff ID is already registered in UCPMS.',

                'email.email' =>
                    'Please enter a valid email address.',

                'email.unique' =>
                    'This email address is already registered in UCPMS.',

                'ptj_id.required' =>
                    'PTJ Department is required for PTJ and KJ users.',

                'ptj_id.exists' =>
                    'Please select a valid active PTJ Department.',

                'password.confirmed' =>
                    'The temporary password confirmation does not match.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Bendahari Does Not Have PTJ Mapping
        |--------------------------------------------------------------------------
        */

        if ($validated['role'] === 'bendahari') {

            $validated['ptj_id'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Create User + Audit Log
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated) {

            $newUser = User::create([
                'user_name' =>
                    $validated['user_name'],

                'email' =>
                    $validated['email'],

                'staff_id' =>
                    $validated['staff_id'],

                'role' =>
                    $validated['role'],

                'ptj_id' =>
                    $validated['ptj_id'] ?? null,

                /*
                | User model has:
                |
                | 'password' => 'hashed'
                |
                | Therefore Laravel automatically hashes this.
                */

                'password' =>
                    $validated['password'],

                'is_active' =>
                    true,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Audit Log
            |--------------------------------------------------------------------------
            |
            | procurement_request_id is NULL because this action does not belong
            | to any procurement request.
            |
            | user_id stores the ADMIN who performed the action.
            |
            */

            AuditLog::create([
                'procurement_request_id' =>
                    null,

                'user_id' =>
                    Auth::id(),

                'action' =>
                    'User Account Created',

                'description' =>
                    'System Administrator registered user '
                    . $newUser->staff_id
                    . ' with role '
                    . $newUser->role
                    . (
                        $newUser->ptj_id
                            ? ' and PTJ ID '
                                . $newUser->ptj_id
                            : ' with no PTJ mapping'
                    )
                    . '.',
            ]);
        });


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User registered successfully.'
            );
    }

    /**
     * Update an existing user's role and PTJ Department assignment.
     */
        public function update(
        Request $request,
        User $user
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Protect System Administrator Account
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return redirect()
                ->route('admin.users.index')
                ->with(
                    'error',
                    'The System Administrator account assignment cannot be modified.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'role' => [
                    'required',

                    Rule::in([
                        'ptj',
                        'kj',
                        'bendahari',
                    ]),
                ],

                'ptj_id' => [
                    Rule::requiredIf(
                        fn () => in_array(
                            $request->role,
                            ['ptj', 'kj'],
                            true
                        )
                    ),

                    'nullable',

                    Rule::exists(
                        'ptj_departments',
                        'ptj_id'
                    )->where(
                        fn ($query) =>
                            $query->where(
                                'is_active',
                                true
                            )
                    ),
                ],
            ],
            [
                'ptj_id.required' =>
                    'PTJ Department is required for PTJ and KJ users.',

                'ptj_id.exists' =>
                    'Please select a valid active PTJ Department.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Bendahari Does Not Have PTJ Mapping
        |--------------------------------------------------------------------------
        */

        if ($validated['role'] === 'bendahari') {

            $validated['ptj_id'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Store Original Assignment
        |--------------------------------------------------------------------------
        */

        $oldRole =
            $user->role;

        $oldPtjId =
            $user->ptj_id;


        $newRole =
            $validated['role'];

        $newPtjId =
            $validated['ptj_id'] ?? null;


        DB::transaction(function () use (
            $user,
            $oldRole,
            $oldPtjId,
            $newRole,
            $newPtjId
        ) {

            /*
            |--------------------------------------------------------------------------
            | Update User
            |--------------------------------------------------------------------------
            */

            $user->update([
                'role' =>
                    $newRole,

                'ptj_id' =>
                    $newPtjId,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Audit Only When Assignment Actually Changed
            |--------------------------------------------------------------------------
            */

            if (
                $oldRole !== $newRole
                ||
                (string) $oldPtjId
                    !==
                (string) $newPtjId
            ) {

                AuditLog::create([
                    'procurement_request_id' =>
                        null,

                    'user_id' =>
                        Auth::id(),

                    'action' =>
                        'User Assignment Updated',

                    'description' =>
                        'System Administrator updated user '
                        . $user->staff_id
                        . ' from role '
                        . $oldRole
                        . ' / PTJ '
                        . ($oldPtjId ?? 'Not Applicable')
                        . ' to role '
                        . $newRole
                        . ' / PTJ '
                        . ($newPtjId ?? 'Not Applicable')
                        . '.',
                ]);
            }
        });


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User role and department assignment updated successfully.'
            );
    }

    /**
     * Deactivate or reactivate a user account.
     */
    public function updateStatus(
        User $user
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Protect System Administrator Account
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return redirect()
                ->route('admin.users.index')
                ->with(
                    'error',
                    'The System Administrator account cannot be deactivated.'
                );
        }


        DB::transaction(function () use ($user) {

            /*
            |--------------------------------------------------------------------------
            | Determine New Status
            |--------------------------------------------------------------------------
            */

            $newStatus =
                ! $user->is_active;


            /*
            |--------------------------------------------------------------------------
            | Update User Status
            |--------------------------------------------------------------------------
            */

            $user->update([
                'is_active' =>
                    $newStatus,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Audit Log
            |--------------------------------------------------------------------------
            */

            AuditLog::create([
                'procurement_request_id' =>
                    null,

                'user_id' =>
                    Auth::id(),

                'action' =>
                    $newStatus
                        ? 'User Account Reactivated'
                        : 'User Account Deactivated',

                'description' =>
                    'System Administrator '
                    . (
                        $newStatus
                            ? 'reactivated'
                            : 'deactivated'
                    )
                    . ' user '
                    . $user->staff_id
                    . ' ('
                    . $user->user_name
                    . ').',
            ]);
        });


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                $user->is_active
                    ? 'User account reactivated successfully.'
                    : 'User account deactivated successfully.'
            );
    }
}