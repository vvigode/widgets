<?php defined('INPUSH') || die() ?>

<div class="modal fade" id="create_notification_data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= language()->notification->create_review_data_modal->header ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_notification_data" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Inpush\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="notification_id" value="<?= $data->notification->notification_id ?>" />

                    <div class="form-group">
                        <label><?= language()->notification->create_review_data_modal->input->title ?></label>
                        <input type="hidden" class="form-control" name="key[]" value="title" required="required" />
                        <input type="text" class="form-control" name="value[]" required="required" />
                    </div>

                    <div class="form-group">
                        <label><?= language()->notification->create_review_data_modal->input->description ?></label>
                        <input type="hidden" class="form-control" name="key[]" value="description" required="required" />
                        <input type="text" class="form-control" name="value[]" required="required" />
                    </div>

                    <div class="form-group">
                        <label><?= language()->notification->settings->image ?></label>
                        <input type="hidden" class="form-control" name="key[]" value="image" required="required" />
                        <input type="text" class="form-control" name="value[]" required="required" />
                    </div>

                    <div class="form-group">
                        <label for=""><?= language()->notification->settings->image_alt ?></label>
                        <input type="hidden" class="form-control" name="key[]" value="image_alt" required="required" />
                        <input type="text" class="form-control" name="value[]" required="required" maxlength="100" />
                        <small class="form-text text-muted"><?= language()->notification->settings->image_alt_help ?></small>
                    </div>

                    <div class="form-group">
                        <label><?= language()->notification->create_review_data_modal->input->stars ?></label>
                        <input type="hidden" class="form-control" name="key[]" value="stars" />
                        <input type="number" value="5" min="1" max="5" class="form-control" name="value[]" />
                        <small class="form-text text-muted"><?= language()->notification->create_review_data_modal->input->stars_help ?></small>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= language()->global->create ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    $('form[name="create_notification_data"]').on('submit', event => {

        $.ajax({
            type: 'POST',
            url: 'notification-data-ajax',
            data: $(event.currentTarget).serialize(),
            success: (data) => {
                if (data.status == 'error') {
                    notification_container.html('');

                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    /* Hide modal */
                    $('#create_notification_data').modal('hide');

                    /* Clear input values */
                    $('form[name="create_notification_data"] input').val('');

                    /* Fade out refresh */
                    redirect(`<?= url('notification/' . $data->notification->notification_id . '/data') ?>`, true);

                }
            },
            dataType: 'json'
        });

        event.preventDefault();
    })
</script>
<?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>
