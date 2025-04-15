/* assets/js/main.js */

// مثال على دالة لإظهار Toast عبر Toastify
function showToast(message, type = 'success') {
  Toastify({
    text: message,
    duration: 3000,
    close: true,
    gravity: "top",
    position: "right",
    stopOnFocus: true,
    style: {
      // يمكنك تخصيص الألوان هنا
      // background: "linear-gradient(to right, #00b09b, #96c93d)",
    },
  }).showToast();
}

/**
 * تأكيد الحذف باستخدام SweetAlert2
 * مثال: <a href="#" onclick="confirmDelete(123)">حذف</a>
 */
function confirmDelete(recordId) {
  Swal.fire({
    title: 'هل أنت متأكد؟',
    text: "لا يمكنك التراجع عن هذا الإجراء!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'نعم، احذف!',
    cancelButtonText: 'إلغاء'
  }).then((result) => {
    if (result.isConfirmed) {
      // توجه مثلًا لصفحة الحذف
      window.location.href = "delete.php?id=" + recordId;
    }
  });
}
