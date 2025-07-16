

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
    <?php $__env->startSection('title', $title ?? 'Manajemen Tipe Kamar'); ?> 

    
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            
            <a href="<?php echo e(route('rooms.create')); ?>"
                class="rounded-md bg-orange-600 px-5 py-2 text-white hover:bg-orange-500">
                <?php echo e(__('Tambah Tipe Kamar Baru')); ?>

            </a>
        </h2>
     <?php $__env->endSlot(); ?>

    
    <?php if(session()->has('success')): ?>
        <div id="alert" class="relative m-4 flex justify-between rounded bg-green-500 p-4 text-white shadow-lg">
            <p><?php echo e(session('success')); ?></p>
            <button type="button" id="closeBtn" class="close transition" data-dismiss="alert">
                <i class="text-white" data-feather="x"></i>
            </button>
        </div>
    <?php endif; ?>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="w-full overflow-hidden bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800">
                <div class="relative max-w-full overflow-x-auto"> 
                    <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Nama Tipe Kamar</th>
                                <th scope="col" class="px-6 py-3">Harga per Malam</th>
                                <th scope="col" class="px-6 py-3">Kapasitas</th>
                                <th scope="col" class="px-6 py-3">Total Kamar</th>
                                <th scope="col" class="px-6 py-3">Tersedia</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                
                                <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                                    <th scope="row"
                                        class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        <?php echo e($loop->iteration); ?>

                                    </th>
                                    <td class="px-6 py-4">
                                        
                                        <a class="hover:underline hover:underline-offset-1"
                                            href="<?php echo e(route('rooms.show', $room->slug)); ?>">
                                            <?php echo e($room->name); ?>

                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        Rp. <?php echo e(number_format($room->price_per_night, 0, ',', '.')); ?>

                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo e($room->capacity); ?>

                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo e($room->total_rooms); ?>

                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo e($room->available_rooms); ?>

                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-start space-x-2 text-white"> 
                                            
                                            <a href="<?php echo e(route('rooms.edit', ['room' => $room->slug])); ?>"
                                                class="bg-warning-600 group relative inline-block h-8 w-12 rounded-md bg-yellow-500 px-3 py-1 hover:bg-yellow-600">
                                                
                                                <i data-feather="edit"></i>
                                                <span
                                                    class="absolute left-7 top-8 z-50 border border-black bg-slate-100 px-0.5 py-0.5 text-xs text-black opacity-0 transition duration-300 group-hover:opacity-100">Edit</span>
                                            </a>
                                            
                                            <form action="<?php echo e(route('rooms.destroy', $room->slug)); ?>" method="post"
                                                class="inline-block"> 
                                                <?php echo method_field('delete'); ?>
                                                <?php echo csrf_field(); ?>
                                                <button type="submit"
                                                    class="bg-danger-600 group relative h-8 w-12 rounded-md bg-red-600 px-3 py-1 hover:bg-red-700"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus tipe kamar ini: <?php echo e($room->name); ?>?')">
                                                    <i data-feather="trash-2"></i> 
                                                    <span
                                                        class="absolute left-7 top-8 z-50 border border-black bg-slate-100 px-0.5 py-0.5 text-xs text-black opacity-0 transition duration-300 group-hover:opacity-100">Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                        Belum ada tipe kamar yang ditambahkan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <?php echo e($rooms->links()); ?>

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


<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace(); // Inisialisasi ikon Feather

            // Logic untuk menutup alert
            const alertElement = document.getElementById('alert');
            const closeBtn = document.getElementById('closeBtn');

            if (closeBtn && alertElement) {
                closeBtn.addEventListener('click', function() {
                    alertElement.classList.add('hidden'); // Sembunyikan alert
                });
                // Opsional: sembunyikan alert setelah beberapa detik
                setTimeout(() => {
                    alertElement.classList.add('hidden');
                }, 5000); // Sembunyikan setelah 5 detik
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laravel10\booking-hotel-main\resources\views/dashboard/rooms/index.blade.php ENDPATH**/ ?>