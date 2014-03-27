<div class="alert in alert-block fade alert-error"><?= $message ?></div>
<pre><?= $file.'('.$line.')' ?></pre>
<?php if (!empty($trace)): ?>
    <pre><?= $trace ?></pre>
<?php endif; ?>
