<header>
    <div>
        <a href="index.php">
            <img src="/public/assets/logo.svg" alt="In a pixelised font: SS Smartcity Security">
        </a>
        <ul>
            <li>
                <form method="GET" action="controller.php" style="margin-top: 20px;">
                    <input type="hidden" name="controller" value="dashboard">
                    <input type="hidden" name="action" value="index">
                    <button type="submit">Dashboard</button>
                </form>
            </li>
            <li>
                <form method="GET" action="controller.php" style="margin-top: 20px;">
                    <input type="hidden" name="controller" value="alert">
                    <input type="hidden" name="action" value="index">
                    <button type="submit">Enregistrements</button>
                </form>
            </li>
        </ul>
    </div>
</header>