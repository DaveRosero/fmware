<div class="container-fluid">
    <!--User List Start-->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">User List</h5>
            <div class="table-container">
                <table class="table table-borderless" id="user-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Date Created</th>
                            <th>Last Login</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $user->getUserList(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--User List End-->
</div>

