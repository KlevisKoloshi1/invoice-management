<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="/">Invoice Management</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('imports.index')); ?>">Imports</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('invoices.index')); ?>">Invoices</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('imports.public')); ?>">Public Imports</a></li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if(Auth::check()): ?>
                    <li class="nav-item"><span class="nav-link"><?php echo e(Auth::user()->name); ?></span></li>
                    <li class="nav-item">
                        <form action="/logout" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-link nav-link" type="submit">Logout</button>
                        </form>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main>
    <div class="container">
        <?php if($errors->has('auth')): ?>
            <div class="alert alert-danger"><?php echo e($errors->first('auth')); ?></div>
        <?php endif; ?>
    </div>
    <?php echo $__env->yieldContent('content'); ?>
</main>
</body>
</html> <?php /**PATH C:\Users\klevi\invoice-management-api\resources\views/layouts/app.blade.php ENDPATH**/ ?>