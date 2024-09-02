<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="new-unit" method="POST">
                <div class="modal-body">
                    <div class="table-responsive">
                        <p class="text-start" id="customer"></p>
                        <table class="table table-borderless">
                            <thead>
                                <th class="text-center">Image</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Condition</th>
                            </thead>
                            <tbody id="view-content">

                            </tbody>
                        </table>
                        <p class="text-end" id="staff"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>