<div id="guard_box">
    <h2>Please Log In</h2>
    <div id="guard_content">
        <p>You must log in to use this application.</p>
        <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
            <?php
                echo $form['username']->renderRow();
                echo $form['password']->renderRow();
            ?>
            <div class="formrow">
                <div class="formrow-fields">
                    <?php echo $form['remember']->render(); ?>
                    Stay Logged In
                </div>
            </div>
            <div style="margin-left: 170px; margin-top: 15px;">
                <button type="submit" value="log in">Log In</button>
            </div>
        </form>
    </div>
</div>
