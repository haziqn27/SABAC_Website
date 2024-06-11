<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_record";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Validate required fields
    $requiredFields = array(
        "degreeProgram", "date", "studentName", "guardianName", 
        "mobileNo", "dob", "idCard", "gender", "matricCourse", 
        "matricMarks", "matricTotalMarks", "intermediateCourse", 
        "intermediateMarks", "intermediateTotalMarks"
    );

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            echo "<script>alert('All required fields must be filled!'); window.location.href = 'admission.html';</script>";
            exit; // Stop further execution
        }
    }

// Handle file uploads and store file contents in variables
    $idForm_data = isset($_FILES["idForm"]["tmp_name"]) && !empty($_FILES["idForm"]["tmp_name"]) ? file_get_contents($_FILES["idForm"]["tmp_name"]) : null;
    $picture_data = isset($_FILES["picture"]["tmp_name"]) && !empty($_FILES["picture"]["tmp_name"]) ? file_get_contents($_FILES["picture"]["tmp_name"]) : null;
    $matricCard_data = isset($_FILES["matricCard"]["tmp_name"]) && !empty($_FILES["matricCard"]["tmp_name"]) ? file_get_contents($_FILES["matricCard"]["tmp_name"]) : null;
    $intermediateCard_data = isset($_FILES["intermediateCard"]["tmp_name"]) && !empty($_FILES["intermediateCard"]["tmp_name"]) ? file_get_contents($_FILES["intermediateCard"]["tmp_name"]) : null;

    // Prepare SQL statement
    $sql = "INSERT INTO student_form (course_name, date, student_name, father_name, phone_no, date_of_birth, cnic_Bform, cnic_img,  gender,student_image, matric, matric_Ob_marks, matric_tot_marks, matric_result_card, intermediate1, inter_Ob_marks, inter_tot_marks, inter_result_card) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    echo "intermediateCourse: "; var_dump($_POST["intermediateCourse"]);
    // Bind parameters
    $stmt->bind_param("sssssssbsisiiisssi", $_POST["degreeProgram"], $_POST["date"], $_POST["studentName"], $_POST["guardianName"], $_POST["mobileNo"], $_POST["dob"], $_POST["idCard"], $idForm_data, $_POST["gender"], $picture_data, $_POST["matricCourse"], $_POST["matricMarks"], $_POST["matricTotalMarks"], $matricCard_data, $_POST["intermediateCourse"], $_POST["intermediateMarks"], $_POST["intermediateTotalMarks"], $intermediateCard_data);

    // Execute the statement
    if ($stmt->execute()) {
        // echo "<script>alert('New record inserted successfully'); window.location.href = 'admission.html';</script>";
    } else {
        echo "<script>alert('Error inserting record: " . $conn->error . "'); window.location.href = 'admission.html';</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Optional: If you want to handle form not submitted case
    echo "<script>alert('Form not submitted');</script>";
}