<?php $this->load->view('admin/inc/sidebar');?>
	<div style="min-height:100vh;">
        <div class="section" style="padding-left:10px;padding-right:10px;">
            <div class="row">
                <div class="col-12 col-md-12 table-responsive">
                    <table class="table table-bordered js-table" width="100%">
                      <thead class="thead-dark">
                        <tr>
                            <th scope="col" style="display: none;"></th>
                            <th scope="col">Transaction ID</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Phone/Email</th>
                            <th scope="col">Date</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Description</th>
                            <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php foreach( $fundings as $funding ): ?>

                              <tr>
                                  <td style="display: none;"><?= $funding->id; ?></td>
                                  <td>
                                      <?= $funding->trans_id; ?>
                                      <?php
                                      $check = $this->site->run_sql("SELECT id FROM transaction_status WHERE tid = {$funding->trans_id}")->num_rows();
                                      if( $check > 0 ) : ?>
                                          <span><a href="<?= base_url('admin/confirm_payment/?tid='. $funding->trans_id);?>">Confirm Payment</a></span>
                                      <?php else : ?>
                                          - User has not uploaded proof
                                      <?php endif;?>
                                  </td>
                                  <td><?= (!is_null($funding->name)) ? ucwords($funding->name) : 'Not Set'; ?></td>
                                  <td><?= $funding->phone . ' / ' . $funding->email; ?></td>
                                  <td><?= neatDate( $funding->date_initiated) . ' '. neatTime($funding->date_initiated); ?></td>
                                  <td><?= ngn($funding->amount)?></td>
                                  <td><?= payment_id_replacer($funding->description); ?></td>
                                  <td>
                                      <form class="form-inline" method="post" action="<?= base_url('admin/approval')?>" id="<?= $funding->id?>">
                                          <div class="form-group mx-sm-3 mb-2">
                                              <label for="action" class="sr-only">Action</label>
                                              <select class="form-control-sm" name="action" required>
                                                  <option value=""> -- Select action --</option>
                                                  <option value="approved"> Approve </option>
                                                  <option value="declined"> Decline </option>
                                              </select>
                                              <input type="hidden" name="txn_id" value="<?= $funding->id; ?>" />
                                              <input type="hidden" name="user_id" value="<?= $funding->user_id; ?>" />
                                              <input type="hidden" name="amount" value="<?= $funding->amount; ?>" />
                                          </div>
                                          <button type="submit" class="btn btn-sm btn-outline-success mb-2">Submit</button>
                                      </form>
                                  </td>
                              </tr>
                          <?php endforeach; ?>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>		
<?php $this->load->view('admin/inc/footer');?>