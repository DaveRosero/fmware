<div class="container-fluid">

    <!--Create Staff Start-->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">New Staff</h5>
            <form action="/fmware/staff" method="POST">
                <div class="row">
                    <div class="col">
                        <label for="group_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="group_name" name="fname" aria-describedby="group_name" required>
                    </div>
                    <div class="col">
                        <label for="group_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="group_name" name="lname" aria-describedby="group_name" required>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <label for="group_name" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="group_name" name="email" aria-describedby="group_name" required>
                    </div>
                    <div class="col">
                        <label for="group_name" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="group_name" name="password" aria-describedby="group_name" required>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <label for="group_name" class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="group_name" name="phone" aria-describedby="group_name" required>
                    </div>
                    <div class="col">
                        <label for="group_name" class="form-label">Sex <span class="text-danger">*</span></label>
                        <select class="form-select" id="category" name="sex">
                            <option value="0">Male</option>
                            <option value="1">Female</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="group_name" class="form-label">Group <span class="text-danger">*</span></label>
                        <select class="form-select" id="category" name="group">
                            <option select-disabled>Select a group</option>
                            <?php $group->displayGroups(); ?>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="action" value="new_staff">    
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
    <!--Create Staff End-->

    <!--Staff List Start-->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Staff List</h5>
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
                        <?php $userList->getStaffList(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--Staff List End-->
</div>

