@extends('layouts.admin.master')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="container py-4 content">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-key me-2"></i>Đổi mật khẩu
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/xu-ly-doi-mat-khau">
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu cũ <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="mat_khau_cu" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="mat_khau_moi" 
                                   minlength="6" required>
                            <small class="text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="xac_nhan_mat_khau" 
                                   minlength="6" required>
                        </div>
                        
                        <div class="text-center">
                            <a href="/admin/thong-tin-ca-nhan" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-key me-1"></i>Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection