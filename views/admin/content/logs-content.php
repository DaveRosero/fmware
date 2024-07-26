<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <h5 class="card-title mb-9 fw-semibold">Activity Logs</h5>
                </div>
                <div class="row">
                    <table class="table table-borderless" id="logs-table">
                        <thead>
                            <th class="text-center">Description</th>
                            <th class="text-center">Author</th>
                            <th class="text-center">Date & Time</th>
                        </thead>
                        <tbody>
                            <?php $logs->showLogs(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>