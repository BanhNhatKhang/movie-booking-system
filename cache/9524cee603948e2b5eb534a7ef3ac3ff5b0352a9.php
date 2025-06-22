


<?php $__env->startSection('title', 'Quản lý Ghế phòng chiếu'); ?>

<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/QuanLyPhongGhe.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $activePage = 'PhongGhe';
    $roomTypes = ['2D', 'IMAX', 'Event'];
    $rooms = [
        1 => ['name'=>'Phòng 1', 'type'=>'2D'],
        2 => ['name'=>'Phòng 2', 'type'=>'IMAX'],
        3 => ['name'=>'Phòng 3', 'type'=>'Event'],
    ];
    $roomId = isset($_GET['room']) && isset($rooms[$_GET['room']]) ? intval($_GET['room']) : 1;
    $room = $rooms[$roomId];

    if ($roomId == 2) {
        $rows = ['A','B','C','D','E'];
        $cols = range(1,10);
    } else {
        $rows = ['A','B','C','D','E','F','G','H'];
        $cols = range(1,12);
    }

    $room['seats'] = [];
    foreach($rows as $row) {
        foreach($cols as $col) {
            $code = $row . str_pad($col,2,'0',STR_PAD_LEFT);
            if(in_array($row, ['A','B','C'])) $type = 'normal';
            elseif(in_array($row, ['D','E','F','G'])) $type = 'vip';
            else $type = 'luxury';
            $status = 'available';
            $room['seats'][$code] = ['type'=>$type, 'status'=>$status];
        }
    }
?>

<div class="container py-4 content">
    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh;">
        <div>
            <h1 class="mb-4 text-center">Quản lý Ghế - <?php echo e($room['name']); ?></h1>
            <!-- Chọn phòng -->
            <form class="row g-3 align-items-center mb-4 justify-content-center" method="get" action="">
                <div class="col-auto">
                    <label class="form-label fw-bold">Chọn phòng:</label>
                </div>
                <div class="col-auto">
                    <select name="room" class="form-select" onchange="this.form.submit()">
                        <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id=>$r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($id); ?>"<?php echo e($roomId==$id?' selected':''); ?>><?php echo e($r['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </form>
            <!-- Chỉnh sửa phân loại phòng -->
            <form class="row g-3 align-items-center mb-4 justify-content-center" method="post" action="">
                <div class="col-auto">
                    <label class="form-label fw-bold">Phân loại phòng:</label>
                </div>
                <div class="col-auto">
                    <select name="room_type" class="form-select">
                        <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>"<?php echo e($room['type']==$type?' selected':''); ?>><?php echo e($type); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" type="submit" name="update_room_type">Cập nhật</button>
                </div>
            </form>
            <!-- Sơ đồ ghế -->
            <div class="seat-map-demo mb-3 text-center">
                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $code = $row . str_pad($col,2,'0',STR_PAD_LEFT);
                            $seat = $room['seats'][$code];
                            $class = 'seat-demo seat-label ';
                            if($seat['status']=='booked') $class .= 'seat-booked';
                            elseif($seat['status']=='selected') $class .= 'seat-selected';
                            elseif($seat['status']=='locked') $class .= 'seat-locked';
                            else $class .= 'seat-' . $seat['type'];
                        ?>
                        <span class="<?php echo e($class); ?>" data-seat="<?php echo e($code); ?>" data-type="<?php echo e($seat['type']); ?>" data-status="<?php echo e($seat['status']); ?>"><?php echo e($code); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <br>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="mb-3 text-center">
                <button class="btn btn-warning btn-sm" id="btn-vip">Đổi sang VIP</button>
                <button class="btn btn-info btn-sm" id="btn-normal">Đổi sang Thường</button>
                <button class="btn btn-purple btn-sm" id="btn-luxury" style="background:#8e24aa;color:#fff;">Đổi sang LUXURY</button>
                <button class="btn btn-secondary btn-sm" id="btn-lock">Khóa ghế</button>
                <button class="btn btn-success btn-sm" id="btn-unlock">Mở khóa ghế</button>
            </div>
            <div class="mt-4 text-center">
                <h5>Chú thích:</h5>
                <span class="seat-demo seat-normal seat-label">A01</span> Ghế thường
                <span class="seat-demo seat-vip seat-label ms-3">D01</span> Ghế VIP
                <span class="seat-demo seat-luxury seat-label ms-3">H01</span> LUXURY
                <span class="seat-demo seat-booked seat-label ms-3">F06</span> Ghế đã bán
                <span class="seat-demo seat-selected seat-label ms-3">G09</span> Ghế đang chọn
                <span class="seat-demo seat-locked seat-label ms-3">A03</span> Ghế khóa (hư)
            </div>
            <div class="mt-4 text-center">
                <h5>Thống kê</h5>
                <div id="stat"></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
    <script src="/static/js/admin/QuanLyPhongGhe.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Servers\test\app\Views/admin-views/QuanLyPhongGhe/QuanLyPhongGhe.blade.php ENDPATH**/ ?>