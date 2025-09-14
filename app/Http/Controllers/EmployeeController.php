<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;




// app/Http/Controllers/EmployeeController.php

use App\Models\Employee;


class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::paginate(5);

        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:employees,code',
            'doj' => 'required|date',
            'department' => 'required',
            'project' => 'required',
        ]);

        $employee = Employee::create($request->all());

        return response()->json($employee, 201);
    }

    public function show(Employee $employee)
    {
        return response()->json($employee);
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:employees,code,' . $employee->id,
            'doj' => 'required|date',
            'department' => 'required',
            'project' => 'required',
        ]);

        $employee->update($request->all());

        return response()->json($employee);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(['message' => 'Employee deleted']);
    }
}