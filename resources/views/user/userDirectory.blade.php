@extends('layouts.ucpms')

@section('title', 'User Directory | UCPMS')

@section('page-style')

    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-heading h1 {
        font-size: 28px;
        color: var(--navy);
        letter-spacing: -0.5px;
    }

    .page-heading p {
        margin-top: 6px;
        font-size: 13px;
        color: var(--muted);
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;

        padding: 11px 16px;

        border: none;
        border-radius: 10px;

        background: var(--primary);
        color: white;

        font-size: 12px;
        font-weight: 700;

        cursor: pointer;
    }

    .alert {
        padding: 13px 15px;
        margin-bottom: 18px;

        border-radius: 10px;

        font-size: 13px;
        font-weight: 600;
    }

    .alert-success {
        background: var(--success-soft);
        color: var(--success);
    }

    .alert-danger {
        background: var(--danger-soft);
        color: var(--danger);
    }

    .filter-card,
    .table-card {
        background: white;

        border: 1px solid var(--border);
        border-radius: 14px;

        box-shadow: var(--shadow);
    }

    .filter-card {
        padding: 17px;
        margin-bottom: 18px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1.4fr 1fr auto;
        gap: 11px;
        align-items: end;
    }

    .form-group label {
        display: block;

        margin-bottom: 6px;

        color: #53627a;

        font-size: 11px;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        height: 41px;

        padding: 0 11px;

        border: 1px solid #dbe2ed;
        border-radius: 9px;

        background: white;
        color: var(--text);

        outline: none;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }

    .filter-actions {
        display: flex;
        gap: 7px;
    }

    .btn-filter,
    .btn-clear {
        height: 41px;

        padding: 0 13px;

        border-radius: 9px;

        font-size: 11px;
        font-weight: 700;

        cursor: pointer;
    }

    .btn-filter {
        border: none;
        background: var(--primary);
        color: white;
    }

    .btn-clear {
        display: inline-flex;
        align-items: center;

        border: 1px solid #dbe2ed;

        background: white;
        color: #65738b;
    }

    .table-card {
        overflow: hidden;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;

        padding: 16px 18px;

        border-bottom: 1px solid var(--border);
    }

    .table-header strong {
        color: var(--navy);
        font-size: 14px;
    }

    .table-header span {
        color: var(--muted);
        font-size: 11px;
    }

    .table-scroll {
        overflow-x: auto;
    }

    .user-table {
        width: 100%;
        min-width: 1050px;

        border-collapse: collapse;
    }

    .user-table th {
        padding: 12px 14px;

        background: #f8f9fd;

        border-bottom: 1px solid var(--border);

        text-align: left;

        color: #78869c;

        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.35px;
    }

    .user-table td {
        padding: 14px;

        border-bottom: 1px solid #edf0f5;

        font-size: 12px;
        vertical-align: middle;
    }

    .user-table tbody tr:hover {
        background: #fcfcff;
    }

    .staff-id {
        display: inline-block;

        padding: 5px 8px;

        border-radius: 6px;

        background: var(--primary-soft);
        color: var(--primary);

        font-size: 10px;
        font-weight: 800;
    }

    .user-full-name {
        font-weight: 700;
        color: var(--navy);
    }

    .email-text,
    .department-text {
        color: #637188;
    }

    .not-applicable {
        color: #99a2b1;
        font-style: italic;
    }

    .badge {
        display: inline-flex;
        align-items: center;

        padding: 5px 9px;

        border-radius: 999px;

        font-size: 10px;
        font-weight: 800;
    }

    .role-ptj {
        background: #e8f1ff;
        color: #1769e8;
    }

    .role-kj {
        background: #fff0f2;
        color: #c9152d;
    }

    .role-bendahari {
        background: #eaf8ee;
        color: #19843a;
    }

    .role-admin {
        background: #f1e9ff;
        color: #6d28d9;
    }

    .status-active {
        background: var(--success-soft);
        color: var(--success);
    }

    .status-inactive {
        background: #f0f2f5;
        color: #7b8493;
    }

    .protected {
        background: #f1e9ff;
        color: #6d28d9;
    }

    .action-group {
        display: flex;
        gap: 7px;
        flex-wrap: wrap;
    }

    .btn-edit,
    .btn-status {
        padding: 7px 9px;

        border-radius: 8px;

        font-size: 10px;
        font-weight: 700;

        cursor: pointer;
    }

    .btn-edit {
        border: 1px solid #d7dfeb;

        background: white;
        color: var(--primary);
    }

    .btn-deactivate {
        border: 1px solid #f0bec5;

        background: #fff8f9;
        color: var(--danger);
    }

    .btn-reactivate {
        border: 1px solid #bce2d2;

        background: #f2fbf7;
        color: var(--success);
    }

    .empty-state {
        padding: 45px 20px;

        text-align: center;

        color: var(--muted);
        font-size: 13px;
    }

    .pagination-area {
        display: flex;
        justify-content: space-between;
        align-items: center;

        padding: 14px 18px;

        border-top: 1px solid var(--border);
    }

    .pagination-area span {
        color: var(--muted);
        font-size: 11px;
    }

    .pagination-buttons {
        display: flex;
        gap: 7px;
    }

    .page-button {
        padding: 7px 10px;

        border: 1px solid #dbe2ed;
        border-radius: 8px;

        background: white;
        color: var(--primary);

        font-size: 11px;
        font-weight: 700;
    }

    .page-button.disabled {
        pointer-events: none;
        color: #a4acb9;
        background: #f7f8fa;
    }


    /* =========================================================
       EDIT MODAL
    ========================================================= */

    .modal-overlay {
        position: fixed;
        inset: 0;

        z-index: 2000;

        display: none;
        align-items: center;
        justify-content: center;

        padding: 20px;

        background: rgba(24, 32, 52, 0.48);
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-card {
        width: 100%;
        max-width: 500px;

        background: white;

        border-radius: 15px;

        box-shadow: 0 25px 70px rgba(20, 30, 60, 0.25);

        overflow: hidden;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        gap: 15px;

        padding: 18px 20px;

        border-bottom: 1px solid var(--border);
    }

    .modal-title {
        color: var(--navy);

        font-size: 17px;
        font-weight: 800;
    }

    .modal-user-name {
        margin-top: 4px;

        color: var(--muted);
        font-size: 12px;
    }

    .modal-close {
        border: none;

        background: transparent;

        color: #7e8898;

        font-size: 24px;

        cursor: pointer;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-group {
        margin-bottom: 15px;
    }

    .modal-group label {
        display: block;

        margin-bottom: 7px;

        color: #53627a;

        font-size: 12px;
        font-weight: 700;
    }

    .modal-control {
        width: 100%;
        height: 42px;

        padding: 0 11px;

        border: 1px solid #dbe2ed;
        border-radius: 9px;

        background: white;
    }

    .modal-control:disabled {
        background: #f3f5f8;

        color: #8a95a6;
    }

    .modal-note {
        margin-top: 7px;

        color: var(--muted);

        font-size: 11px;
        line-height: 1.5;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 8px;

        padding: 15px 20px;

        border-top: 1px solid var(--border);

        background: #fafbfc;
    }

    .btn-cancel,
    .btn-save {
        padding: 9px 14px;

        border-radius: 9px;

        font-size: 11px;
        font-weight: 700;

        cursor: pointer;
    }

    .btn-cancel {
        border: 1px solid #dbe2ed;

        background: white;
        color: #66758b;
    }

    .btn-save {
        border: none;

        background: var(--primary);
        color: white;
    }


    @media (max-width: 1050px) {

        .filter-grid {
            grid-template-columns: 1fr 1fr;
        }

        .filter-actions {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 650px) {

        .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .btn-primary {
            width: 100%;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            grid-column: auto;
        }

        .pagination-area {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }

@endsection


@section('content')

    <div class="page-heading">

        <div>

            <h1>System User Directory</h1>

            <p>
                View and manage registered UCPMS users, their roles,
                PTJ assignments and account status.
            </p>

        </div>


        <a
            href="{{ route('admin.users.create') }}"
            class="btn-primary"
        >
            + Add New User
        </a>

    </div>


    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if (session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    @if ($errors->any())

        <div class="alert alert-danger">

            @foreach ($errors->all() as $error)

                <div>{{ $error }}</div>

            @endforeach

        </div>

    @endif


    <!-- =====================================================
         SEARCH AND FILTER
    ====================================================== -->

    <form
        method="GET"
        action="{{ route('admin.users.index') }}"
        class="filter-card"
    >

        <div class="filter-grid">


            <div class="form-group">

                <label for="search">
                    Search
                </label>

                <input
                    id="search"
                    type="text"
                    name="search"
                    class="form-control"
                    value="{{ request('search') }}"
                    placeholder="Name, Staff ID or email"
                >

            </div>


            <div class="form-group">

                <label for="role">
                    Role
                </label>

                <select
                    id="role"
                    name="role"
                    class="form-control"
                >

                    <option value="">
                        All Roles
                    </option>

                    <option value="ptj" @selected(request('role') === 'ptj')>
                        PTJ Staff
                    </option>

                    <option value="kj" @selected(request('role') === 'kj')>
                        Ketua Jabatan
                    </option>

                    <option
                        value="bendahari"
                        @selected(request('role') === 'bendahari')
                    >
                        Bendahari
                    </option>

                    <option
                        value="admin"
                        @selected(request('role') === 'admin')
                    >
                        System Administrator
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label for="ptj_id">
                    PTJ Department
                </label>

                <select
                    id="ptj_id"
                    name="ptj_id"
                    class="form-control"
                >

                    <option value="">
                        All Departments
                    </option>

                    @foreach ($ptjDepartments as $department)

                        <option
                            value="{{ $department->ptj_id }}"
                            @selected(
                                (string) request('ptj_id')
                                ===
                                (string) $department->ptj_id
                            )
                        >
                            {{ $department->department_name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="form-group">

                <label for="status">
                    Account Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="form-control"
                >

                    <option value="">
                        All Status
                    </option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        @selected(request('status') === 'inactive')
                    >
                        Inactive
                    </option>

                </select>

            </div>


            <div class="filter-actions">

                <button
                    type="submit"
                    class="btn-filter"
                >
                    Filter
                </button>

                <a
                    href="{{ route('admin.users.index') }}"
                    class="btn-clear"
                >
                    Clear
                </a>

            </div>

        </div>

    </form>


    <!-- =====================================================
         DIRECTORY
    ====================================================== -->

    <div class="table-card">


        <div class="table-header">

            <strong>
                Registered Users
            </strong>

            <span>
                {{ $users->total() }}
                {{ $users->total() === 1 ? 'user' : 'users' }}
            </span>

        </div>


        @if ($users->count())


            <div class="table-scroll">

                <table class="user-table">


                    <thead>

                        <tr>

                            <th>Staff ID</th>
                            <th>Full Name</th>
                            <th>Official Email</th>
                            <th>Assigned Role</th>
                            <th>PTJ Department</th>
                            <th>Account Status</th>
                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($users as $directoryUser)

                            @php

                                $roleClass = match ($directoryUser->role) {
                                    'ptj' => 'role-ptj',
                                    'kj' => 'role-kj',
                                    'bendahari' => 'role-bendahari',
                                    'admin' => 'role-admin',
                                    default => '',
                                };

                                $roleName = match ($directoryUser->role) {
                                    'ptj' => 'PTJ Staff',
                                    'kj' => 'KJ',
                                    'bendahari' => 'Bendahari',
                                    'admin' => 'Administrator',
                                    default => ucfirst($directoryUser->role),
                                };

                            @endphp


                            <tr>


                                <td>

                                    <span class="staff-id">
                                        {{ $directoryUser->staff_id }}
                                    </span>

                                </td>


                                <td>

                                    <span class="user-full-name">
                                        {{ $directoryUser->user_name }}
                                    </span>

                                </td>


                                <td>

                                    <span class="email-text">
                                        {{ $directoryUser->email }}
                                    </span>

                                </td>


                                <td>

                                    <span class="badge {{ $roleClass }}">
                                        {{ $roleName }}
                                    </span>

                                </td>


                                <td>

                                    @if (
                                        in_array(
                                            $directoryUser->role,
                                            ['ptj', 'kj'],
                                            true
                                        )
                                    )

                                        <span class="department-text">

                                            {{ $directoryUser->ptjDepartment?->department_name
                                                ?? 'Unassigned' }}

                                        </span>

                                    @else

                                        <span class="not-applicable">
                                            Not Applicable
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if ($directoryUser->is_active)

                                        <span class="badge status-active">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge status-inactive">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if ($directoryUser->role === 'admin')

                                        <span
                                            class="badge protected"
                                            title="Privileged Administrator account"
                                        >
                                            Protected Account
                                        </span>

                                    @else


                                        <div class="action-group">


                                            <button
                                                type="button"
                                                class="btn-edit edit-user-button"

                                                data-id="{{ $directoryUser->user_id }}"
                                                data-name="{{ $directoryUser->user_name }}"
                                                data-role="{{ $directoryUser->role }}"
                                                data-ptj="{{ $directoryUser->ptj_id }}"
                                            >
                                                View / Edit
                                            </button>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.users.status',
                                                    $directoryUser
                                                ) }}"
                                                onsubmit="
                                                    return confirm(
                                                        '{{ $directoryUser->is_active
                                                            ? 'Are you sure you want to deactivate this user?'
                                                            : 'Are you sure you want to reactivate this user?' }}'
                                                    );
                                                "
                                            >

                                                @csrf
                                                @method('PATCH')


                                                @if ($directoryUser->is_active)

                                                    <button
                                                        type="submit"
                                                        class="btn-status btn-deactivate"
                                                    >
                                                        Deactivate
                                                    </button>

                                                @else

                                                    <button
                                                        type="submit"
                                                        class="btn-status btn-reactivate"
                                                    >
                                                        Reactivate
                                                    </button>

                                                @endif

                                            </form>


                                        </div>


                                    @endif

                                </td>


                            </tr>

                        @endforeach

                    </tbody>


                </table>

            </div>


            @if ($users->hasPages())

                <div class="pagination-area">


                    <span>
                        Page {{ $users->currentPage() }}
                        of {{ $users->lastPage() }}
                    </span>


                    <div class="pagination-buttons">


                        @if ($users->onFirstPage())

                            <span class="page-button disabled">
                                Previous
                            </span>

                        @else

                            <a
                                href="{{ $users->previousPageUrl() }}"
                                class="page-button"
                            >
                                Previous
                            </a>

                        @endif


                        @if ($users->hasMorePages())

                            <a
                                href="{{ $users->nextPageUrl() }}"
                                class="page-button"
                            >
                                Next
                            </a>

                        @else

                            <span class="page-button disabled">
                                Next
                            </span>

                        @endif


                    </div>


                </div>

            @endif


        @else


            <div class="empty-state">

                No registered users match the selected search or filters.

            </div>


        @endif


    </div>


    <!-- =====================================================
         EDIT USER MODAL
    ====================================================== -->

    <div
        id="editUserModal"
        class="modal-overlay"
    >

        <div class="modal-card">


            <div class="modal-header">

                <div>

                    <div class="modal-title">
                        Update User Assignment
                    </div>

                    <div
                        id="modalUserName"
                        class="modal-user-name"
                    ></div>

                </div>


                <button
                    type="button"
                    id="closeModal"
                    class="modal-close"
                >
                    &times;
                </button>

            </div>


            <form
                id="editUserForm"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="modal-body">


                    <div class="modal-group">

                        <label for="editRole">
                            System Role
                        </label>

                        <select
                            id="editRole"
                            name="role"
                            class="modal-control"
                            required
                        >

                            <option value="ptj">
                                PTJ Administrative Staff
                            </option>

                            <option value="kj">
                                Ketua Jabatan (KJ)
                            </option>

                            <option value="bendahari">
                                Bendahari
                            </option>

                        </select>

                    </div>


                    <div class="modal-group">

                        <label for="editPtj">
                            PTJ Department
                        </label>

                        <select
                            id="editPtj"
                            name="ptj_id"
                            class="modal-control"
                        >

                            <option value="">
                                Select PTJ Department
                            </option>

                            @foreach ($ptjDepartments as $department)

                                <option value="{{ $department->ptj_id }}">

                                    {{ $department->department_name }}

                                </option>

                            @endforeach

                        </select>


                        <div
                            id="departmentNote"
                            class="modal-note"
                        >
                            PTJ Department is required for PTJ and KJ users.
                        </div>

                    </div>


                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        id="cancelModal"
                        class="btn-cancel"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn-save"
                    >
                        Save Changes
                    </button>

                </div>


            </form>


        </div>

    </div>

@endsection


@section('page-script')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const modal =
        document.getElementById('editUserModal');

    const form =
        document.getElementById('editUserForm');

    const userName =
        document.getElementById('modalUserName');

    const role =
        document.getElementById('editRole');

    const ptj =
        document.getElementById('editPtj');

    const departmentNote =
        document.getElementById('departmentNote');

    const closeButton =
        document.getElementById('closeModal');

    const cancelButton =
        document.getElementById('cancelModal');


    const baseUrl =
        @json(url('/admin/users'));


    function updateDepartmentState() {

        const requiresPtj =
            role.value === 'ptj'
            ||
            role.value === 'kj';


        if (requiresPtj) {

            ptj.disabled = false;
            ptj.required = true;

            departmentNote.textContent =
                'PTJ Department is required for PTJ and KJ users.';

        } else {

            ptj.value = '';

            ptj.disabled = true;
            ptj.required = false;

            departmentNote.textContent =
                'PTJ Department is not applicable for Bendahari users.';
        }
    }


    document
        .querySelectorAll('.edit-user-button')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const id =
                    this.dataset.id;

                const selectedRole =
                    this.dataset.role;

                const selectedPtj =
                    this.dataset.ptj;


                userName.textContent =
                    this.dataset.name;


                form.action =
                    `${baseUrl}/${id}`;


                role.value =
                    selectedRole;


                updateDepartmentState();


                if (
                    selectedRole === 'ptj'
                    ||
                    selectedRole === 'kj'
                ) {

                    ptj.value =
                        selectedPtj || '';
                }


                modal.classList.add('show');

            });

        });


    role.addEventListener(
        'change',
        updateDepartmentState
    );


    function closeEditModal() {

        modal.classList.remove('show');

    }


    closeButton.addEventListener(
        'click',
        closeEditModal
    );


    cancelButton.addEventListener(
        'click',
        closeEditModal
    );


    modal.addEventListener(
        'click',
        function (event) {

            if (event.target === modal) {
                closeEditModal();
            }

        }
    );


    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape'
                &&
                modal.classList.contains('show')
            ) {

                closeEditModal();
            }

        }
    );

});

</script>

@endsection