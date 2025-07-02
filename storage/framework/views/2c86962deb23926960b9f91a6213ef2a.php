
<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Imports</h1>
    <?php if(session('status')): ?>
        <div class="alert alert-success"><?php echo e(session('status')); ?></div>
    <?php endif; ?>
    <a href="<?php echo e(route('imports.create')); ?>" class="btn btn-primary mb-3">Upload Import</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>File</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $imports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $import): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($import->id); ?></td>
                <td><?php echo e($import->file_path); ?></td>
                <td><?php echo e($import->status); ?></td>
                <td><?php echo e($import->user->name ?? 'N/A'); ?></td>
                <td><?php echo e($import->created_at); ?></td>
                <td>
                    <a href="<?php echo e(route('imports.edit', $import)); ?>" class="btn btn-sm btn-warning">Edit</a>
                    <form action="<?php echo e(route('imports.destroy', $import)); ?>" method="POST" style="display:inline-block">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this import?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php echo e($imports->links()); ?>

</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\klevi\invoice-management-api\resources\views/imports/index.blade.php ENDPATH**/ ?>