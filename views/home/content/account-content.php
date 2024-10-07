<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <h1 class="h2 pb-4">My Account</h1>
            <ul class="list-unstyled templatemo-accordion">
                <!-- <li class="pb-3">
                    <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle profile-icon"></i>
                            <span class="px-2">Profile</span>
                        </div>
                        <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
                    </a>
                    <ul class="collapse show list-unstyled pl-3">
                        <li><a class="text-decoration-none" href="/account/settings">Settings</a></li>
                        <li><a class="text-decoration-none" href="#">Addresses</a></li>
                    </ul>
                </li> -->
                <li class="pb-3">
                    <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="px-2">My Purchases (<?php $transaction->getOrderCount($user_info['id']); ?>)</span>
                        </div>
                        <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
                    </a>
                    <ul class="collapse show list-unstyled pl-3">
                        <li><a class="text-decoration-none" href="/my-purchases/to-pay">To Pay <?php echo $transaction->getToPayCount($user_info['id']); ?></a></li>
                        <li><a class="text-decoration-none" href="/my-purchases/pending">Pending <?php echo $transaction->getPendingCount($user_info['id']); ?></a></li>
                        <li><a class="text-decoration-none" href="/my-purchases/to-receive">To Receive <?php echo $transaction->getToReceiveCount($user_info['id']); ?></a></li>
                        <li><a class="text-decoration-none" href="/my-purchases/delivered">Delivered <?php echo $transaction->getDeliveredCount($user_info['id']); ?></a></li>
                        <li><a class="text-decoration-none" href="/my-purchases/completed">Completed <?php echo $transaction->getCompletedCount($user_info['id']); ?></a></li>
                        <li><a class="text-decoration-none" href="/my-purchases/others">Cancellations & Returns <?php echo $transaction->getCancelledCount($user_info['id']); ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>