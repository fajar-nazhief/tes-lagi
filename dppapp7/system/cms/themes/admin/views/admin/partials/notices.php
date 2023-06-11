<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger animated fadeIn">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true"><i class="fal fa-times-circle"></i></span>
                                                    </button>
	<p><?php echo $this->session->flashdata('error'); ?></p>
</div>
<?php endif; ?>

<?php if (validation_errors()): ?>
<div class="alert alert-danger animated fadeIn">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true"><i class="fal fa-times-circle"></i></span>
                                                    </button>
	<p><?php echo validation_errors(); ?></p>
</div>
<?php endif; ?>

<?php if ( ! empty($messages['error'])): ?>
<div class="alert alert-danger animated fadeIn">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true"><i class="fal fa-times-circle"></i></span>
                                                    </button>
	<p><?php echo $messages['error']; ?></p>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('notice')): ?>
<div class="alert alert-warning animated fadeIn">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true"><i class="fal fa-times-circle"></i></span>
                                                    </button>
	<p><?php echo $this->session->flashdata('notice');?></p>
</div>
<?php endif; ?>

<?php if ( ! empty($messages['notice'])): ?>
<div class="alert alert-warning animated fadeIn">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true"><i class="fal fa-times-circle"></i></span>
                                                    </button>
	<p><?php echo $messages['notice']; ?></p>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success animated fadeIn">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true"><i class="fal fa-times-circle"></i></span>
                                                    </button>
	<p><?php echo $this->session->flashdata('success'); ?></p>
</div>
<?php endif; ?>

<?php if ( ! empty($messages['success'])): ?>
<div class="alert alert-success animated fadeIn">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true"><i class="fal fa-times-circle"></i></span>
                                                    </button>
	<p><?php echo $messages['success']; ?></p>
</div>
<?php endif; ?>

<?php 

	/**
	 * Admin Notification Event
	 */
	Events::trigger('admin_notification');
	
?>