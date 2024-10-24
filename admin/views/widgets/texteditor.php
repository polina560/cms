<?php ?>

<div>
       <textarea name="<?= $name ?>" id="<?= $name ?>"><?= $value ?></textarea>
</div>
<script>
    ClassicEditor
        .create(document.querySelector('#<?= $name ?>'))
        .catch(error => {
            console.error(error);
        });
</script>
