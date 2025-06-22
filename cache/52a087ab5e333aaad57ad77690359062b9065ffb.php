


<?php $__env->startSection('title', 'Thông tin người dùng'); ?>

<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/ChiTietNguoiDung.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    // Dữ liệu mẫu người dùng
    $users = [
        [
            'id'=>1,
            'email'=>'admin@khfcinema.vn',
            'name'=>'Admin KHF',
            'role'=>'admin',
            'status'=>'active',
            'history'=>[],
            'offers'=>[]
        ],
        [
            'id'=>2,
            'email'=>'nguyenvanb@gmail.com',
            'name'=>'Nguyễn Văn B',
            'role'=>'guest',
            'status'=>'active',
            'history'=>[
                [
                    'movie'=>'Thanh Gươm Diệt Quỷ',
                    'showtime'=>'08:30 25/06/2024',
                    'seats'=>'G09, G10',
                    'price'=>170000,
                    'date'=>'2024-06-20',
                    'status'=>'paid'
                ],
                [
                    'movie'=>'Ký Ức Mùa Hè',
                    'showtime'=>'14:00 22/06/2024',
                    'seats'=>'B05',
                    'price'=>70000,
                    'date'=>'2024-06-10',
                    'status'=>'cancelled'
                ]
            ],
            'offers'=>[
                ['name'=>'Giảm 10% vé 2D', 'used_date'=>'2024-06-10'],
                ['name'=>'Tặng bắp nước', 'used_date'=>'2024-06-20']
            ]
        ],
        [
            'id'=>3,
            'email'=>'lethic@gmail.com',
            'name'=>'Lê Thị C',
            'role'=>'guest',
            'status'=>'locked',
            'history'=>[],
            'offers'=>[]
        ],
    ];

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $user = null;
    foreach($users as $u) {
        if($u['id'] == $id) {
            $user = $u;
            break;
        }
    }
?>

<div class="container py-4 content">
    <?php if($user): ?>
        <h1>Thông tin người dùng</h1><hr>
        <div class="mb-3 row">
            <div class="user-label col-sm-4">Email:</div>
            <div class="user-value col-sm-8"><?php echo e($user['email']); ?></div>
        </div>
        <div class="mb-3 row">
            <div class="user-label col-sm-4">Tên:</div>
            <div class="user-value col-sm-8"><?php echo e($user['name']); ?></div>
        </div>
        <div class="mb-3 row">
            <div class="user-label col-sm-4">Phân loại:</div>
            <div class="user-value col-sm-8">
                <?php if($user['role']=='admin'): ?>
                    <span class="badge status-admin">Admin</span>
                <?php else: ?>
                    <span class="badge status-guest">Khách</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="mb-3 row">
            <div class="user-label col-sm-4">Trạng thái:</div>
            <div class="user-value col-sm-8">
                <?php if($user['status']=='locked'): ?>
                    <span class="badge status-locked">Đã khóa</span>
                <?php else: ?>
                    <span class="badge bg-success">Hoạt động</span>
                <?php endif; ?>
            </div>
        </div>
        <hr>
        <h5>Lịch sử đặt vé</h5>
        <div class="table-responsive mb-3">
            <table class="table table-sm table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tên phim</th>
                        <th>Suất chiếu</th>
                        <th>Ghế</th>
                        <th>Giá</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($user['history'])): ?>
                        <?php $__currentLoopData = $user['history']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($h['movie']); ?></td>
                            <td><?php echo e($h['showtime']); ?></td>
                            <td><?php echo e($h['seats']); ?></td>
                            <td><?php echo e(number_format($h['price'],0,',','.')); ?> VNĐ</td>
                            <td><?php echo e(date('d/m/Y', strtotime($h['date']))); ?></td>
                            <td>
                                <?php if($h['status']=='paid'): ?>
                                    <span class="badge bg-success">Đã thanh toán</span>
                                <?php elseif($h['status']=='unpaid'): ?>
                                    <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Đã hủy</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted">Chưa có lịch sử đặt vé.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <h5>Ưu đãi đã sử dụng</h5>
        <div class="table-responsive mb-3">
            <table class="table table-sm table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tên ưu đãi</th>
                        <th>Ngày sử dụng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($user['offers'])): ?>
                        <?php $__currentLoopData = $user['offers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($offer['name']); ?></td>
                            <td><?php echo e(date('d/m/Y', strtotime($offer['used_date']))); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr><td colspan="2" class="text-center text-muted">Chưa sử dụng ưu đãi nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="/quan-ly-nguoi-dung" class="btn btn-secondary">Quay lại</a>
            <?php if($user['status']!='locked'): ?>
            <a href="/khoa-nguoi-dung?id=<?php echo e($user['id']); ?>" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn khóa tài khoản này?')">Khóa tài khoản</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-danger text-center">
            Không tìm thấy người dùng!
        </div>
        <div class="mt-4 text-center">
            <a href="/quan-ly-nguoi-dung" class="btn btn-secondary">Quay lại</a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Servers\test\app\Views/admin-views/QuanLyNguoiDung/ChiTietNguoiDung.blade.php ENDPATH**/ ?>