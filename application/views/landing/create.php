<?php $this->load->view('inc/header');?>
<div class="section-fullscreen bg-image" style="background-image: url(<?= base_url("assets/images/app-landing-parallax-2-bg.jpg");?>)">
			<div class="bg-black-04">
				<div class="container text-center">
					<div class="position-middle">
						<div class="row">
							<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
								<h4 class="font-weight-light margin-bottom-30">Registration</h4>
                                <?php $this->load->view('msg_view'); ?>
								<?= form_open('auth/create/')?>
									<input type="text" placeholder="Full name" autocapitalize="off" autocomplete="off" name="name" value="<?= set_value('name')?>" required>
									<input type="text" placeholder="Phone Number" name="phone" value="<?= set_value('phone')?>" required>
									<input type="text" placeholder="Email" name="email" value="<?= set_value('email')?>" required>
									<input type="password" placeholder="Password" name="password" required>
									<input type="password" placeholder="Confirm Password" name="confirm_password" required>
									<button class="button button-lg button-outline-white-2 button-fullwidth">Register</button>
								<?= form_close();?>
								<div class="margin-top-30">
									<p>Or <a href="<?= base_url('login');?>">Login</a> if you already have an account with us.</p>
								</div>
							</div>
						</div><!-- end row -->
					</div><!-- end position-middle -->
				</div><!-- end container -->
			</div>
		</div>
        <?php $this->load->view('inc/footer');?>