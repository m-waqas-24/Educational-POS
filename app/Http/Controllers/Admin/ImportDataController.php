<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DuplicateStudent;
use App\Models\ImportStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ImportDataController extends Controller
{
    
    public function index(){
        $from = null;
        $to = null;
        $importedStudents = ImportStudent::orderBy('id','DESC')->where('is_distributed', 0)->get();
        $distributedStudents = ImportStudent::orderBy('id','DESC')->where('is_distributed', 1)->get();

        return view('admin.importdata.index', compact('importedStudents', 'distributedStudents', 'from', 'to'));
    }

   public function importData(Request $request){
    $request->validate([
        'csv_file' => 'required|mimes:csv,txt'
    ]);

    $path = $request->file('csv_file')->getRealPath();
    $data = array_map('str_getcsv', file($path));
        $rowCount = 0; // Initialize row count


    foreach ($data as $row) {
         $rowCount++; 
        if (empty($row[0])) {
            continue; // Skip empty rows
        }

        $phoneNumber = str_replace('p:', '', $row[2]);

        $carbonDate = Carbon::createFromFormat('Y-m-d\TH:i:sP', $row[6]);
        // Format the date as YYYY-MM-DD
        $formattedDate = $carbonDate->format('Y-m-d');

        // Check if the student already exists
        $studentExists = ImportStudent::where(function($query) use ($row) {
            $query->where('email', $row[1]);
            if (!empty($row[3])) {
                $query->orWhere('cnic', $row[3]);
            }
        })->exists();

        if (!$studentExists) {
            // Create a new entry in the ImportStudent table
            ImportStudent::create([
                'name' => $row[0],
                'email' => $row[1],
                'phone' => $phoneNumber,
                'cnic' => $row[3],
                'city' => $row[5],
                'course' => $row[4],
                'datetime' => $formattedDate,
            ]);
        } else {
            // Check if the student exists in the DuplicateStudent table
            $duplicateStudentExists = DuplicateStudent::where(function ($query) use ($row) {
                $query->where('email', $row[1]);
                if (!empty($row[3])) {
                    $query->orWhere('cnic', $row[3]);
                }
            })->exists();

            if (!$duplicateStudentExists) {
                // Create a new entry in the DuplicateStudent table
                DuplicateStudent::create([
                    'name' => $row[0],
                    'email' => $row[1],
                    'phone' => $phoneNumber,
                    'cnic' => $row[3],
                    'city' => $row[5],
                    'course' => $row[4],
                    'datetime' => $formattedDate,
                ]);
            }
        }
    }
    //   dd('Total rows checked: ' . $rowCount);

    return back()->withSuccess('Data imported successfully!');
}


    public function duplicateData(){
        $students = DuplicateStudent::orderBy('id','DESC')->get();

        return view('admin.importdata.duplicate-student', compact('students'));
    }

}
