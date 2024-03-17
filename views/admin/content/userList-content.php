<div class="container-fluid">
    <!--User List Start-->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">User List</h5>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Sex</th>
                            <th>Group</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $userList->getUserList(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--User List End-->
</div>

