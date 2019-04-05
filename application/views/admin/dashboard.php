<?php $this->load->view('admin/inc/sidebar');?>
			<div class="section">
				<div class="row" style="padding:5px;">
					<div class="col-sm-3">
						<div class="prices-box" style="border: 1px solid #ffc107;">
							<div>
								<h5 class="font-weight-normal">Today</h5>
								<p><?= ngn($today); ?></p>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="prices-box" style="border: 1px solid #1c6169;">
							<div>
								<h5 class="font-weight-normal">This Week</h5>
								<p><?= ngn($week); ?></p>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="prices-box" style="border: 1px solid #1c6169;">
							<div>
								<h5 class="font-weight-normal">This Month</h5>
								<p><?= ngn($month); ?></p>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="prices-box" style="border: 1px solid #ffc107;">
							<div>
								<h5 class="font-weight-normal">This Year</h5>
								<p><?= ngn($year); ?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="padding:5px;">
					<div class="col-sm-3">
						<div class="prices-box" style="border: 1px solid #1c6169;">
							<div>
								<h5 class="font-weight-normal">Manage Services</h5>
								<p><?= anchor('admin/manage_services', 'Manage all services.'); ?></p>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="prices-box" style="border: 1px solid #1c6169;">
							<div>
								<h5 class="font-weight-normal">Manage Plans</h5>
								<p>Manage all service plans.</p>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="prices-box" style="border: 1px solid #1c6169;">
							<div>
								<h5 class="font-weight-normal">Manage Users</h5>
								<p><?= anchor('admin/manage_users/', 'Manage all system user.'); ?>.</p>
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="prices-box" style="border: 1px solid #1c6169;">
							<div>
								<h5 class="font-weight-normal">Wallet Fundings</h5>
								<p>Authorise wallet funding.</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="section" style="padding-top:unset;">
				<div class="container table-responsive">
                    <table class="table table-striped" id="table">
                        <thead>
                        <tr>
                            <th style="display: none"><?= $transaction->id; ?></th>
                            <th>Transaction ID</th>
                            <th>User detail</th>
                            <th>Date & Time</th>
                            <th>Payment</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach( $transactions as $transaction ): ?>
                            <tr>
                                <td style="display: none;"><?= $transaction->id; ?></td>
                                <td><?= $transaction->trans_id; ?></td>
                                <td><?= $transaction->name . '('.$transaction->phone.')'; ?></td>
                                <td><?= neatDate( $transaction->date_initiated) . ' ' . neatTime( $transaction->date_initiated); ?></td>
                                <td><?= paymentMethod($transaction->payment_method); ?></td>
                                <td><?= payment_id_replacer($transaction->description); ?></td>
                                <td><?= ngn($transaction->amount)?></td>
                                <td><?= statusLabel( $transaction->status);?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
				</div><!-- end container -->
			</div>
<?php $this->load->view('admin/inc/footer');?>