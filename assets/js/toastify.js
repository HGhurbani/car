Swal.fire({
  title: 'هل أنت متأكد؟',
  text: "لا يمكنك التراجع عن هذا الإجراء!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'نعم، احذف!',
  cancelButtonText: 'لا'
}).then((result) => {
  if (result.isConfirmed) {
    // تنفيذ عملية الحذف
  }
});
