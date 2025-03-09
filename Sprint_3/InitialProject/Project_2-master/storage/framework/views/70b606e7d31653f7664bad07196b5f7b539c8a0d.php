
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<?php $__env->startSection('title','Project'); ?>

<?php $__env->startSection('content'); ?>

<div class="container">

    <?php if($message = Session::get('success')): ?>
    <div class="alert alert-success">
        <p><?php echo e($message); ?></p>
    </div>
    <?php endif; ?>
    <div class="card" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">โครงการวิจัย</h4>
            <a class="btn btn-primary btn-menu btn-icon-text btn-sm mb-3" href="<?php echo e(route('researchProjects.create')); ?>"><i class="mdi mdi-plus btn-icon-prepend"></i> ADD</a>
            <!-- <div class="table-responsive"> -->
                <table id="example1" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Year</th>
                            <th>Project name</th>
                            <th>Head</th>
                            <th>Member</th>
                            <th width="auto">Action</th>
                        </tr>
                        <thead>
                        <tbody>
                            <?php $__currentLoopData = $researchProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$researchProject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($i+1); ?></td>
                                <td><?php echo e($researchProject->project_year); ?></td>
                                
                                <td><?php echo e(Str::limit($researchProject->project_name,70)); ?></td>
                                <td>
                                    <?php $__currentLoopData = $researchProject->user; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if( $user->pivot->role == 1): ?>
                                    <?php echo e($user->fname_en); ?>

                                    <?php endif; ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td>
                                    <?php $__currentLoopData = $researchProject->user; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if( $user->pivot->role == 2): ?>
                                    <?php echo e($user->fname_en); ?>

                                    <?php endif; ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td>
                                    <form action="<?php echo e(route('researchProjects.destroy',$researchProject->id)); ?>"method="POST">
                                    <li class="list-inline-item">
                                    <a class="btn btn-outline-primary btn-sm" type="button" data-toggle="tooltip"
                                            data-placement="top" title="view"
                                            href="<?php echo e(route('researchProjects.show',$researchProject->id)); ?>"><i
                                                class="mdi mdi-eye"></i></a>
                                    </li>
                                        <!-- <?php if(Auth::user()->can('update',$researchProject)): ?>
                                <a class="btn btn-primary"
                                    href="<?php echo e(route('researchProjects.edit',$researchProject->id)); ?>">Edit</a>
                                <?php endif; ?> -->
                               
                                        <?php if(Auth::user()->can('update',$researchProject)): ?> 
                                        <li class="list-inline-item">
                                        <a class="btn btn-outline-success btn-sm" type="button" data-toggle="tooltip"
                                            data-placement="top" title="Edit"
                                            href="<?php echo e(route('researchProjects.edit',$researchProject->id)); ?>"><i
                                                class="mdi mdi-pencil"></i></a>
                                             </li>
                                        <?php endif; ?>
                               
                                        <?php if(Auth::user()->can('delete',$researchProject)): ?>
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <li class="list-inline-item">
                                            <button class="btn btn-outline-danger btn-sm show_confirm" type="submit"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="mdi mdi-delete"></i></button>
                                        </li>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tbody>
                        
                </table>
            <!-- </div> -->
            <br>
            
        </div>
    </div>
    

</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src = "https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap4.min.js" defer ></script>
<script src = "https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js" defer ></script>
<script>
    $(document).ready(function() {
        var table1 = $('#example1').DataTable({
            responsive: true,
        });
    });
</script>
<script type="text/javascript">
    $('.show_confirm').click(function(event) {
        var form = $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        swal({
                title: `Are you sure?`,
                text: "If you delete this, it will be gone forever.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    swal("Delete Successfully", {
                        icon: "success",
                    }).then(function() {
                        location.reload();
                        form.submit();
                    });
                }
            });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboards.users.layouts.user-dash-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8-3-68\git-group-repository-group-5-sec-1\Sprint_3\InitialProject\Project_2-master\resources\views/research_projects/index.blade.php ENDPATH**/ ?>