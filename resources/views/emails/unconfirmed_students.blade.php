<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm Payments</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f4f4f4;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    h1 {
      color: #333;
      text-align: center;
    }
    p {
      color: #555;
      line-height: 1.6;
    }
    .btn {
      display: inline-block;
      padding: 10px 20px;
      margin: 10px 0;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
    }
    .btn:hover {
      background-color: #0056b3;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Confirm Payments</h1>
    <p>Dear Admin,</p>
    <p>I hope this email finds you well. I wanted to inform you that we have received several payments that require your confirmation.</p>
    <table>
      <thead>
        <tr>
          <th>CSR Name</th>
          <th>Student Name</th>
          <th>Course</th>
        </tr>
      </thead> 
      <tbody>
        @foreach($studentCourses as $index => $course)
        <tr>
          <td>{{ $course->student->csr->name }}</td>
          <td>{{ $course->student->name }}</td>
          <td>{{ $course->course->name }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <p>Please review the details above and kindly confirm these payments at your earliest convenience.</p>
    <p>Thank you for your attention to this matter.</p>
    <p>Best regards,</p>
    <p>NIAIS</p>
    <a href="https://onsite-portal.niais.org/admin/hold-students" class="btn">View Payments</a>
  </div>
</body>
</html>
