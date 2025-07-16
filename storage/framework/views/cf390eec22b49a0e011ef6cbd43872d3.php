<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title', $title); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            <?php echo e(__('Daftar Pesanan Anda')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="mb-6 text-2xl font-bold"><?php echo e(__('Pesanan Saya')); ?></h3>

                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        
                        <div
                            class="<?php echo e($booking->status->cardBgColor()); ?> <?php echo e($booking->status->cardBorderColor()); ?> mb-6 rounded-lg border p-4 shadow-sm">
                            <div class="flex flex-col items-start justify-between md:flex-row md:items-center">
                                <div class="mb-4 md:mb-0">
                                    
                                    <h4 class="mb-1 text-xl font-semibold">
                                        <?php echo e($booking->room ? $booking->room->name : 'Tipe Kamar Tidak Dikenal'); ?>

                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        Check-in: <span
                                            class="font-medium"><?php echo e(\Carbon\Carbon::parse($booking->check_in_date)->format('d M Y')); ?></span>
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        Check-out: <span
                                            class="font-medium"><?php echo e(\Carbon\Carbon::parse($booking->check_out_date)->format('d M Y')); ?></span>
                                    </p>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                        Total Harga: <span
                                            class="text-lg font-bold text-indigo-600 dark:text-indigo-400">Rp.
                                            <?php echo e(number_format($booking->total_price, 0, ',', '.')); ?></span>
                                    </p>
                                </div>

                                <div class="flex flex-shrink-0 flex-col items-end">
                                    
                                    <span
                                        class="<?php echo e($booking->status->color()); ?> mb-2 rounded-full px-3 py-1 text-sm font-semibold">
                                        
                                        <?php echo e($booking->status->label()); ?>

                                    </span>

                                    
                                    <?php if($booking->status === \App\Enums\BookingStatus::PENDING): ?>
                                        <a href="<?php echo e(route('payment.show', $booking->booking_code)); ?>"
                                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring active:bg-indigo-900">
                                            <?php echo e(__('Lanjutkan ke Pembayaran')); ?>

                                        </a>
                                    <?php elseif(
                                        $booking->status === \App\Enums\BookingStatus::CONFIRMED ||
                                            $booking->status === \App\Enums\BookingStatus::CHECKED_IN ||
                                            $booking->status === \App\Enums\BookingStatus::CHECKED_OUT): ?>
                                        <a href="<?php echo e(route('booking.receipt', $booking->booking_code)); ?>"
                                            class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-green-700 focus:outline-none focus:ring active:bg-green-900">
                                            <?php echo e(__('Lihat Tanda Terima')); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-4 text-center text-gray-600 dark:text-gray-400">
                            <p><?php echo e(__('Anda belum memiliki pesanan saat ini.')); ?></p>
                            <p class="mt-2">
                                <a href="<?php echo e(route('home')); ?>"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                    <?php echo e(__('Cari kamar untuk memulai!')); ?>

                                </a>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laravel10\booking-hotel-main\resources\views/dashboard/pesanan/index.blade.php ENDPATH**/ ?>