<?php
$user = $context->getSteamUser();
if ($user->personaname) {
    echo "You are connected as " . $user->personaname;
}
?>

<p>
    <a href="<?php echo $context->getEditURL(); ?>">Connect with steam</a>
</p>