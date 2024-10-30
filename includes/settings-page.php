<style>
    body{
        background-color: #fff;
    }
    #wpcontent {
        padding-left: 0px;
    }
</style>

<?php
    $siteName = get_bloginfo('name');
    $tokensList = get_option(INFINITY_COPY_API_KEY, []);
    $userConnected = false;
    $domainAuthorized = false;
    $userToken = "";
    $redirectUrl = "";
    $domain = home_url();
    $user = wp_get_current_user();
    $userId = $user->user_email ?: null;

    if (isset($_POST['connect']) && $userId) {
        $bytes = openssl_random_pseudo_bytes(32);
        $userToken = bin2hex($bytes);
        $tokensList = get_option(INFINITY_COPY_API_KEY);

        if (is_array($tokensList)) {
            $tokensList[$userId] = $userToken;
        } else {
            $tokensList = [$userId => $userToken];
        }

        update_option(INFINITY_COPY_API_KEY, $tokensList);

        $redirectUrl = sprintf('%s?domain=%s&user=%s&token=%s', INFINITY_COPY_APP_CONNECT_URL, $domain, $userId, $userToken);
    } elseif (isset($_POST['disconnect']) && isset($_POST['token'])) {
        $tokensList = get_option(INFINITY_COPY_API_KEY, []);
        $token      = sanitize_text_field($_POST['token']);
        $id         = array_search($token, $tokensList);

        if ($id && is_array($tokensList)) {
            unset($tokensList[$id]);
            update_option(INFINITY_COPY_API_KEY, $tokensList);
        }
    }

    if (is_array($tokensList) && array_key_exists($userId, $tokensList)) {
        $userConnected    = true;
        $userToken        = $tokensList[$userId];
        $domainAuthorized = WPInfinityCopy::checkDomainAuthorization($domain, $userId, $userToken);
    }
?>

<nav>
    <img src="<?php echo esc_url(plugins_url('../assets/images/infinity-copy-logo.svg', __FILE__ )); ?>" alt="">
    <h1>Configurações</h1>
</nav>

<div class="container">
    <div class="titleContainer">
        <h2>Infinity Copy + WordPress: crie lá, publique aqui!</h2>
        <p class="h2Subtitle">Crie conteúdos utilizando o Escritor e, depois que finalizá-lo, envie como rascunho para sua lista de artigos.</p>
    </div>
    <div class="flexContainer">
        <div class="left">
            <form action="" method="post" class="generic-form">
                <?php if (!$userConnected): ?>
                    <input type="hidden"
                           name="connect"
                           value="true"
                    />
                <?php elseif (!isset($_POST['connect'])): ?>
                    <input type="hidden"
                           name="token"
                           value="<?php echo esc_attr($userToken); ?>"
                    >
                    <input type="hidden"
                           name="disconnect"
                           value="true"
                    />
                <?php endif; ?>
                <?php if ($userConnected && !isset($_POST['connect'])): ?>
                    <div class="boxIntegration active" id="actived">
                        <h3>Integração ativada</h3>
                        <p>Este WordPress está integrado à conta Infinity Copy</p>
                        <?php if (false): ?>
                            <span><?php echo esc_attr($userId); ?></span>
                        <?php endif; ?>

                        <input type="submit"
                               id="submit"
                               name="submit"
                               class="button buttonRed"
                               value="<?php echo esc_attr(__('Desativar integração', 'infinity-copy')); ?>"
                        />
                    </div>
                <?php else: ?>
                    <div class="boxIntegration" id="disabled">
                        <div class="login">
                            <h3>Ative sua integração</h3>
                            <p>Assista ao vídeo e ative a integração da sua conta Infinity Copy com WordPress</p>
                            <input type="submit"
                                   id="submit"
                                   name="submit"
                                   class="button buttonBlue"
                                   value="<?php echo esc_attr(!isset($_POST['connect']) ? __('Ativar integração', 'infinity-copy') : __('Redirecionando para Infinity Copy ...', 'infinity-copy')); ?>"
                            />
                        </div>
                        <div class="register">
                            <h3>Não possui conta?</h3>
                            <p>Crie agora e ganhe 3.000 palavras para testar grátis por 7 dias</p>
                            <a href="https://app.infinitycopy.ai/register?utm_source=wordpress-plugin" target="_blank">
                                <button type="button" class="button buttonBlueLine"><?php _e('Crie sua conta grátis na Infinity Copy', 'infinity-copy'); ?></button>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
        <?php /*
        <div class="right">
            <div class="video-container">
                <iframe
                        class="video"
                        src="https://www.youtube.com/embed/XhhCh8f3GS4"
                        frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope;"
                        allowfullscreen>
                </iframe>
            </div>
        </div>
 */ ?>
    </div>
</div>

<?php if (isset($_POST['connect'])): ?>
    <script>
        setTimeout(function() {
            window.location = '<?php echo esc_attr(INFINITY_COPY_APP_CONNECT_URL) ?>?domain=<?php echo esc_attr($domain) ?>&user=<?php echo esc_attr($userId) ?>&token=<?php echo esc_attr($userToken) ?>&name=<?php echo esc_attr($siteName) ?>';
        }, 2000);
    </script>
<?php endif; ?>