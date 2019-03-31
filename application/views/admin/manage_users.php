<?php $this->load->view('admin/inc/sidebar');?>
	<div style="min-height:100vh;">
        <div class="section" style="padding-left:10px;padding-right:10px;">
            <?php $this->load->view('msg_view'); ?>
            <div class="row">
                <div class="col-12 col-md-12 table-responsive">
                    <table class="table table-bordered js-table" width="100%">
                          <thead class="thead-dark">
                            <tr>
                              <th scope="col" style="display: none;">ID</th>
                              <th scope="col">ID</th>
                              <th scope="col">Full Name</th>
                              <th scope="col">Wallet</th>
                              <th scope="col">Email/Phone</th>
                              <th scope="col">Last Login</th>
                              <th scope="col">Status</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                        <tbody>
                        <?php foreach( $users as $user ): ?>
                            <tr>
                                <td style="display: none"><?= $user->id; ?></td>
                                <td><?= $user->user_code; ?></td>
                                <td><?= $user->name; ?></td>
                                <td><?= ngn($user->wallet); ?></td>
                                <td><?= $user->email . ' ' . $user->phone; ?></td>
                                <td><?= neatDate($user->last_login) . ' ' . neatTime($user->last_login); ?></td>
                                <td><?= $user->status; ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-info" href="<?= base_url('admin/user_action/active/' . $user->id); ?>">Unblock</a>
                                        <a class="btn btn-warning" href="<?= base_url('admin/user_action/delete/' . $user->id); ?>">Delete</a>
                                        <a class="btn btn-danger" href="<?= base_url('admin/user_action/block/' . $user->id); ?>">Block</a>
                                    </div>
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