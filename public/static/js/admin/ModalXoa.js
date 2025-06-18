
// Modal xác nhận xoá 
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const name = this.getAttribute('data-title') || '';
        const url = this.getAttribute('data-url') || '#';
        document.getElementById('deleteItemName').textContent = name;
        document.getElementById('confirmDeleteBtn').href = url;
        let modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    });
});
