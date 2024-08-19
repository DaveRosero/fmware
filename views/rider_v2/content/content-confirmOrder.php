<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-0">Payment</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Placeholder for Order Ref -->
                            <h1 id="order-ref">Order Ref: N/A</h1>

                            <!-- Order details -->
                            <div class="d-flex flex-column text-end">
                                <!-- Placeholders for Order Price, Discount, and Delivery Fee -->
                                <h3 id="order-price">Order Price:</h3>
                                <h3 id="order-delivery-fee">Delivery Fee:</h3>
                                <h3 id="order-discount">Discount:</h3>
                                <h3 id="total-price">Order Price:</h3>

                            </div>

                            <!-- Payment Method -->
                            <div class="d-flex flex-column">
                                <h1>Payment Method</h1>
                                <div class="d-flex">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="Cash" value="3" checked>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Cash
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="Gcash" value="2" >
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Gcash
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Input for Cash Received Info -->
                            <div>
                                <input class="form-control" type="number" placeholder="Cash Received" aria-label="default input example">
                                <div class="d-flex flex-column text-end">
                                    <h3 id="order-change">Change: N/A</h3>
                                </div>
                            </div>

                    
                            <div>
                                <button type="button" class="btn btn-primary" id="delivered-btn" disabled>Delivered</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>