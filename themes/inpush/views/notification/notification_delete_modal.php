<?php defined('INPUSH') || die() ?>

<div class="modal fade" id="notification_delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title"><?= language()->notification_delete_modal->header ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?= language()->global->close ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form name="notification_delete_modal" method="post" action="<?= url('notification/delete') ?>" role="form">
                    <input type="hidden" name="token" value="<?= \Inpush\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="notification_id" value="" />

                    <p class="text-muted"><?= language()->notification_delete_modal->subheader ?></p>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-danger"><?= language()->global->delete ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('#notification_delete_modal').on('show.bs.modal', event => {
        let notification_id = $(event.relatedTarget).data('notification-id');

        $(event.currentTarget).find('input[name="notification_id"]').val(notification_id);
    });
</script>
<?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>
