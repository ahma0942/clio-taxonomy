<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeData;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeesController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $employee_id
     * @return \Illuminate\Http\Response
     */
    public function get_children($employee_id) {
        return response(Employee::where('superior_id', $employee_id)->pluck('name')->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_employee(Request $request) {
        $validatedData = $request->validate([
            'name' => ['required'],
            'role' => ['required', Rule::in(['manager', 'developer', 'misc'])],
            'superior_id' => ['required', 'exists:employees,id'],
            'department' => ['prohibited_unless:role,manager', 'required_if:role,manager'],
            'programming_language' => ['prohibited_unless:role,developer', 'required_if:role,developer'],
        ]);

        if (isset($validatedData['department']) || isset($validatedData['programming_language'])) {
            $employee_data = new EmployeeData;
            $employee_data->department = $validatedData['department'] ?? '';
            $employee_data->programming_language = $validatedData['programming_language'] ?? '';
            $employee_data->save();
        }

        $employee = new Employee;
        $employee->name = $validatedData['name'];
        $employee->role = $validatedData['role'];
        $employee->superior_id = $validatedData['superior_id'];
        $employee->employees_data_id = $employee_data->id ?? null;
        $employee->save();

        return response($employee->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $employee_id
     * @return \Illuminate\Http\Response
     */
    public function update_parent(Request $request, $employee_id) {
        $validatedData = $request->validate([
            'superior_id' => ['required', 'exists:employees,id', 'min:1', 'max:' . ($employee_id - 1)]
        ]);

        $employee = Employee::find($employee_id);
        $employee->superior_id = $validatedData['superior_id'];
        $employee->save();

        return response($employee->id);
    }
}
