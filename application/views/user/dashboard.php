<?php $this->load->view('user/inc/sidebar');?>
			<div class="section">
				<div class="row" style="padding:5px;">
					<div class="col-sm-4">
						<div class="prices-box" style="border: 1px solid #ffc107;">
							<div>
								<h5 class="font-weight-normal">Buy Data</h5>
								<p>Easy Data Recharge.</p>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="prices-box" style="border: 1px solid #1c6169;">
							<div>
								<h5 class="font-weight-normal">Buy Airtime</h5>
								<p>Quick Recharge.</p>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="prices-box" style="border: 1px solid #ffc107;">
							<div>
								<h5 class="font-weight-normal">Wallet</h5>
								<p><?= ngn($user->wallet); ?></p>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="prices-box" style="border: 1px solid #1c6169;">
							<div>
								<h5 class="font-weight-normal">TV Subscription</h5>
								<p>Pay for your TV Service provider online.</p>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="prices-box" style="border: 1px solid #1c6169;">
							<div>
								<h5 class="font-weight-normal">Buy Electric</h5>
								<p>Pay for Electric bills online.</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="section" style="padding-top:unset;">
				<div class="container table-responsive">
                    <h3>Your 10 most rescent Transactions</h3>
                    <table class="table table-striped table-bordered" id="table">
                        <thead>
                        <tr>
                            <th style="display: none"></th>
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
                                <td style="display:none;"><?= $transaction->id; ?></td>
                                <td><?= $transaction->trans_id; ?></td>
                                <td><?= neatDate( $transaction->date_initiated) . ' ' . neatTime( $transaction->date_initiated); ?></td>
                                <td><?= product_name($transaction->product_id); ?></td>
                                <td><?= payment_id_replacer($transaction->description); ?></td>
                                <td><?= ngn($transaction->amount)?></td>
                                <td><?= statusLabel( $transaction->status);?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
				</div><!-- end container -->
			</div>
<?php $this->load->view('user/inc/footer');?>