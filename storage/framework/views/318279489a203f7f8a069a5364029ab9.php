<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <title><?php echo $__env->yieldContent('title'); ?> - Booking Hotel
    </title>

    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content=" <?php echo $__env->yieldContent('description'); ?> ">
    <meta name="robots" content="index, follow">


    
    <meta property="og:title" content="<?php echo $__env->yieldContent('title'); ?>">
    <meta property="og:description" content=" <?php echo $__env->yieldContent('description'); ?>">
    <meta property="og:image" content="<?php echo e(asset('img/' . basename(request()->path()) . '.PNG')); ?>">
    <meta property="og:url" content="<?php echo e(request()->url()); ?>">


    
    <link rel="shortcut icon" href="<?php echo e(asset('img/favicon.ico')); ?>" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <?php if(request()->route()->getName() == 'home'): ?>
        <link rel="canonical" href="<?php echo e(url()->current()); ?>">
    <?php endif; ?>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-L33K6VP4EV"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-L33K6VP4EV');
    </script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900">
        <div>
            <a href="/">
                <img src="<?php echo e(asset('img/logo.jpg')); ?>" class="h-20 w-20 fill-current text-gray-500" />
            </a>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg dark:bg-gray-800">
            <?php echo e($slot); ?>

        </div>
    </div>
    <script>
        feather.replace();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            const togglePassword = document.getElementById('togglePassword');

            togglePassword.addEventListener('change', function() {
                const passwordType = togglePassword.checked ? 'text' : 'password';
                passwordInput.type = passwordType;
                eyeOpen.classList.toggle('hidden');
                eyeClosed.classList.toggle('hidden');
            });
        });
    </script>
    <script>
        // Mendapatkan path URL tanpa karakter slash terakhir
        var pathWithoutSlash = window.location.pathname.replace(/\/$/, "");

        // Mendapatkan nama file dari path URL
        var fileName = pathWithoutSlash.substring(pathWithoutSlash.lastIndexOf("/") + 1);

        // Mengubah nama file menjadi huruf besar dan menambahkan ekstensi PNG
        var thumbnailFileName = fileName.toUpperCase() + ".PNG";
        var thumbnailPath = "<?php echo e(asset('img/')); ?>" + '/' + thumbnailFileName;

        // Mengatur nilai og:image dengan URL gambar thumbnail
        document.getElementById("ogImage").content = thumbnailPath;
    </script>
</body>

</html>
<?php /**PATH C:\laravel10\booking-hotel-main\resources\views/layouts/guest.blade.php ENDPATH**/ ?>