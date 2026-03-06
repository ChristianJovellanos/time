<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>iClock Pro — iOS 26</title>
    <style>
        :root {
            --ios-bg: #000000;
            --ios-glass: rgba(255, 255, 255, 0.08);
            --ios-border: rgba(255, 255, 255, 0.15);
            --ios-blue: #0a84ff;
            --ios-gold: #ffd60a;
            --ios-text: #ffffff;
            --ios-secondary: #8e8e93;
            --sf-font: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", "Helvetica Neue", sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: var(--sf-font);
            -webkit-tap-highlight-color: transparent;
        }

        body {
            min-height: 100vh;
            padding: 20px;
            background: radial-gradient(circle at top right, #1c1c1e, #000000);
            color: var(--ios-text);
            -webkit-font-smoothing: antialiased;
        }

        /* iOS Header */
        header {
            text-align: center;
            margin-bottom: 30px;
            padding-top: 20px;
        }

        h2 {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -1px;
            background: linear-gradient(180deg, #ffffff 30%, #a1a1a1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* iOS Search Bar Material */
        .search-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto 30px auto;
        }

        input {
            width: 100%;
            padding: 14px 20px;
            border-radius: 14px;
            border: none;
            background: rgba(118, 118, 128, 0.24);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            color: white;
            font-size: 1rem;
            outline: none;
        }

        /* Toggle Button */
        .controls {
            text-align: center;
            margin-bottom: 25px;
        }

        button {
            background: var(--ios-blue);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        button:active { transform: scale(0.92); }

        /* Widget Grid (The Clocks) */
        .clock-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 40px;
        }

        /* The Squircle Glass Card */
        .clock-card {
            background: var(--ios-glass);
            backdrop-filter: blur(50px) saturate(200%);
            -webkit-backdrop-filter: blur(50px) saturate(200%);
            border-radius: 30px; 
            padding: 24px;
            text-align: center;
            border: 0.5px solid var(--ios-border);
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: iosScaleIn 0.6s ease-out forwards;
        }

        .clock-card:hover {
            transform: scale(1.04) translateY(-5px);
            background: rgba(255, 255, 255, 0.12);
        }

        .flag { font-size: 32px; margin-bottom: 10px; display: block; }
        
        h3 {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--ios-secondary);
            margin-bottom: 4px;
        }

        .time {
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: -1.5px;
            color: #fff;
            margin: 5px 0;
        }

        .date {
            font-size: 0.85rem;
            color: var(--ios-blue);
            font-weight: 500;
        }

        /* Glass Table (Reference Guide) */
        .table-container {
            background: var(--ios-glass);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border-radius: 24px;
            border: 0.5px solid var(--ios-border);
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        th {
            padding: 18px;
            font-size: 0.75rem;
            color: var(--ios-secondary);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            background: rgba(255,255,255,0.03);
            border-bottom: 0.5px solid var(--ios-border);
        }

        td {
            padding: 14px;
            font-size: 0.95rem;
            text-align: center;
            border-bottom: 0.5px solid rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.8);
        }

        /* Philippines Column - Vibrant Gold */
        td:last-child {
            color: var(--ios-gold);
            font-weight: 600;
            background: rgba(255, 214, 10, 0.05);
        }

        tr:hover { background: rgba(255,255,255,0.03); }

        @keyframes iosScaleIn {
            from { opacity: 0; transform: scale(0.8) translateY(30px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .dark-mode-bg { background: #111 !important; }

    </style>
</head>
<body id="body">

    <header>
        <h2>TIMEZONE</h2>
    </header>

    <div class="controls">
        <button onclick="toggleTheme()">Appearance</button>
    </div>

    <div class="search-container">
        <input type="text" id="search" placeholder="Search for a city or zone..." onkeyup="filterClocks()">
    </div>

    <?php
    // Mapping for Live Clocks
    $clockData = [
        ["name" => "Eastern", "tz" => "America/New_York", "flag" => "🇺🇸"],
        ["name" => "Central", "tz" => "America/Chicago", "flag" => "🇺🇸"],
        ["name" => "Mountain", "tz" => "America/Denver", "flag" => "🇺🇸"],
        ["name" => "Pacific", "tz" => "America/Los_Angeles", "flag" => "🇺🇸"],
        ["name" => "Alaska", "tz" => "America/Anchorage", "flag" => "🇺🇸"],
        ["name" => "Hawaii", "tz" => "Pacific/Honolulu", "flag" => "🇺🇸"],
        ["name" => "Philippines", "tz" => "Asia/Manila", "flag" => "🇵🇭"]
    ];
    ?>

    <div class="clock-grid">
        <?php foreach ($clockData as $c): ?>
        <div class="clock-card" data-search-name="<?php echo strtolower($c['name']); ?>">
            <span class="flag"><?php echo $c['flag']; ?></span>
            <h3><?php echo $c['name']; ?></h3>
            <div class="time live-time" data-timezone="<?php echo $c['tz']; ?>">--:--:--</div>
            <div class="date live-date" data-timezone="<?php echo $c['tz']; ?>">Loading...</div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <?php foreach ($clockData as $c) echo "<th>" . $c['name'] . "</th>"; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fixed Reference Start (Matches your image's 8:00 AM EST)
                $refTime = new DateTime("2026-03-07 08:00:00", new DateTimeZone("America/New_York"));
                for ($i = 0; $i < 24; $i++) {
                    echo "<tr>";
                    foreach ($clockData as $c) {
                        $rowTime = clone $refTime;
                        $rowTime->setTimezone(new DateTimeZone($c['tz']));
                        echo "<td>" . $rowTime->format("h:i A") . "</td>";
                    }
                    echo "</tr>";
                    $refTime->modify("+1 hour");
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Live Clock Engine
        function updateAllClocks() {
            const timeElements = document.querySelectorAll('.live-time');
            const dateElements = document.querySelectorAll('.live-date');

            timeElements.forEach((el, index) => {
                const tz = el.getAttribute('data-timezone');
                const now = new Date();

                // Format Time with Seconds
                const timeStr = now.toLocaleTimeString('en-US', {
                    timeZone: tz,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });

                // Format Date
                const dateStr = now.toLocaleDateString('en-US', {
                    timeZone: tz,
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });

                el.textContent = timeStr;
                dateElements[index].textContent = dateStr;
            });
        }

        // Live Search Filter
        function filterClocks() {
            const query = document.getElementById('search').value.toLowerCase();
            const cards = document.querySelectorAll('.clock-card');
            
            cards.forEach(card => {
                const name = card.getAttribute('data-search-name');
                card.style.display = name.includes(query) ? 'block' : 'none';
            });
        }

        // Theme Toggle
        function toggleTheme() {
            document.getElementById('body').classList.toggle('dark-mode-bg');
        }

        // Initialize
        setInterval(updateAllClocks, 1000);
        updateAllClocks();
    </script>
</body>
</html>