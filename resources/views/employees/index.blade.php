@extends('layouts.master')

@section('title', 'Employees')

@section('content')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<div class="container mt-4">
    <table id="employeeTable" class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Position</th>
                <th>Salary</th>
                <th>Actions</th> <!-- Fix actions column -->
            </tr>
        </thead>
    </table>
    <button id="addEmployee" class="btn btn-success">Add Employee</button>
</div>

<!-- Add/Edit Employee Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">Add Employee</h5>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>
            <div class="modal-body">
                <form id="employeeForm">
                    @csrf
                    <input type="hidden" id="employee_id">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" id="position" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Salary</label>
                        <input type="number" id="salary" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    let table = $('#employeeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("employees.data") }}',
        columns: [
            { data: 'name' },
            { data: 'email' },
            { data: 'position' },
            { data: 'salary' },
            { data: 'actions', orderable: false, searchable: true }
        ]
    });

    $('#addEmployee').click(function() {
        $('#employeeModal').modal('show');
        $('#employeeForm')[0].reset();
        $('#employee_id').val(''); 
    });

    $('#employeeForm').submit(function(e) {
        e.preventDefault();
        let id = $('#employee_id').val();
        let url = id ? `/employees/${id}` : '/employees';

        $.ajax({
            url: url,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: {
                name: $('#name').val(),
                email: $('#email').val(),
                position: $('#position').val(),
                salary: $('#salary').val()
            },
            success: function(response) {
                Swal.fire('Success', response.success, 'success');
                $('#employeeModal').modal('hide');
                table.ajax.reload();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // Edit Employee
    $('#employeeTable').on('click', '.edit', function() {
        let id = $(this).data('id');
        $.get(`/employees/${id}/edit`, function(employee) {
            $('#employee_id').val(employee.id);
            $('#name').val(employee.name);
            $('#email').val(employee.email);
            $('#position').val(employee.position);
            $('#salary').val(employee.salary);
            $('#employeeModal').modal('show');
        });
    });

    // Delete Employee
    $('#employeeTable').on('click', '.delete', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/employees/${id}`,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire('Deleted!', response.success, 'success');
                        table.ajax.reload();
                    }
                });
            }
        });
    });
});
</script>
@endsection
