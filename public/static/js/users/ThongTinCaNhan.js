// Kiểm tra mật khẩu khớp
document
  .getElementById("changePasswordForm")
  .addEventListener("submit", function (e) {
    const newPassword = document.getElementById("newPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (newPassword !== confirmPassword) {
      e.preventDefault();
      alert("Mật khẩu mới không khớp!");
      return false;
    }

    if (newPassword.length < 6) {
      e.preventDefault();
      alert("Mật khẩu phải có ít nhất 6 ký tự!");
      return false;
    }
  });
