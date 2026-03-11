<?php if ($this->getUser() !== null): ?>
    <div class="game-login-box game-login-box-auth">
        <p class="game-login-greeting"><?=$this->getTrans('hello') ?> <strong><?=$this->escape($this->getUser()->getName()) ?></strong></p>
        <div class="game-login-links">
            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'panel', 'action' => 'index']) ?>"><?=$this->getTrans('userPanel') ?></a>
            <div class="ilch--new-message"></div>
            <?php if ($this->get('userAccesses') || $this->getUser()->isAdmin()): ?>
                <a target="_blank" href="<?=$this->getUrl(['module' => 'admin', 'controller' => 'admin', 'action' => 'index']) ?>"><?=$this->getTrans('admincenter') ?></a>
            <?php endif; ?>
            <a href="<?=$this->getUrl(['module' => 'admin/admin', 'controller' => 'login', 'action' => 'logout', 'from_frontend' => 1]) ?>"><?=$this->getTrans('logout') ?></a>
        </div>
    </div>
<?php else: ?>
    <script>$(document).ready(function(){ $('.providers').on('click', function (e) { e.preventDefault(); var myForm = $(this).closest('form')[0]; myForm.action = this.href; myForm.method = "POST"; myForm.submit(); return false; }); });</script>
    <form class="game-login-box" action="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'index']) ?>" method="post">
        <input type="hidden" name="login_redirect_url" value="<?=$this->escape($this->get('redirectUrl')) ?>">
        <?=$this->getTokenField(); ?>
        <div class="game-login-field">
            <span><i class="fa-solid fa-user"></i></span>
            <input type="text" name="login_emailname" placeholder="<?=$this->getTrans('nameEmail') ?>" autocomplete="username">
        </div>
        <div class="game-login-field">
            <span><i class="fa-solid fa-lock"></i></span>
            <input type="password" name="login_password" placeholder="<?=$this->getTrans('password') ?>" autocomplete="current-password">
        </div>
        <label class="game-login-check"><input type="checkbox" name="rememberMe" value="rememberMe"> <?=$this->getTrans('rememberMe') ?></label>
        <div class="game-login-actions">
            <button type="submit" class="btn btn-outline-secondary" name="login"><?=$this->getTrans('login') ?></button>
            <?php foreach ($this->get('providers') as $provider): ?>
                <a
                    class="providers provider-<?= $provider->getKey() ?>"
                    href="<?= $this->getUrl(['module' => $provider->getModule(), 'controller' => $provider->getAuthController(), 'action' => $provider->getAuthAction()]) ?>"
                >
                    <i class="fa-solid fa-fw <?= $provider->getIcon() ?>"></i>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="game-login-links">
            <?php if ($this->get('regist_accept') == '1'): ?>
                <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'regist', 'action' => 'index']) ?>"><?=$this->getTrans('register') ?></a>
            <?php endif; ?>
            <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'login', 'action' => 'forgotpassword']) ?>"><?=$this->getTrans('forgotPassword') ?></a>
        </div>
    </form>
<?php endif; ?>

<?php if ($this->getUser() !== null): ?>
    <script>
        $(document).ready(function () {
            let notificationsDiv = $(".ilch--new-message"),
                messageCheckLink = "<?=$this->getUrl(['module' => 'user', 'controller' => 'ajax','action' => 'checknewmessage']) ?>",
                openFriendRequestsCheckLink = "<?=$this->getUrl(['module' => 'user', 'controller' => 'ajax','action' => 'checknewfriendrequests']) ?>",
                globalStore = [];

            function loadNotifications()
            {
                $.when(
                    $.get(messageCheckLink, function(newMessages) {
                        globalStore['newMessages'] = newMessages;
                    }),

                    $.get(openFriendRequestsCheckLink, function(newFriendRequests) {
                        globalStore['newFriendRequests'] = newFriendRequests;
                    }),
                ).then(function() {
                    notificationsDiv.html(globalStore['newMessages']);
                    notificationsDiv.append(globalStore['newFriendRequests'])
                });
            }

            loadNotifications();
            setInterval(loadNotifications, 60000);
        });
    </script>
<?php endif; ?>
