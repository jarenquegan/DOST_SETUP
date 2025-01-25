const originalFirstName = document.getElementById("firstname").value.trim();
const originalLastName = document.getElementById("lastname").value.trim();

document.querySelectorAll("#firstname, #lastname").forEach((input) => {
  input.addEventListener("input", function () {
    const firstName =
      document.getElementById("firstname").value.trim() || originalFirstName;
    const lastName =
      document.getElementById("lastname").value.trim() || originalLastName;
    const fullName = firstName + " " + lastName;
    document.getElementById("userFullName").textContent = fullName;
  });
});

const previewImage = document.getElementById("previewImage");
document
  .getElementById("user_pic")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function (e) {
      previewImage.src = e.target.result;
    };
    reader.readAsDataURL(file);
  });

function removeProfilePicture() {
  document.getElementById("previewImage").src =
    "assets/images/circle-user-solid.svg";

  document.getElementById("removedProfilePicture").value =
    "circle-user-solid.svg";

  document.getElementById("user_pic").value = "";
}