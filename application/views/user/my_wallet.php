<?php $this->load->view('user/inc/sidebar');?>
	<div style="min-height:100vh;">
    <div class="section" style="padding-left:10px;padding-right:10px;">
    <div class="row">
        <div class="col-12 col-md-12">
            <ul class="nav tabs margin-bottom-20 text-center" style="color:#fff;">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#fund_wallet">Fund Wallet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#fund_transfer">Fund Transfer</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="fund_wallet">
                    <div class="alert-secondary" style="padding:10px;font-size:12px;">
                        <h4 class="h5">Please Note:</h4>
                        <ul>
                            <li>
                                A transaction ID will be generated for you, which should be used as reference.
                            </li>
                            <li>
                                If you will be paying via Bank Transfer / Deposit, account details will be shown.
                            </li>
                        </ul>
                    </div>
                    <div class="">
                        <form>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label" for="Amount">Amount</label>
                                        <input type="text" autocomplete="off" class="form-control number" name="amount" id="pay_amount" required placeholder="Enter Amount">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label" for="Payment method">Payment Method</label>
                                        <select class="form-control custom-select w-100 custom-select-lg" name="payment_method" id="payment_method" required>
                                            <option value=""> -- How will you like to pay? --</option>
                                            <option value="1">Bank Transfer / Deposit</option>
                                            <option value="3">Pay Online Via Paystack (With Card) - ( 1.5% fee )</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group" style="display: none;" id="bank_col">
                                        <label class="label" for="Bank">Bank</label>
                                        <select class="form-control custom-select w-100 custom-select-lg" name="bank" id="bank" required>
                                            <option value=""> -- Select the bank you're paying to --</option>
                                            <?php
                                            $banks = explode('|', lang('company_banks'));
                                            foreach( $banks as $bank ):
                                                ?>
                                                <option value="<?= trim( $bank ); ?>"> <?= trim($bank); ?> </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <input type="hidden" name="product_id" id="product_id" value="6" />
                            <input type="hidden" name="post_type" value="wallet_funding" />
                            <button class="btn btn-outline-danger btn-sm col-sm-4 pay-now">Pay Now</button>
                            <button type="reset" class="btn btn-default btn-sm col-sm-3">Clear</button>&nbsp;&nbsp;
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="fund_transfer">
                    <div class="">
                        <h4 class="text-danger"><b>Your current wallet balance - <?= ngn($user->wallet);?></b></h4>
                        <form method="POST" action="<?= base_url('dashboard/')?>">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label" for="Amount">Amount</label>
                                        <input type="text" class="form-control number" name="amount" id="transfer_amount" required placeholder="Enter Amount you want to send">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label" for="Payment method">Receiver Username</label>
                                        <input type="text" name="receiver" id="receiver" class="form-control" required placeholder="Receiver phone number">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="product_id" id="transfer_product_id" value="7" />
                            <button class="btn btn-outline-danger btn-sm col-sm-4 transfer-now" data-balance="<?= $user->wallet;?>">Transfer Now</button>
                            <button type="reset" class="btn btn-default btn-sm col-sm-3">Clear Form</button>&nbsp;&nbsp;
                        </form>
					</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 table-responsive">
            <h4>10 latest transactions.</h4><hr />
            <div style="margin-top: 20px" class="table-responsive">
                <table class="table table-striped" id="table">
                    <thead>
                    <tr>
                        <th style="display: none;"></th>
                        <th>Transaction ID</th>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach( $transactions as $transaction ): ?>
                        <tr>
                            <td style="display: none"><?= $transaction->id; ?></td>
                            <td>
                                <?= $transaction->trans_id; ?>
                                <?php if( $transaction->payment_method == 1 && $transaction->status == 'pending') : ?>
                                    <span><a href="<?= base_url('dashboard/payment_made/?tid=' . $transaction->trans_id)?>">Confirm Payment</a></span>
                                <?php endif;?>
                            </td>
                            <td><?= neatDate( $transaction->date_initiated) . ' ' . neatTime( $transaction->date_initiated); ?></td>
                            <td><?= product_name($transaction->product_id); ?></td>
                            <td><?= payment_id_replacer($transaction->description); ?></td>
                            <td><?= ngn($transaction->amount)?></td>
                            <td><?= statusLabel( $transaction->status);?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>		
<?php $this->load->view('user/inc/footer');?>