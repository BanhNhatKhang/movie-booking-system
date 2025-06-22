                    <!-- Modal xác nhận xoá -->
                    <div class="modal fade modal-danger" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-danger shadow-lg">
                        <div class="modal-header">
                            <i class="bi bi-exclamation-octagon-fill fs-2 me-2"></i>
                            <h5 class="modal-title" id="deleteModalLabel">Xác nhận xoá</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-2">Bạn có chắc chắn muốn xoá <span class="fw-bold text-danger" id="deleteItemName"></span>?</p>
                            <p class="text-muted small mb-0">Thao tác này <span class="fw-bold">không thể hoàn tác</span>!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Huỷ</button>
                            <a href="#" class="btn btn-danger rounded-pill px-4" id="confirmDeleteBtn">
                            <i class="bi bi-trash"></i> Xoá
                            </a>
                        </div>
                        </div>
                    </div>
                    </div>
                </div><?php /**PATH C:\Servers\test\app\Views/admin-view/ModalXoa/ModalXoa.blade.php ENDPATH**/ ?>