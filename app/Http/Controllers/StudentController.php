<?php

namespace App\Http\Controllers;

use App\Models\Admin\CsrStudent;
use App\Models\Bank;
use App\Models\Batch;
use App\Models\Course;
use App\Models\ImportStudent;
use App\Models\PaymentHistory;
use App\Models\ProvinceCity;
use App\Models\Qualification;
use App\Models\Source;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\StudentCoursePayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{

    public function index(Request $request)
    {
        $today = Carbon::today();
        $from = null;
        $s_csr = null;
        $to = null;
        $id = null;
        $status = $request->status;
        $csrs = User::where(['type' => 'csr', 'status' => 1])->get();
        $courses = Course::all();
    
        if (getUserType() == 'admin' || getUserType() == 'superadmin') {
            $studentCourses = StudentCourse::where(['status_id' => $status, 'is_confirmed' => 1, 'is_continued' => 1])->orderBy('id','DESC')->get();
        } elseif (getUserType() == 'csr' && Auth::user()->role_id == 1) {
            $studentCourses = StudentCourse::where(['status_id' => $status, 'is_confirmed' => 1, 'is_continued' => 1])->orderBy('id','DESC')->get();
        } elseif (getUserType() == 'csr' && Auth::user()->role_id == null) {
            $authenticatedUserId = Auth::id();

            $studentCourses = StudentCourse::where(['status_id' => $status, 'is_confirmed' => 1, 'is_continued' => 1])
            ->whereHas('student', function ($query) use ($authenticatedUserId) {
                $query->where('csr_id', $authenticatedUserId);
            })
            ->orderBy('id', 'DESC')
            ->get();
        }
        $modes = Bank::all();
        $batches = Batch::all();
    
        return view('admin.students.index', compact('studentCourses', 'courses','id','status', 'csrs','from', 'to', 's_csr', 'modes', 'batches'));
    }

    public function disContinuedStudent(){
        $studentCourses = StudentCourse::where('is_continued',0)->orderBy('id','DESC')->get();

        return view('admin.students.discontinued', compact('studentCourses'));
    }

    public function filterStudent(Request $req) {
        // dd($req->all());
        $status = $req->status;
        $batch = $req->batch;
        $csrs = User::where(['type' => 'csr', 'status' => 1])->get();
        $s_csr = $req->csr;   
        $courses = Course::all();
        $course = $req->course;   
    
        $studentCourses = StudentCourse::where(['status_id' => $status, 'is_confirmed' => 1, 'is_continued' => 1]);
        
        if ($s_csr) {
            $studentCourses->whereHas('student', function ($query) use ($s_csr) {
                $query->where('csr_id', $s_csr);
            });
        }
        if ($course) {
            $studentCourses->where('course_id', $course);
        }
        if ($batch) {
            $studentCourses->where('batch_id', $batch);
        }
    
        if($req->from && $req->to){
            $from = Carbon::createFromFormat('m/d/Y', $req->from)->startOfDay()->format('Y-m-d');
            $to = Carbon::createFromFormat('m/d/Y', $req->to)->endOfDay()->format('Y-m-d');
        }else{
            $from = null;
            $to = null;
        }
        if($from && $to){
            $studentCourses->whereBetween('created_at', [$from,$to]);
        }
    
        $studentCourses = $studentCourses->get();
        $modes = Bank::all();
        $batches = Batch::all();
    
        return view('admin.students.index', compact('studentCourses', 'modes', 'courses','status', 'csrs', 'from', 'to', 's_csr', 'batches'));
    }
    
    
        public function store(Request $req, $id = null){
            // dd($req->all());
            $req->validate([
                "name" => 'required',
                "email" => 'required',
            ]);
            
            // Validate payment amount before creating user and student records
            foreach($req->batch_id as $index => $batch){
                $mycourse = Course::find($req->courses[$index]);
                $course_fee = $mycourse->fee;
                $card_fee = $mycourse->card_fee;
                $discount = $req->discount[$index] ?? 0;
        
                $final_fee = $course_fee + $card_fee - $discount;
                $payment_first = $req->payment_first[$index];
                $payment_second = $req->payment_second[$index];
                $totalPayment = $payment_first + $payment_second;
        
                    if ($totalPayment > $final_fee) {
                        return back()->withErrors('Payment cannot be greater than fees.');
                    }
                }
            
            if($id){
                // Proceed with creating user and student records
                $csrStudent = CsrStudent::find($id);
            }else{
                $city = ProvinceCity::find($req->city_id);
                $course = Course::find(1);
                $import = ImportStudent::create([
                    'is_distributed' => 1,
                    'name' => $req->name,
                    'email' => $req->email,
                    'phone' => $req->whatsapp,
                    'cnic' => $req->cnic,
                    'city' => $city->name,
                    'course' => $course->name,
                    'datetime' => Carbon::now(),
                ]);

                $csrStudent = CsrStudent::create([
                    'csr_id' => getUserType() == 'admin' ? $req->csr : auth()->user()->id,
                    'student_id' => $import->id,
                ]);
            }
            $authuser = Auth::user();
            $userExists = User::where('email', $req->email)->exists();
            if($userExists){
                return back()->withErrors('Student already enrolled');
            }
        
            $user = User::create([
                "name" => $req->name,
                "email" => $req->email,
                "password" => Hash::make('password'),
                "type" => 'student',
            ]);
        
            $student = Student::create([
                "user_id" => $user->id,
                "name" => $req->name,
                "cnic" => $req->cnic,
                "card" => $req->card[$index] ?? 0, 
                "qualification_id" => $req->qualification_id,
                "city_id" => $req->city_id,
                "source_id" => $req->source,
                "csr_id" => getUserType() == 'admin' ? $req->csr : $authuser->id,
                "whatsapp" => $req->whatsapp,
                "address" => $req->address,
            ]);
        
            $csrStudent->update([
                'called_at' => Carbon::today(),
                'action_status_id' => 9, 
            ]);
        
            foreach($req->batch_id as $index => $batch){
                $mycourse = Course::find($req->courses[$index]);
                $course_fee = $mycourse->fee;
                $card_fee = $mycourse->card_fee;
                $discount = $req->discount[$index] ?? 0;
        
                $final_fee = $course_fee + $card_fee - $discount;
                $payment_first = $req->payment_first[$index];
                $payment_second = $req->payment_second[$index];
                $totalPayment = $payment_first + $payment_second;
        
                $status_id = 1; 
        
                if ($totalPayment < $final_fee) {
                    $status_id = 2;
                }
        
                $student_course = StudentCourse::create([
                    'student_id' => $student->id,
                    'batch_id' => $batch,
                    'course_id' => $req->courses[$index],
                    'fee' => $course_fee - $discount,
                    'discount' => $discount,
                    'status_id' => $status_id, 
                ]);
        
                $bank1 = Bank::find($req->mode_first[$index]);
                $bank1->update([
                    'amount' => $bank1->amount + $payment_first,
                ]);
        
                if($req->mode_second[$index]){
                    $bank2 = Bank::find($req->mode_second[$index]);
                    $bank2->update([
                        'amount' => $bank2->amount + $payment_second,
                    ]);    
                }
        
                $firstReceiptPath  = isset($req->first_receipt[$index]) ?  arruploadFile($req->first_receipt[$index], 'public/studentreceipts') : null;
                $secondReceiptPath = isset($req->second_receipt[$index]) ?  arruploadFile($req->second_receipt[$index], 'public/studentreceipts') : null;
        
                StudentCoursePayment::create([
                    'student_course_id' => $student_course->id,
                    'mode_first' => $req->mode_first[$index],
                    'payment_first' => $payment_first,
                    'payment_first_receipt' => $firstReceiptPath,
                    'payment_date_first' => $req->payment_date_first[$index],
                    'mode_second' => $req->mode_second[$index], 
                    'payment_second' => $payment_second,
                    'payment_second_receipt' => $secondReceiptPath,
                ]);
            }
        
            return redirect()->route('admin.hold.students')->withSuccess('Student enrolled successfully!');
        }

    public function show($id){
        $studentCourse = StudentCourse::find($id);
        $modes = Bank::all();

        return view('admin.students.show', compact('studentCourse', 'modes'));
    }

    public function makePayment(Request $req, $id){
        // dd($req->all());

        $req->validate([
            'mode_first' => 'required',
            'payment_first' => 'required',
        ]);

        $studentcourse = StudentCourse::find($id);

        $totalPayment = $req->payment_first + $req->payment_second;
        $remaining = $studentcourse->remainingfee();
        if ($totalPayment > $remaining) {
            return back()->withErrors('Payment cannot be greater then fees.');
        }

        $firstReceiptPath = null;
        if ($req->file('payment_first_receipt')) {
            $path = $req->file('payment_first_receipt');
            $target = 'public/payment_first_receipt';
            $firstReceiptPath = Storage::putFile($target, $path);
            $firstReceiptPath = substr($firstReceiptPath, 7, strlen($firstReceiptPath) - 7);
        }

        $secondReceiptPath = null;
        if ($req->file('payment_second_receipt')) {
            $path = $req->file('payment_second_receipt');
            $target = 'public/payment_second_receipt';
            $secondReceiptPath = Storage::putFile($target, $path);
            $secondReceiptPath = substr($secondReceiptPath, 7, strlen($secondReceiptPath) - 7);
        }

        $payment = StudentCoursePayment::create([
            'user_id' => Auth::user()->id,
            'student_course_id' => $id,
            'mode_first' => $req->mode_first,
            'payment_first' => $req->payment_first,
            'payment_first_receipt' => $firstReceiptPath,
            'payment_date_first' => $req->payment_date_first,
            'mode_second' => $req->mode_second, 

            'payment_second' => $req->payment_second,
            'payment_second_receipt' => $secondReceiptPath,
        ]);

        $studentcourse->update([
            'is_confirmed' => 0,
        ]);

        $bank1 = Bank::find($req->mode_first);
        $bank1->update([
            'amount' => $bank1->amount + $req->payment_first,
        ]);

        if($req->mode_second){
            $bank2 = Bank::find($req->mode_second);
            $bank2->update([
                'amount' => $bank2->amount + $req->payment_second,
            ]);    
        }

        // considering the newly added payment  
        $studentcourse->isPaidAllFee($payment);

        return back()->withSuccess('Payment added successfully');
    }


    public function isStudentEnrollAlready(Request $req)
    {
        $email = $req->email;
    
        if($email){
            $user = User::where('email', $email)->first();
    
            if($user){
                $student = Student::where('user_id', $user->id)->first();
    
                if($student){
                    $coursesList = "";
                    foreach($student->courses as $index => $course) {
                        $coursesList .= $course->course->name . " - " . $course->batch->number;
                        if($index < count($student->courses) - 1) {
                            $coursesList .= ",";
                        }
                    }
    
                    $message = "Student is already enrolled in " . $coursesList;
    
                    return response()->json(['enrolled' => true, 'student' => $student, 'message' => $message]);
                } else {
                    return response()->json(['enrolled' => false, 'message' => 'User is not enrolled as a student.']);
                }
            } else {
                return response()->json(['enrolled' => false, 'message' => 'User with this email does not exist.']);
            }
        } else {
            return response()->json(['enrolled' => false, 'message' => 'No email provided.']);
        }
        }

        public function isStudentEnrollAlreadyCnic(Request $req)
        {
            $cnic = $req->cnic;
        
            if($cnic){
                $student = Student::where('cnic', $cnic)->first();

                if($student){
                    $coursesList = "";
                    foreach($student->courses as $index => $course) {
                        $coursesList .= $course->course->name . " - " . $course->batch->number;
                        if($index < count($student->courses) - 1) {
                            $coursesList .= ", ";
                        }
                    }
    
                    $message = "Student is already enrolled in " . $coursesList;
    
                    return response()->json(['enrolled' => true, 'student' => $student, 'message' => $message]);
                }else{
                    return response()->json(['enrolled' => false, 'message' => 'Student not found.']);
                }

            } else {
                return response()->json(['enrolled' => false, 'message' => 'No email provided.']);
            }
        }

        public function isStudentEnrollAlreadyPhone(Request $req)
        {
            $phone = $req->phone;
        
            if($phone){
                $student = Student::where('whatsapp', $phone)->first();

                if($student){
                    $coursesList = "";
                    foreach($student->courses as $index => $course) {
                        $coursesList .= $course->course->name . " - " . $course->batch->number;
                        if($index < count($student->courses) - 1) {
                            $coursesList .= ", ";
                        }
                    }
    
                    $message = "Student is already enrolled in " . $coursesList;
    
                    return response()->json(['enrolled' => true, 'student' => $student, 'message' => $message]);
                }else{
                    return response()->json(['enrolled' => false, 'message' => 'Student not found.']);
                }

            } else {
                return response()->json(['enrolled' => false, 'message' => 'No email provided.']);
            }
        }


        public function discontinuedCourse($id, $action){
            $studentCourse = StudentCourse::find($id);
        
            if(!$studentCourse){
                return back()->withErrors('Course not found!');
            }
        
            $isContinued = ($action == 'discontinued') ? 0 : 1;
        
            $studentCourse->update([
                'is_continued' => $isContinued,
            ]);
        
            Session::put('student_course_id', $id);
        
            $message = ($action == 'discontinued') ? 'Student discontinued successfully!' : 'Student active successfully!';
        
            return back()->withSuccess($message);
        }
        

        public function edit($id){
            $stuCourse = StudentCourse::find($id);
            $qualifications = Qualification::all();
            $provinces = ProvinceCity::where('province_id', '!=' , null)->get();
            $courses = Course::all();
            $modes = Bank::all();
            $sources = Source::all();
            $batches = Batch::all();
            $csrs = User::where(['type' => 'csr', 'status' => 1])->get();

            return view('admin.students.edit', compact('stuCourse', 'modes', 'batches', 'csrs', 'qualifications', 'sources', 'provinces', 'courses'));
        }

        public function update(Request $request, $id){
            // dd($request->all()); 
            $request->validate([
                "name" => 'required',
                "email" => 'required',
            ]);
            $studentCourse = StudentCourse::find($id);
            $student = Student::find($studentCourse->student_id);
           
            $user = User::find($student->user_id);
        
            $user->update([
                "name" => $request->name,
                "email" => $request->email,
            ]);
        
            $student->update([
                "name" => $request->name,
                "cnic" => $request->cnic,
                "qualification_id" => $request->qualification_id,
                "city_id" => $request->city_id,
                "source_id" => $request->source,
                "csr_id" => $request->csr,
                "whatsapp" => $request->whatsapp,
                "address" => $request->address,
            ]);

            if($request->course != $studentCourse->course_id){
                $newCourse = Course::find($request->course);

                // dd($newCourse->card_fee);
                $totalPaidByStudent = 0;
                foreach($studentCourse->coursePayments as $pay){
                    $payment_first = $pay->is_confirmed_first ? $pay->payment_first : 0;
                    $payment_second = $pay->is_confirmed_second ? $pay->payment_second : 0;
                    $totalPaidByStudent += $payment_first + $payment_second;
                }

                $finalFee = $newCourse->fee + $newCourse->card_fee - ($request->discount ?? 0);

                $student->update([
                    "card" => $newCourse->card_fee,
                ]);

                $studentCourse->update([
                    'switch_from' => $studentCourse->course_id,
                    'course_id' => $request->course,
                    'discount' => $request->discount,
                    'batch_id' => $request->batch_id,
                    'status_id' => $totalPaidByStudent == $finalFee ? 1 : 2,
                    'fee' => $newCourse->fee,
                    'balance' => $totalPaidByStudent > $finalFee ? ($totalPaidByStudent - $finalFee) : 0,
                ]);
            }
        
            return back()->withSuccess('Student updated successfully!');
        }

        public function addReturns(Request $req, $id){

            $req->validate([
                'refund' => 'required',
            ]);

            $studentCourse = StudentCourse::find($id);

            if($req->refund >= $studentCourse->balance){
                return back()->withErrors('Refund should not be greater then balance!');
            }

            $balance = $req->refund - $studentCourse->balance;

            $studentCourse->update([
                'balance' => $balance,
                'refund' => $req->refund,
            ]);

            return back()->withSuccess('Refund added successfully!');
        }

        public function editPayment(Request $request){
            $paymentId = $request->paymentId;
            $paymentType = $request->paymentType;

            $payment = StudentCoursePayment::find($paymentId);

            if($payment){
                return response()->json(['payment' => $payment]);
            }
        }

        public function updateStudentPayment(Request $request, $id){
            $payment = StudentCoursePayment::find($id);

            $originalPayment = $payment->toArray();

            $paymentHistory = $payment->payment_history ? json_decode($payment->payment_history, true) : [];
            $paymentHistory[] = $originalPayment;
        
            PaymentHistory::create([
                'payment_id' => $payment->id,
                'payment_type' => $request->paymentType,
                'payment_history' => json_encode($paymentHistory),
            ]);

            $studentcourse = StudentCourse::find($payment->student_course_id);

            if($request->paymentType == 'first'){
                $payment->update([
                    'mode_first' => $request->mode_first,
                    'payment_first' => $request->payment_first,
                    'is_edit_first' => 1,
                ]);
            }elseif($request->paymentType == 'second'){
                $payment->update([
                    'mode_second' => $request->mode_second,
                    'payment_second' => $request->payment_second,
                    'is_edit_second' => 1,
                ]);
            }

            $totalPaid = 0;
    
            foreach ($studentcourse->coursePayments as $payment) {
                $totalPaid += $payment->payment_first + $payment->payment_second;
            }
    
            //1 for paid and 2 for partial
            $totalFee = $studentcourse->fee + $studentcourse->student->card;

            $status_id = ($totalPaid >= $totalFee) ? 1 : 2;
            $studentcourse->update([
                'status_id' => $status_id,
            ]);

            return back()->withSuccess('Updated!');

        }

}
