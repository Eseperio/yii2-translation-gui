<?php


/* @var $details array<array> an array like [ category => [message=> translation]] */
/* @var $language string */
/* @var $this \yii\web\View */
/* @var $pattern string */
?>

<?php foreach ($details['messages'] as $category => $content): ?>
    <?php if ($details['pattern'] !== $category): ?>
        <h2><?= $category ?></h2>
    <?php endif; ?>
    <?php foreach ($content as $message => $translation): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label><?= $message ?></label>
                    <textarea class="form-control" data-locale="<?= $language['language_id'] ?>"
                              data-category="<?= $category ?>"
                              data-message="<?= $message ?>"><?= $translation ?></textarea>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>
