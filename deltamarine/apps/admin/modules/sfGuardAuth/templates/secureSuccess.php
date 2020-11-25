<div id="guard_box">
    <h2>Access Denied</h2>
    <div id="guard_content">
        <h3>Your user does not have permission to access to this feature or function.</h3> 
        <p>Please contact the administrator to perform this action for you or to grant you permission to do it yourself.</p>
        <p><?php echo link_to(image_tag('silkicon/application_go'), '@homepage').link_to('Return to Dashboard', '@homepage'); ?></p>
        <p><?php echo link_to(image_tag('silkicon/application_go'), '@sf_guard_signout').link_to('Log Out', '@sf_guard_signout'); ?></p>
    </div>
</div>
