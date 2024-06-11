$(document).ready(function () {
  // Form submission
  $("form").submit(function (event) {
    // Prevent default form submission
    event.preventDefault();

    // Validate form fields
    var isValid = validateForm();

    // If form is valid, submit it
    if (isValid) {
      // Submit the form
      $(this).unbind("submit").submit();
    } else {
      // Scroll to the first invalid field
      scrollToFirstInvalidField();
    }
  });

  // Function to validate form fields
  function validateForm() {
    var isValid = true;

    // Check if any required fields are empty
    $("input[required], select[required]").each(function () {
      if (!$(this).val()) {
        isValid = false;
        $(this).addClass("border-red-500"); // Add red border for invalid fields
      } else {
        $(this).removeClass("border-red-500"); // Remove red border for valid fields
      }
    });

    // Check file size for each file input
    $("input[type='file']").each(function () {
      if (this.files.length > 0) {
        var fileSize = this.files[0].size; // Size of the first file
        // Check if file size exceeds 500KB (500 * 1024 bytes)
        if (fileSize > 500 * 1024) {
          isValid = false;
          $(this).addClass("border-red-500"); // Add red border for invalid file inputs
          alert("File size must be less than 500KB.");
        } else {
          $(this).removeClass("border-red-500"); // Remove red border for valid file inputs
        }
      }
    });

    return isValid;
  }

  // Function to scroll to the first invalid field
  function scrollToFirstInvalidField() {
    $("html, body").animate(
      {
        scrollTop: $(".border-red-500").first().offset().top - 100,
      },
      500
    );
  }
});
