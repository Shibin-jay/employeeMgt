<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employees.index');
    }

    public function getEmployees()
    {
        return DataTables::of(Employee::query())
            ->addColumn('actions', function ($employee) {
                return '<button class="btn btn-sm btn-primary edit" data-id="'.$employee->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger delete" data-id="'.$employee->id.'">Delete</button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $employee = Employee::create($request->all());
        return response()->json(['success' => 'Employee added successfully!', 'employee' => $employee]);
    }

    public function edit(Employee $employee)
    {
        return response()->json($employee);
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->update($request->all());
        return response()->json(['success' => 'Employee updated successfully!']);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(['success' => 'Employee deleted successfully!']);
    }
}
