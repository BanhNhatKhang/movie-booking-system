// ✅ SỬA: Function nhận element thay vì parameters
function resetPassword(button) {
  const userId = button.getAttribute("data-user-id");
  const userName = button.getAttribute("data-user-name");

  const message =
    'Bạn có chắc chắn muốn reset mật khẩu cho "' +
    userName +
    '"?\nMật khẩu mới sẽ là: 123456';

  if (confirm(message)) {
    document.getElementById("resetUserId").value = userId;
    document.getElementById("resetPasswordForm").submit();
  }
}
