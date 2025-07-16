<!DOCTYPE html>
<html class="scroll-smooth" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="robots" content="index, follow">

    <?php if(request()->route()->getName() == 'home'): ?>
        <meta name="description"
            content="Jelajahi berbagai pilihan kamar yang sesuai dengan kebutuhan Anda. Dari liburan keluarga hingga
                perjalanan bisnis, kami punya yang Anda cari.">
    <?php else: ?>
        <meta name="description" content=" <?php echo $__env->yieldContent('description'); ?> ">
    <?php endif; ?>



    
    <meta property="og:title" content="<?php echo $__env->yieldContent('title'); ?>">
    <?php if(request()->route()->getName() == 'home'): ?>
        <title>Home - Booking Hotel
        </title>
        <meta property="og:image" content="<?php echo e(asset('img/tumbnail.PNG')); ?>">
        <meta property="og:description"
            content="Jelajahi berbagai pilihan kamar yang sesuai dengan kebutuhan Anda. Dari liburan keluarga hingga
                perjalanan bisnis, kami punya yang Anda cari.">
    <?php else: ?>
        <title><?php echo $__env->yieldContent('title'); ?> - Booking Hotel
        </title>
        <meta property="og:image" content="<?php echo e(asset('img/' . basename(request()->path()) . '.PNG')); ?>">
        <meta property="og:description" content=" <?php echo $__env->yieldContent('description'); ?>">
    <?php endif; ?>
    <meta property="og:url" content="<?php echo e(request()->url()); ?>">


    <!-- Fonts -->
    <link rel="shortcut icon" href="<?php echo e(asset('img/favicon.ico')); ?>" type="image/x-icon">
    <link rel="canonical" href="https://booking.com/" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/web3@1.6.0/dist/web3.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="<?php echo e(asset('css/markdown.css')); ?>">
    <style>
        .note-editable {
            background-color: white !important;
            color: black !important;
            min-height: 240px;
        }

        .note-editable ul {
            list-style: disc !important;
            list-style-position: inside !important;
        }

        .note-editable ol {
            list-style: decimal !important;
            list-style-position: inside !important;
        }



        @keyframes rotBGimg {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }


        #header {
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        #nav-content {
            transition: padding 0.3s ease, margin 0.3s ease;
        }
    </style>
</head>

<body class="antialiase scroll-smooth font-sans">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">


        <?php echo $__env->make('layouts.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


        <div class="bg-white py-1 pt-16 text-center text-white dark:bg-gray-800">
            <p id="backNav" class="text-left text-xs"> </p>
        </div>
        <!-- Page Heading -->
        <?php if(isset($header)): ?>
            <header class="bg-white shadow dark:bg-gray-800">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?>


        <!-- Page Content -->
        <main>
            <?php echo e($slot); ?>

        </main>
    </div>

    <!-- Tombol Scroll to Top -->
    <div id="scroll-to-top"
        class="fixed bottom-4 right-4 flex hidden h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-orange-500 p-2 text-white">
        <i class="fas fa-arrow-up"></i>
    </div>

    <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script>
        feather.replace();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to close the alert
            function closeAlert() {

                const alertElement = document.getElementById('alert');
                alertElement.classList.add('hidden');

                var backNav = document.getElementById("backNav");
                backNav.classList.remove("mt-20");
                backNav.classList.add("mt-14");
            }

            // Attach event listener to the close button if it exists
            const closeBtn = document.getElementById('closeBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', closeAlert);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var mybutton = document.getElementById('scroll-to-top');

            // Tampilkan tombol ketika menggulir lebih dari 100px dari bagian atas halaman
            window.onscroll = function() {
                if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                    mybutton.classList.remove('hidden');
                } else {
                    mybutton.classList.add('hidden');
                }
            };

            // Fungsi untuk menangani klik pada tombol scroll to top
            mybutton.addEventListener('click', function() {
                // Menggunakan scrollIntoView dengan efek smooth
                document.body.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>

    

</body>

</html>
<?php /**PATH C:\laravel10\booking-hotel-main\resources\views/layouts/app.blade.php ENDPATH**/ ?>