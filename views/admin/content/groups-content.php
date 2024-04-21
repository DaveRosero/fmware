<div class="container-fluid">
    <!--Create Group Start-->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Create Group</h5>
            <form action="/groups" method="POST">
                <div class="row">
                    <div class="col">
                        <label for="group_name" class="form-label">Group Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="group_name" name="group_name" aria-describedby="group_name" required>
                    </div>
                </div>
                <div class="table-container overflow-auto" style="max-height: 200px;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Modules</th>
                                <th>View</th>
                                <th>Add</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Groups</td>
                                <td><input type="checkbox" name="permissions[]" value="viewGroup"></td>
                                <td><input type="checkbox" name="permissions[]" value="addGroup"></td>
                                <td><input type="checkbox" name="permissions[]" value="editGroup"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteGroup"></td>
                            </tr>
                            <tr>
                                <td>Users</td>
                                <td><input type="checkbox" name="permissions[]" value="viewUser"></td>
                                <td><input type="checkbox" name="permissions[]" value="addUser"></td>
                                <td><input type="checkbox" name="permissions[]" value="editUser"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteUser"></td>
                            </tr>
                            <tr>
                                <td>Staff</td>
                                <td><input type="checkbox" name="permissions[]" value="viewStaff"></td>
                                <td><input type="checkbox" name="permissions[]" value="addStaff"></td>
                                <td><input type="checkbox" name="permissions[]" value="editStaff"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteStaff"></td>
                            </tr>
                            <tr>
                                <td>Products</td>
                                <td><input type="checkbox" name="permissions[]" value="viewProducts"></td>
                                <td><input type="checkbox" name="permissions[]" value="addProducts"></td>
                                <td><input type="checkbox" name="permissions[]" value="editProducts"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteProducts"></td>
                            </tr>
                            <tr>
                                <td>Stocks</td>
                                <td><input type="checkbox" name="permissions[]" value="viewStocks"></td>
                                <td><input type="checkbox" name="permissions[]" value="addStocks"></td>
                                <td><input type="checkbox" name="permissions[]" value="editStocks"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteStocks"></td>
                            </tr>
                            <tr>
                                <td>Restocks</td>
                                <td><input type="checkbox" name="permissions[]" value="viewRestocks"></td>
                                <td><input type="checkbox" name="permissions[]" value="addRestocks"></td>
                                <td><input type="checkbox" name="permissions[]" value="editRestocks"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteRestocks"></td>
                            </tr>
                            <tr>
                                <td>Returns</td>
                                <td><input type="checkbox" name="permissions[]" value="viewReturns"></td>
                                <td><input type="checkbox" name="permissions[]" value="addReturns"></td>
                                <td><input type="checkbox" name="permissions[]" value="editReturns"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteReturns"></td>
                            </tr>
                            <tr>
                                <td>Replacements</td>
                                <td><input type="checkbox" name="permissions[]" value="viewReplacements"></td>
                                <td><input type="checkbox" name="permissions[]" value="addReplacements"></td>
                                <td><input type="checkbox" name="permissions[]" value="editReplacements"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteReplacements"></td>
                            </tr>
                            <tr>
                                <td>Suppliers</td>
                                <td><input type="checkbox" name="permissions[]" value="viewSuppliers"></td>
                                <td><input type="checkbox" name="permissions[]" value="addSuppliers"></td>
                                <td><input type="checkbox" name="permissions[]" value="editSuppliers"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteSuppliers"></td>
                            </tr>
                            <tr>
                                <td>Sales</td>
                                <td><input type="checkbox" name="permissions[]" value="viewSales"></td>
                                <td><input type="checkbox" name="permissions[]" value="addSales"></td>
                                <td><input type="checkbox" name="permissions[]" value="editSales"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteSales"></td>
                            </tr>
                            <tr>
                                <td>Orders</td>
                                <td><input type="checkbox" name="permissions[]" value="viewOrders"></td>
                                <td><input type="checkbox" name="permissions[]" value="addOrders"></td>
                                <td><input type="checkbox" name="permissions[]" value="editOrders"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteOrders"></td>
                            </tr>
                            <tr>
                                <td>Attributes</td>
                                <td><input type="checkbox" name="permissions[]" value="viewAttributes"></td>
                                <td><input type="checkbox" name="permissions[]" value="addAttributes"></td>
                                <td><input type="checkbox" name="permissions[]" value="editAttributes"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteAttributes"></td>
                            </tr>
                            <tr>
                                <td>Archive</td>
                                <td><input type="checkbox" name="permissions[]" value="viewArchive"></td>
                                <td><input type="checkbox" name="permissions[]" value="addArchive"></td>
                                <td><input type="checkbox" name="permissions[]" value="editArchive"></td>
                                <td><input type="checkbox" name="permissions[]" value="deleteArchive"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="action" value="create_group">    
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
    <!--Create Group End-->


    <!--Group Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Group List</h5>
                </div>
                <div class="col text-end">
                    <a href="#"><i class="fa-solid fa-print fs-5"></i></a>
                </div>
            </div>
            
            <form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Permissions</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $group->getGroups(); ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <!--Group Table End-->
</div>

